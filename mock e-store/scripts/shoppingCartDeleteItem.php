<?php
/*shoppingCartDeleteItem.php
Deletes an item from the user's shopping cart, and
redisplays the cart.
*/
session_start();
include("connectToDatabase.php");

$orderItemID = $_GET['orderItemID'];
$orderID = $_GET['orderID'];
$query =
    "DELETE FROM as_Order_Items
    WHERE order_item_id='$orderItemID'";
$success = mysqli_query($db, $query) or die (mysqli_error($db));
$query =
    "SELECT COUNT(*) AS numItemsStillInOrder
    FROM as_Order_Items
    WHERE order_item_id='$orderID'";
$return_value = mysqli_query($db, $query) or die (mysqli_error($db));
$row = mysqli_fetch_array($return_value, MYSQLI_ASSOC);
if ($row[numItemsStillInOrder] == 0)
{
    $query = "DELETE FROM as_Order
                     WHERE order_id='$orderID'";
    $success = mysqli_query($db, $query) or die (mysqli_error($db));

}
header("Location: ../pages/shoppingCart.php?productID=view");
?>
