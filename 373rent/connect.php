<?php
$username = "root";
$password = "password";
$hostname = "localhost";
$dbname = "aptinfo";

$connected = TRUE;

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    $connected = FALSE;
}
?>

