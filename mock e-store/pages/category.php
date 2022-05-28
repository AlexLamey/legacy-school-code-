<?php session_start();
include "../common/document_head.html" 
?>
<main class="w3-container">
<?php include "../common/banner.php" ?>

<?php include "../common/menus.html" ?>
<?php include "../scripts/connectToDatabase.php"?>


<article class="w3-container w3-border-left w3-border-right
                     w3-border-black w3-light-grey">

<?php include "../scripts/categoryProcess.php" ?> 
</article>
    </main>
<?php include "../common/footer.html" ?>