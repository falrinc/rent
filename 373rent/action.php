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
    if(!isset($_FILES["image"])) {
        echo "error";
        exit();
    }

    if($connected) {
        $fName = "assets/images/cover/" . $_FILES["image"]["name"];

        $src = mysqli_real_escape_string($conn, $fName);

        $sql = "SELECT * FROM coverphotos WHERE src='" . $src . "'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            echo "fileexists";
            exit();
        }

        if(!move_uploaded_file($_FILES["image"]["tmp_name"], $fName)) {
            echo "fileproblem";
            exit();
        }

        $sql = "SELECT id FROM coverphotos ORDER BY id";
        $result = $conn->query($sql);

        $openID = 0;
        $lastID = 0;

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $curID = $row["id"];
                if($curID > $lastID + 1) {
                    $openID = $lastID + 1;
                    break;
                }
                $lastID = $curID;
            }
        }

        if($openID == 0) {
            $openID = $lastID + 1;
        }

        $sql = "INSERT INTO coverphotos values(" . $openID . ",'" . $fName . "','')";
        $result = $conn->query($sql);

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "moveUpCover") {
    if($connected) {
        $src = mysqli_real_escape_string($conn, $_POST["src"]);

        $sql = "SELECT id FROM coverphotos WHERE src='" . $src . "'";
        $result = $conn->query($sql);

        $myID = 0;
        $curiousID = 1;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $myID = $row["id"];
        }

        if($myID <= 1) {
            echo "failure";
            exit();
        }

        $sql = "SELECT MAX(id) as id FROM coverphotos WHERE id < " . $myID;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $curiousID = $row["id"];
        }

        if($curiousID == $myID) {
            echo "failure";
            exit();
        }

        $sql = "SELECT src FROM coverphotos WHERE id=" . $curiousID;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $src2 = $row["src"];
            $sql = "UPDATE coverphotos SET id=" . $curiousID . " WHERE src='" . $src . "'";
            $result = $conn->query($sql);
            $sql = "UPDATE coverphotos SET id=" . $myID . " WHERE src='" . $src2 . "'";
            $result = $conn->query($sql);
        } else {
            $sql = "UPDATE coverphotos SET id=" . $curiousID . " WHERE src='" . $src . "'";
            $result = $conn->query($sql);
        }

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "moveDownCover") {
    if($connected) {
        $src = mysqli_real_escape_string($conn, $_POST["src"]);

        $sql = "SELECT id FROM coverphotos WHERE src='" . $src . "'";
        $result = $conn->query($sql);

        $myID = 0;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $myID = $row["id"];
        }

        $sql = "SELECT MAX(id) as id FROM coverphotos";
        $result = $conn->query($sql);

        $maxID = 0;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $maxID = $row["id"];
        }

        if($myID >= $maxID) {
            echo "failure";
            exit();
        }

        $curiousID = $maxID;

        $sql = "SELECT MIN(id) as id FROM coverphotos WHERE id > " . $myID;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $curiousID = $row["id"];
        }

        if($curiousID == $myID) {
            echo "failure";
            exit();
        }

        $sql = "SELECT src FROM coverphotos WHERE id=" . $curiousID;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $src2 = $row["src"];
            $sql = "UPDATE coverphotos SET id=" . $curiousID . " WHERE src='" . $src . "'";
            $result = $conn->query($sql);
            $sql = "UPDATE coverphotos SET id=" . $myID . " WHERE src='" . $src2 . "'";
            $result = $conn->query($sql);
        } else {
            $sql = "UPDATE coverphotos SET id=" . $curiousID . " WHERE src='" . $src . "'";
            $result = $conn->query($sql);
        }

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "removeCover") {
    if($connected) {
        $src = mysqli_real_escape_string($conn, $_POST["src"]);

        $sql = "DELETE FROM coverphotos WHERE src='" . $src . "'";
        $result = $conn->query($sql);

        $sql = "SELECT * FROM coverphotos WHERE src='" . $src . "'";
        $result = $conn->query($sql);

        if($result->num_rows == 0) {
            unlink(realpath($src));
        }

        $sql = "SELECT id, src FROM coverphotos ORDER BY id";
        $result = $conn->query($sql);

        $lowestOpen = 1;

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($row["id"] == $lowestOpen) {
                    $lowestOpen += 1;
                    continue;
                }

                $sql = "UPDATE coverphotos SET id=" . $lowestOpen . " WHERE src='" . $row["src"] . "'";
                $res = $conn->query($sql);
                $lowestOpen += 1;
            }
        }

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "removeWaitlist") {
    if($connected) {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "DELETE FROM notify WHERE email='" . $email . "' AND apt='" . $id . "'";
        $result = $conn->query($sql);

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "removeNeighborhood") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "DELETE FROM extralist WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "pullNeighborhoodData") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "SELECT name, weblink, maplink, description FROM extralist  WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            echo "neighborhoodName==" . $row["name"] . "<br>neighborhoodSite==" . $row["weblink"] . "<br>neighborhoodMap==" . $row["maplink"] . "<br>neighborhoodDesc==" . $row["description"];
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }

    exit();
}

echo "invalidaction";
exit();

?>

