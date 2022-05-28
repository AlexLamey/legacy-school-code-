<?php
/*checkoutProcess.php
Displays a receipt to confirm the client's purchase(s)
and adjusts the database inventory levels accordingly.
Has a very short main driver, but uses eight helper
functions, all of which are defined below.
Calls displayReceipt() once, which in turn calls
--getExistingOrder() once
--displayReceiptHeader() once
--displayItemAndReturnTotalPrice() once for each item in the order
--displayReceiptFooter() once
Calls markOrderPaid() once
Calls markOrderItemsPaid() once, which in turn calls
--reduceInventory() once for each item in the order
*/
//error_reporting(E_ALL);

//========== main script begins here
displayReceipt($db, $customerID);

//Get the order ID for the order in progress
$query =
    "SELECT
        as_Order.order_id,
        as_Order.customer_id,
        as_Order.order_status_code,
        as_Order_Items.*
    FROM
        as_Order_Items, as_Order
    WHERE
        as_Order.order_id = as_Order_Items.order_item_id and
        as_Order.order_status_code = 'IP'        and
        as_Order.customer_id = $customerID";
$orderInProgress = mysqli_query($db, $query) or die (mysqli_error($db));
$orderInProgressArray = mysqli_fetch_array($orderInProgress);
$orderID = $orderInProgressArray[0];

//Now mark as paid both the order itself and its order items 
markOrderPaid($db, $customerID, $orderID);
markOrderItemsPaid($db, $orderID);
mysqli_close($db);
//========== main script ends here

/*displayReceipt()
The "driver" routine for preparing and displaying a receipt
for the items purchased in the current order being checked out.
*/
function displayReceipt($db, $customerID)
{
    $items = getExistingOrder($db, $customerID);
    $numRecords = mysqli_num_rows($items);
    if($numRecords == 0)
    {
        echo
        "<h4 class='ShoppingCartHeader'>Shopping Cart</h4>
        <p class='Notification'>Your shopping cart is empty.</p>
        <p class='Notification'>To continue shopping, please
        <a class='NoDecoration' href='submissions/submission06/pages/storeCatalogue.php'>click
        here</a>.</p>";
        exit(0);
    }
    else
    {
        displayReceiptHeader();
        $grandTotal = 0;
        for($i=1; $i<=$numRecords; $i++)
        {
            $row = mysqli_fetch_array($items, MYSQLI_ASSOC);
            $grandTotal += displayItemAndReturnTotalPrice($db, $row);
        }
        displayReceiptFooter($grandTotal);
    }
}

/*getExistingOrder()
Gets and returns the purchased items in the order
being checked out.
*/
function getExistingOrder($db, $customerID)
{
    $query = 
        "SELECT
            as_Order.order_id,
            as_Order.customer_id,
            as_Order.order_status_code,
            as_Order_Items.*
        FROM
            as_Order_Items, as_Order
        WHERE
            as_Order.order_id = as_Order_Items.order_item_id and
            as_Order.order_status_code = 'IP' and
            as_Order.customer_id = '$customerID'";
    $items = mysqli_query($db, $query) or die (mysqli_error($db));
    return $items;
}

/*displayReceiptHeader()
Displays user information and the date, as well as column
headers for the table of purchased items.
*/
function displayReceiptHeader()
{
    $date = date("F j, Y");
    $time = date('g:ia');
    echo
    "<p class='ReceiptTitle'>***** R E C E I P T *****</p>
    <p class='Notification'>
      Payment received from
      $_SESSION[salutation]
      $_SESSION[first_name]
      $_SESSION[middle_initial]
      $_SESSION[last_name] on $date at $time.
    </p>";
    echo
    "<table class='Receipt' border=1px'>
      <tr>
        <th>Product Image</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
      </tr>";
}

/*displayItemAndReturnTotalPrice()
Displays one table row containing the information for
one purchased item.
*/
function displayItemAndReturnTotalPrice($db, $row)
{
    $productID = $row['product_item_id'];
    $query = "SELECT * FROM as_Product WHERE product_id ='$productID'";
    $product = mysqli_query($db, $query) or die (mysqli_error($db));
    $rowProd = mysqli_fetch_array($product, MYSQLI_ASSOC);
    $productPrice = $rowProd['price'];
    $productPriceAsString = sprintf("$%1.2f", $productPrice);
    $totalPrice = $row['order_item_quantity'] * $rowProd['price'];
    $totalPriceAsString = sprintf("$%1.2f", $totalPrice);
    $imageLocation = $rowProd['image_file'];
    echo
    "<tr>
      <td class='Centered'>
        <img height='70' width='70'
             src='submissions/submission06/images/products/$imageLocation' alt='Product Image'>
      </td><td class='LeftAligned'>
        $rowProd[name]
      </td><td class='RightAligned'>
        $productPriceAsString
      </td><td class='RightAligned'>
        $row[order_item_quantity]
      </td><td class='RightAligned'>
        $totalPriceAsString
      </td>
    </tr>";
    return $totalPrice;
}

/*displayReceiptFooter()
Displays the total amount of the purchase and additional
information in the footer of the receipt.
*/
function displayReceiptFooter($grandTotal)
{
    $grandTotalAsString = sprintf("$%1.2f", $grandTotal);
    echo
    "<tr>
      <td class='Notification' colspan='4'>
        Grand Total
      </td><td class='RightAligned'>
        <strong>$grandTotalAsString</strong>
      </td>
    </tr><tr>
      <td colspan='5'>
        <p class='Notification'>Your order has been processed.
        <br>Thank you very much for shopping with Lynn Mountain Meadows.
        <br>We appreciate your purchase of the above product(s).
        <br>You may print a copy of this page for your permanent record.
        <br>To return to our e-store options page please
          <a href='submissions/submission06/pages/estore.php' class='NoDecoration'>click here</a>.
        <br>Or, you may choose one of the navigation links from our
        menu options.</p>
          
        <p class='LeftAligned'>Note to readers of the text:<br>
        We have only marked, in our database, the order and corresponding
        order items as paid, and reduced the database inventory in our
        Products table accordingly. The revised inventory levels should
        appear in any subsequent display of an affected product. Actual
        handling of payments and shipment is beyond the scope of our text.
        Besides, if truth be told, we have nothing to sell!</p>
      </td>
    </tr>
  </table>";
}

/*markOrderPaid()
Changes the status in the database of the order being checked
out from IP (in progress) to PD (paid).
*/
function markOrderPaid($db, $customerID, $orderID)
{
    $query =
        "UPDATE as_Order
        SET order_status_code = 'PD'
        WHERE customer_id = '$customerID' and
              order_id ='$orderID'";
    $success = mysqli_query($db, $query);
}

/*markOrderItemsPaid()
Changes the status in the database of each item purchased
from IP (in progress) to PD (paid).
*/
function markOrderItemsPaid($db, $orderID)
{
    $query =
        "SELECT *
        FROM as_Order_Items
        WHERE order_item_id = '$orderID'";
    $orderItems = mysqli_query($db, $query);
    $numRecords = mysqli_num_rows($orderItems);
    for($i=1; $i<=$numRecords; $i++)
    {
        $row = mysqli_fetch_array($orderItems, MYSQLI_ASSOC);
        $query =
            "UPDATE as_Order_Items
            SET order_item_status_code = 'PD'
            WHERE product_item_id = $row[product_item_id] and
                  order_item_id = $row[order_item_id]";        
        mysqli_query($db, $query) or die (mysqli_error($db));
        reduceInventory($db, $row['product_item_id'],
                             $row['order_item_quantity']);
    }
}

/*reduceInventory()
Reduces the inventory level in the database of the product
purchased by the amount purchased.
*/
function reduceInventory($db, $productID, $quantityPurchased)
{
    $query = "SELECT * FROM as_Product WHERE product_id = '$productID'";
    $product = mysqli_query($db, $query) or die (mysqli_error($db));
    $row = mysqli_fetch_array($product, MYSQLI_ASSOC);
    $row['quantity'] -= $quantityPurchased;
    $query =
        "UPDATE as_Product
        SET quantity = $row[quantity]
        WHERE product_id = $row[product_id]";
    mysqli_query($db, $query);
}
?>
