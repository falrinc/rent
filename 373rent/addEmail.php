<?php
if(!isset($_POST["email"]) || !isset($_POST["apt"])) {
    exit();
}

$username = "root";
$password = "password";
$hostname = "localhost";
$dbname = "aptinfo";
$connected = TRUE;

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    $connected = FALSE;
}

$email = mysqli_real_escape_string($conn, $_POST["email"]);
$apt = mysqli_real_escape_string($conn, $_POST["apt"]);

if($connected && $email != "") {
    $sql = "INSERT INTO notify VALUES('" . $email . "','" . $apt . "')";
    $result = $conn->query($sql);
}
?>