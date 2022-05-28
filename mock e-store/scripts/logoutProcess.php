<?php
/*logoutProcess.php
Handles "clean up" by deleting any orders that
have been created by users who have tried to buy
items without being registered and logging in,
or by users who have logged in and started to
buy one or more items but changed their mind.
*/
//error_reporting(E_ALL);

//First delete all "orphaned" orders created
//by not-logged-in customers who tried to buy
//an item, but did not follow up when they
//discovered registration and login were
//required ...
$query =
    "DELETE FROM as_Order
    WHERE
        customer_id = 0 and
        order_status_code = 'IP'";
$success = mysqli_query($db, $query) or die (mysqli_error($db));

//Next, see if there is an order "in progress"
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
$items = mysqli_query($db, $query) or die (mysqli_error($db));
if ($items != null)
    $numRecords = mysqli_num_rows($items);
//If $numRecords is non-zero (actually, 1) there is
//an order in progress; if it is 0, there is no order
//in progress, but there may be any number of orders
//that were created by a logged-in user choosing to
//"Buy this item" but not actually buying it, and doing
//this one or more times before actually starting an
//order, thus creating one or more orders without any
//corresponding order items,
//so ...

if ($numRecords == 0)
//If there are "orphaned" orders for which there are no
//corresponding order items, find them and delete them ...
{
    $query =
        "SELECT
            order_id,
            customer_id,
            order_status_code
        FROM as_Order
        WHERE
            order_status_code = 'IP' and
            customer_id = $customerID";
    $orphanedOrders = mysqli_query($db, $query);
    if ($orphanedOrders != null)
    {
        $numRecords = mysqli_num_rows($orphanedOrders);
        if ($numRecords != 0)
        {
            for ($i=0; $i<$numRecords; $i++)
            {
                $orphanedOrdersArray =
                    mysqli_fetch_array($orphanedOrders, MYSQLI_ASSOC);
                $orphanedOrderID = $orphanedOrdersArray['order_id'];
                $query =
                    "DELETE FROM as_Order
                    WHERE
                        order_id = '$orphanedOrderID' and
                        order_status_code = 'IP'      and
                        customer_id = '$customerID'";
                $success = mysqli_query($db, $query) or die(mysqli_error($db));
            }
        }
    }
}
mysqli_close($db);
?>