<?php 

session_start();
$customerID = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : "";
$productID = $_GET['productID'];
//else {$productID = 'view';}
if ($customerID == "")
{
    $_SESSION['purchasePending'] = $productID;
    header("Location: formLogin.php");
}
 if($productID == "")
 {
   $productID = 'view';
 }
include "../common/document_head.html" ?>
<?php include("../scripts/connectToDatabase.php") ?>
<main class="w3-container">
<?php include "../common/banner.php" ?>

<?php include "../common/menus.html" ?>


      <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey">
                 <?php
        include("../scripts/shoppingCartProcess.php");
        ?>
      </div>
    </main>
<?php include "../common/footer.html" ?>