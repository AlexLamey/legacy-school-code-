<?php
session_start();

$secure = $_SESSION['SECURE'];
if($secure != "!@#$^%FDSSFDWQR@") {die('SECURE test failed!');}

$origin = $_SESSION['ORIGIN'];
if($origin != "/~u16/submissions/submission06/pages/formFeedback.php") 
{die('ORIGIN test failed!');}

$salutation = $firstName = $lastName = "";
$email = $phoneNumber = "";
$subject = $message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $salutation = sanitized_input($_POST["salutation"]);
    $firstName = sanitized_input($_POST["firstName"]);
    if(!preg_match("/^[A-Z][A-Za-z -]*$/", $firstName)) {
    die("Bad first name!");
    }
    $lastName = sanitized_input($_POST["lastName"]);
    if(!preg_match("/^[A-Z][A-Za-z -]*$/", $lastName)) {
      die("Bad last name!");
    }

    $email = sanitized_input($_POST["email"]);
    if(!preg_match("/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})$/", $email)) {
        die("Bad email!");
    }
    
    $phoneNumber = sanitized_input($_POST["phone"]);
    $phoneNumber = !empty($_POST['phone']) ? $_POST['phone'] : "Not given";
    if (!empty($_POST["phone"]) && 
    !preg_match("/^(\d{3}-)?\d{3}-\d{4}$/", $phoneNumber))
    {
      die("Bad phone number!");
    }
    $subject = sanitized_input($_POST["subject"]);
    $message = sanitized_input($_POST["message"]);
}

function sanitized_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    
}

$messageToBusiness = 
    "From: $salutation $firstName $lastName\r\n". 
    "E-mail address: $email \r\n". 
    "Phone number: $phoneNumber \r\n". 
    "_  _   _   _   _   _   _   _   _\r\n". 
    "Subject: $subject \r\n". 
    "_  _   _   _   _   _   _   _   _\r\n".
    "$message\r\n". 
    "_  _   _   _   _   _   _   _   _\r\n";
    
$headerToBusiness = "From: $email\r\n";
mail("u50@mail.cs.smu.ca", $subject,
 $messageToBusiness, $headerToBusiness);

 $messageToClient = 
 "Dear $salutation $lastName: \r\n".
 "Lynn Mountain Meadows has recieved the following message: \r\n". 
 "_  _   _   _   _   _   _   _   _\r\n". 
 $messageToBusiness.
 "We appreciate your feedback and look forward to reading it \r\n"; 

 if(isset($_POST['reply'])) $messageToClient .= 
 "P.S. We will send you an email shortly with additional information.\r\n";

 $headerToClient = "From: u16@mail.cs.ssmu.ca";
 mail($email, "Re: $subject", $messageToClient, $headerToClient);

 $display = str_replace("\r\n", "\r\n<br>", $messageToClient);
 $display = "
 <!DOCTYPE html>
 <html lang = 'en'>
 <head><meta charset='utf-8'><title>Your message</title>
 </head><body><code>$display</code></body>
 </html>";
 echo $display;

$fileVar = fopen("../data/feedback.txt", "a") 
or die ("Error: could not open the log file. :(");
fwrite($fileVar, 
"\n _   _   _   _   _   _   _   _   _   _   _   _   _\n")
or die ("Error: could write divider into the log file. :(");
fwrite($fileVar, "Date recieved: ".date("jS \of F, Y \a\\t H:i:s\n"))
or die ("Error: could write date into the log file. :(");
fwrite($fileVar, $messageToBusiness)
or die ("Error: could write message into the log file. :(");
?>