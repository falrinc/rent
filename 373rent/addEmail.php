<?php
require_once("connect.php");
if(!isset($_POST["email"]) || !isset($_POST["apt"])) {
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST["email"]);
$apt = mysqli_real_escape_string($conn, $_POST["apt"]);

if($connected && $email != "") {
    $sql = "SELECT * FROM notify WHERE email='" . $email . "' AND apt='" . $apt . "'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO notify VALUES('" . $email . "','" . $apt . "')";
        $result = $conn->query($sql);
    }
}
?>