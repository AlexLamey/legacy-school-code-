<?php
$categoryCode = $_GET['categoryCode'];
$query = "SELECT * FROM as_Product WHERE catCode = '$categoryCode' ORDER BY name ASC;";
$category = mysqli_query($db, $query) or die(mysqli_error($db));
$numRecords = mysqli_num_rows($category);
echo "<table class='item'>
<tr>
    <th>Product Image</th>
    <th>Product Name</th>
    <th>Price</th>
    <th># in stock</th>
    <th>Purchase?</th>
</tr>";
for ($i=1; $i<=$numRecords; $i++) { 
    $row = mysqli_fetch_array($category, MYSQLI_ASSOC);
    $productImageFile = $row['image_file'];
    $productName = $row['name'];
    $productPrice = $row['price'];
    $productPriceAsString = sprintf("$%.2f", $productPrice);
    $productQuantity = $row['quantity'];
    $productID = $row['product_id'];
    echo "<tr>
    <td class = 'center'>
    <img width='70' 
    src = 'submissions/submission06/images/products/$productImageFile'
    alt='Product Image'>
    </td>
    <td>
    $productName
    </td> 
    <td class ='right'>
    $productPriceAsString
    </td>
    <td class = 'center'>
    $productQuantity
    </td>
    <td>
    <a class='w3-button w3-blue w3-round w3-small'
    href = 'submissions/submission06/pages/shoppingCart.php?productID=$productID'>
    Add to cart
    </a>
    </td>
    </tr>";

    
}
echo "</table>";
mysqli_close($db);
?>