<?php
/*shoppingCartAddItem.php
Adds an item to the user's shopping cart, and redisplays the cart.
*/
session_start();
include("connectToDatabase.php");

//========== main script begins here
$customerID = $_SESSION['customer_id'];
$productID = $_GET['productID'];

//Get the order ID for the current order in progress
$query =
    "SELECT
        as_Order.order_id,
        as_Order.order_status_code,
        as_Order.customer_id
    FROM as_Order
    WHERE
        as_Order.order_status_code = 'IP' and
        as_Order.customer_id = $customerID";
$order = mysqli_query($db, $query) or die (mysqli_error($db));    
$row = mysqli_fetch_array($order, MYSQLI_ASSOC);
$orderID = $row['order_id'];

//Get the quantity in inventory of the requested product
$query =
    "SELECT *
    FROM as_Product
    WHERE product_id = '$productID'";
$product = mysqli_query($db, $query) or die (mysqli_error($db));
$row = mysqli_fetch_array($product, MYSQLI_ASSOC);
$productInventory = $row['quantity'];

$quantityRequested = $_GET['quantity'];
if ($quantityRequested == 0 or $quantityRequested > $productInventory)
{
    $gotoRetry = "../pages/shoppingCart.php?
                  productID=$productID&retrying=true";
    header("Location: $gotoRetry");
}
else
$Name = $row['name'];
{
    $productPrice = $row['price'];
    $query = "INSERT INTO as_Order_Items
    (
        order_item_name,
        order_item_status_code,
        product_item_id,
        order_item_quantity,
        order_item_price,
        other_order_item_details
    )
    VALUES
    (
        '$row[name]',
        'IP',
        '$productID',
        '$quantityRequested',
        '$productPrice',
        NULL
    )";
    $success = mysqli_query($db, $query) or die ("Error printing $Name: ".mysqli_error($db));
    header("Location: ../pages/shoppingCart.php?productID=view");
}
//========== main script ends here
?>
