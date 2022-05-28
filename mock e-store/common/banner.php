<!--Banner-->
<?php
if(!isset($_SESSION)) session_start();
$loggedIn = isset($_SESSION['customer_id']) ? true : false;

        if (isset($_SESSION['customer_id'])) {
            $customerID = $_SESSION['customer_id'];
        }
        if (isset($_SESSION['salutation'])) {
            $salutation = $_SESSION['salutation'];
        }
        if (isset($_SESSION['first_name'])) {
            $customerFirstName = $_SESSION['first_name'];
        }
        if (isset($_SESSION['middle_initial'])){
            $customerMiddleInitial = $_SESSION['middle_initial'];
        }
        if (isset($_SESSION['last_name'])) {
            $customerLastName = $_SESSION['last_name'];
            }
            ?>
<body class="body w3-auto" onload = "carousel()">
    
      <div class="w3-border w3-border-black w3-light-grey">
              <div id="logo" class="w3-half">
          <img src="submissions/submission06/images/LynnLogo.PNG"
               alt="Lynn Mountain Meadows Logo"
               style="width: 100%">
      </div>      
      
      <div class="w3-half w3-right-align">
        <div class="w3-panel">
          <?php 
          if (!$loggedIn)
          {
              echo "<h5>Welcome!<br>";
          }
          else
          {
              echo "<h5>Welcome,<br>".
                  $salutation." ".
                  $customerFirstName." ".
                  $customerMiddleInitial." ".
                  $customerLastName."<br>";
          }
          include ($_SERVER['CONTEXT_DOCUMENT_ROOT']
          . "/submissions/submission06"
          . "/scripts/time.php");
          if ($loggedIn)
        {
            echo "</h5><a class='w3-button w3-blue w3-round'
                          href='submissions/submission06/pages/logout.php'>
                          Click here to log out</a>";
        }
        else
        {
            echo "<a class='w3-button w3-blue w3-round'
            href='submissions/submission06/pages/formLogin.php'>
           Click here to log in
         </a>";
        }
        ?>
          <!-- <a class='w3-button w3-blue w3-round'
             href='submissions/submission06/pages/formLogin.php'>
            Click here to log in
          </a> -->
          <p class="quote w3-left-align">
          <?php  
          include ($_SERVER['CONTEXT_DOCUMENT_ROOT']
          . "/submissions/submission06"
          . "/scripts/get_quote_from_mongodb.php");
          ?>
           </p></div>
      </div>
     </div>
     <script>
        //This script sets up the AJAX infrastructure for 
        //requesting time updates from the server.
        var request = null;
        function getCurrentTime()
        {
            request = new XMLHttpRequest();
            var url = "scripts/time.php";
            request.open("GET", url, true);
            request.onreadystatechange = updatePage;
            request.send(null);
        }
        function updatePage()
        {
            if (request.readyState == 4)
            {
                var dateDisplay = document.getElementById("datetime");
                dateDisplay.innerHTML = request.responseText;
            }
        }
        getCurrentTime();
        setInterval('getCurrentTime()', 60000)
      </script>
</body>