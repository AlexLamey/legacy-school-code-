<?php include "../common/document_head.html" ?>
<main class="w3-container">
<?php include "../common/banner.php" ?>

<?php include "../common/menus.html" ?>
<?php include "../scripts/connectToDatabase.php"?>

      <div class="w3-container w3-border-left w3-border-right
                 w3-border-black w3-light-grey">
                 <h4>
          Complete List of Product Categories
        </h4>
                 <?php include "../scripts/catalogProcess.php" ?>
    </main>
<?php include "../common/footer.html" ?>