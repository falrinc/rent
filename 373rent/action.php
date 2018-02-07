<?php
session_start();

if(!isset($_SESSION["username"])) {
    echo "notloggedin";
    exit();
}

$time = $_SERVER['REQUEST_TIME'];

//10 minutes
if(isset($_SESSION["timeout"]) && ($time - $_SESSION["timeout"]) > 600) {
    session_unset();
    session_destroy();

    echo "timeout";
    exit();
}

$_SESSION["timeout"] = $time;

if(!isset($_POST["action"])) { 
    echo "noaction";
    exit();
}

$action = $_POST["action"];

$username = "root";
$password = "password";
$hostname = "localhost";
$dbname = "aptinfo";
$connected = TRUE;

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    $connected = FALSE;
}

if($action == "updateCoverCaption") {
    if($connected) {
        $src = mysqli_real_escape_string($conn, $_POST["src"]);
        $cap = mysqli_real_escape_string($conn, $_POST["caption"]);

        $sql = "UPDATE coverphotos SET caption='" . $cap . "' WHERE src='" . $src . "'";
        $result = $conn->query($sql);

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "uploadCover") {
    //do not allow duplicates
    
    $fName = $_FILES['image']['name'];
    echo "success";
    exit();
}

if($action == "moveUpCover") {
    echo "success";
    exit();
}

if($action == "moveDownCover") {
    echo "success";
    exit();
}

if($action == "removeCover") {
    echo "success";
    exit();
}

echo "invalidaction";
exit();

?>

