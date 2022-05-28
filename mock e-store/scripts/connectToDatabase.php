<?php
/*connectToDatabase.php
Handles the connection to the Lynn Mountain Meadows database.
Values for $dbLocation, $dbUsername, $dbPassword, and
$dbName are assigned in the file sql.inc
*/

include("/home/course/u16/htpasswd/sql.inc");

if (!isset($dbLocation))
{
    echo "Database location is missing.<br>
          Connection script now terminating.";
    exit(0);
}

if (!isset($username))
{
    echo "Database username is missing.<br>
          Connection script now terminating.";
    exit(0);
}

if (!isset($password))
{
    echo "Database password is missing.<br>
          Connection script now terminating.";
    exit(0);
}

if (!isset($dbname))
{
    echo "Database name is missing.<br>
          Connection script now terminating.";
    exit(0);
}

$db = mysqli_connect($dbLocation,
                     $username,
                     $password,
                     $dbname);
if (mysqli_connect_errno() || ($db == null))
{
    printf("Database connection failed: %s<br>
           Connection script now terminating.",
           mysqli_connect_error());
    exit(0);
}
?>