<?php
if (isset($_SESSION['customer_id']))
header("Location: ../pages/estore.php");
$retrying = isset($_GET['retrying']) ? true : false;
$_SESSION['POST_SAVE'] = $_POST;
?>
<?php include "../common/document_head.html" ?>
<main class="w3-container">
<?php include "../common/banner.php" ?>

<?php include "../common/menus.html" ?>


<article class="w3-container w3-border-left w3-border-right
                     w3-border-black w3-light-grey">
        <h4 class="w3-center"><strong>Login Form</strong></h4>
        
        <p class="w3-center w3-red w3-padding">Important Note</p>
        
        <p><span class="w3-padding-16">Purchasing items from our on-line
        e-store requires logging in. And if you have not yet registered with 
        Amazing Stuff, before attempting to log in you must
        <a href="submissions/submission06/pages/formRegistration.php">register here</a>.</span></p>
        
        <form id="loginForm"
              action="submissions/submission06/scripts/formLoginProcess.php"
              method="post" autocomplete="on">
        <div class="w3-row w3-section">
          <div class="w3-quarter w3-container">
            Login Name:
          </div>
          <div class="w3-threequarter w3-container w3-wide">
            <input type="text" name="loginName" required
                   style="width: 90%;"
                   placeholder="Must be name assigned at registration"
                   value="">
          </div>
        </div>               
        <div class="w3-row">
          <div class="w3-quarter w3-container">
            Password:
          </div>
          <div class="w3-threequarter w3-container w3-wide">
            <input type="password" name="loginPassword" required
                   style="width: 90%;"
                   placeholder="Must be password chosen at registration"
                   value="">
          </div>
        </div>               
        <div class="w3-row w3-section">
          <div class="w3-quarter w3-container">
            &nbsp;
          </div>
          <div class="w3-threequarter w3-container">
            <input type="submit"
                   value="Log in">
            <input type="reset"
                   value="Reset Form" onclick="this.form.reset()">
          </div>
        </div>               
              
        <div class="w3-row">
          <?php if ($retrying) { ?>

            <p class = "w3-red w3-padding">
             Invalid login and/or password!
            </p>
            <?php }  ?> 
                  </div>
              
        </form>
      </article>
    </main>
<?php include "../common/footer.html" ?>