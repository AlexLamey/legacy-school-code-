<?php 
session_start();
include "../scripts/connectToDatabase.php";

//$notloggedIn = isset($_SESSION['customer_id']) ? true : false;
if (!isset($_SESSION['customer_id'])) $notLoggedIn = true;
else {$notLoggedIn = false;
$customerID = $_SESSION['customer_id'];
include "../scripts/logoutProcess.php";}

//$notLoggedIn = $_SESSION["customer_id" == ""] ? true : false;
session_unset();
session_destroy();
include "../common/document_head.html" ?>
<main class="w3-container">
  
<?php include "../common/banner.php" ?>

<?php include "../common/menus.html" ?>


      <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey">
                 <h4>Logout</h4>
        
      <!-- is this convoluted? yes. yes it is. Are we sticking with it? also yes, barring lost marks -->
        <?php if (!$notLoggedIn) { ?>
        
        <p><br>Thank you for visiting our e-store.<br>
           You have successfully logged out.</p>
        <p>If you wish to log back in,
          <a href="submissions/submission06/pages/formLogin.php"
             class="NoDecoration">click here</a>.</p>
        <p>To browse our product catalog, 
          <a href="submissions/submission06/pages/storeCatalogue.php"
             class="NoDecoration">click here</a>.</p>
             
        <?php } else { ?>
        
        <p><br>Thank you for visiting the Lynn Mountain Meadows website.<br>
           You have not yet logged in.</p>
        <p>If you do wish to log in,
          <a href="submissions/submission06/pages/formLogin.php"
             class="NoDecoration">click here</a>.</p>
        <p>Or, just to browse our product catalog,
          <a href="submissions/submission06/pages/storeCatalogue.php"
             class="NoDecoration">click here</a>.</p>
             
        <?php } ?>
      </div>
    </main>
<?php include "../common/footer.html" ?>