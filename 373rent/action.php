<?php
session_start();
require_once("connect.php");

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

if($action == "updateNeighborhood") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        $name = $_POST["name"];
        $maplink = $_POST["map"];
        $weblink = $_POST["site"];
        $desc = $_POST["desc"];
        $cover = $_POST["cover"];
        $cats = array();
        $photos = array();

        if(isset($_POST["cats"])) {
            for ($i = 0; $i < count($_POST["cats"]); $i++) {
                array_push($cats, mysqli_real_escape_string($conn, $_POST["cats"][$i]));
            }
        }

        if(isset($_POST["photos"])) {
            for ($i = 0; $i < count($_POST["photos"]); $i++) {
                $newPhoto = mysqli_real_escape_string($conn, $_POST["photos"][$i]);

                if($newPhoto != $cover) {
                    array_push($photos, $newPhoto);
                }
            }
        }

        if($name == "") $name = null;
        if($desc == "") $desc = null;
        if($maplink == "") $maplink = null;
        if($weblink == "") $weblink = null;
        if($cover == "") $cover = null;
        if($desc == "") $desc = null;

        $sql = "UPDATE extralist SET name=?, description=?, maplink=?, weblink=?, cover=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $desc, $maplink, $weblink, $cover, $_POST["id"]);
        $stmt->execute();

        //inserts
        for ($i = 0; $i < count($cats); $i++) {
            $sql = "SELECT * FROM categories WHERE id='" . $id . "' AND type='" . $cats[$i] . "'";
            $result = $conn->query($sql);

            if($result->num_rows == 0) {
                $sql = "INSERT INTO categories VALUES('" . $id . "','" . $cats[$i] . "')";
                $result = $conn->query($sql);
            }
        }
        for ($i = 0; $i < count($photos); $i++) {
            $sql = "SELECT * FROM extraphotos WHERE id='" . $id . "' AND src='" . $photos[$i] . "'";
            $result = $conn->query($sql);

            if($result->num_rows == 0) {
                $sql = "INSERT INTO extraphotos VALUES('" . $photos[$i] . "','" . $id . "')";
                $result = $conn->query($sql);
            }
        }

        //clears
        $sql = "SELECT * FROM categories WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $found = false;
                for ($i = 0; $i < count($cats); $i++) {
                    if($row["type"] == $cats[$i]) $found = true;
                }

                if(!$found) {
                    $sub_sql = "DELETE FROM categories WHERE id='" . $id . "' AND type='" . $row["type"] . "'";
                    $sub_res = $conn->query($sub_sql);
                }
            }
        }

        $sql = "SELECT * FROM extraphotos WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $found = false;
                for ($i = 0; $i < count($photos); $i++) {
                    if($row["src"] == $photos[$i]) $found = true;
                }

                if(!$found) {
                    $sub_sql = "DELETE FROM extraphotos WHERE id='" . $id . "' AND src='" . $row["src"] . "'";
                    $sub_res = $conn->query($sub_sql);
                }
            }
        }

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "updateApartment") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);
        $name = $_POST["name"];
        $tag = $_POST["tag"];
        $addr = $_POST["addr"];
        $price = $_POST["price"];
        $bed = $_POST["bed"];
        $bath = $_POST["bath"];
        $sqft = $_POST["sqft"];
        $avail = $_POST["avail"];
        $desc = $_POST["desc"];
        $cover = $_POST["cover"];
        $amens = array();
        $photos = array();

        if(isset($_POST["amens"])) {
            for ($i = 0; $i < count($_POST["amens"]); $i++) {
                array_push($amens, mysqli_real_escape_string($conn, $_POST["amens"][$i]));
            }
        }

        if(isset($_POST["photos"])) {
            for ($i = 0; $i < count($_POST["photos"]); $i++) {
                $newPhoto = mysqli_real_escape_string($conn, $_POST["photos"][$i]);

                if($newPhoto != $cover) {
                    array_push($photos, $newPhoto);
                }
            }
        }

        if($name == "") $name = null;
        if($tag == "") $tag = null;
        if($addr == "") $addr = null;
        if($price == "") $price = null;
        if($bed == "") $bed = null;
        if($bath == "") $bath = null;
        if($sqft == "") $sqft = null;
        if($avail == "") $avail = null;
        if($desc == "") $desc = null;
        if($cover == "") $cover = null;

        $sql = "UPDATE aptlist SET name=?, assoc=?, address=?, price=?, bedroom=?, bathroom=?, sqfoot=?, available=?, description=?, cover=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $name, $tag, $addr, $price, $bed, $bath, $sqft, $avail, $desc, $cover, $_POST["id"]);
        $stmt->execute();

        //inserts
        for ($i = 0; $i < count($amens); $i++) {
            $sql = "SELECT * FROM amenities WHERE id='" . $id . "' AND type='" . $amens[$i] . "'";
            $result = $conn->query($sql);

            if($result->num_rows == 0) {
                $sql = "INSERT INTO amenities VALUES('" . $id . "','" . $amens[$i] . "')";
                $result = $conn->query($sql);
            }
        }
        for ($i = 0; $i < count($photos); $i++) {
            $sql = "SELECT * FROM linkedphotos WHERE id='" . $id . "' AND src='" . $photos[$i] . "'";
            $result = $conn->query($sql);

            if($result->num_rows == 0) {
                $sql = "INSERT INTO linkedphotos VALUES('" . $photos[$i] . "','" . $id . "')";
                $result = $conn->query($sql);
            }
        }

        //clears
        $sql = "SELECT * FROM amenities WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $found = false;
                for ($i = 0; $i < count($amens); $i++) {
                    if($row["type"] == $amens[$i]) $found = true;
                }

                if(!$found) {
                    $sub_sql = "DELETE FROM amenities WHERE id='" . $id . "' AND type='" . $row["type"] . "'";
                    $sub_res = $conn->query($sub_sql);
                }
            }
        }

        $sql = "SELECT * FROM linkedphotos WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $found = false;
                for ($i = 0; $i < count($photos); $i++) {
                    if($row["src"] == $photos[$i]) $found = true;
                }

                if(!$found) {
                    $sub_sql = "DELETE FROM linkedphotos WHERE id='" . $id . "' AND src='" . $row["src"] . "'";
                    $sub_res = $conn->query($sub_sql);
                }
            }
        }

        echo "success";
    } else {
        echo "error";
    }

    exit();
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

if($action == "toggleMM") {
    if($connected) {
        if($_POST["toggle"] == "true") {
            $sql = "SELECT val FROM settings WHERE setting='maintenance'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $sql = "UPDATE settings SET val='true' WHERE setting='maintenance'";
                $result = $conn->query($sql);
            } else {
                $sql = "INSERT INTO settings VALUES('maintenance','true')";
                $result = $conn->query($sql);
            }
        } else {
            $sql = "SELECT val FROM settings WHERE setting='maintenance'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $sql = "UPDATE settings SET val='false' WHERE setting='maintenance'";
                $result = $conn->query($sql);
            } else {
                $sql = "INSERT INTO settings VALUES('maintenance','false')";
                $result = $conn->query($sql);
            }
        }

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

if($action == "createNeighborhood") {
    if($connected) {
        $fName = mysqli_real_escape_string($conn, $_POST["name"]);
        $genID = str_replace(" ", "", $fName);
        $genID = str_replace(".", "", $genID);
        $genID = str_replace(",", "", $genID);
        $genID = str_replace(":", "", $genID);
        $genID = str_replace(";", "", $genID);
        $genID = str_replace("/", "", $genID);
        $genID = str_replace("\\", "", $genID);
        $genID = str_replace("-", "", $genID);
        $genID = str_replace("_", "", $genID);
        $genID = str_replace("'", "", $genID);
        $genID = str_replace("\"", "", $genID);

        $sql = "SELECT id FROM extralist WHERE id='" . $genID . "'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            echo "entryexists";
            exit();
        }

        $sql = "INSERT INTO extralist(id, name) VALUES('" . $genID . "','" . $fName . "')";
        $result = $conn->query($sql);

        echo "success==" . $genID;
    } else {
        echo "error";
    }

    exit();
}

if($action == "createApartment") {
    if($connected) {
        $fName = mysqli_real_escape_string($conn, $_POST["name"]);
        $genID = str_replace(" ", "", $fName);
        $genID = str_replace(".", "", $genID);
        $genID = str_replace(",", "", $genID);
        $genID = str_replace(":", "", $genID);
        $genID = str_replace(";", "", $genID);
        $genID = str_replace("/", "", $genID);
        $genID = str_replace("\\", "", $genID);
        $genID = str_replace("-", "", $genID);
        $genID = str_replace("_", "", $genID);
        $genID = str_replace("'", "", $genID);
        $genID = str_replace("\"", "", $genID);

        $sql = "SELECT id FROM extralist WHERE id='" . $genID . "'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            echo "entryexists";
            exit();
        }

        $sql = "INSERT INTO aptlist(id, name) VALUES('" . $genID . "','" . $fName . "')";
        $result = $conn->query($sql);

        echo "success==" . $genID;
    } else {
        echo "error";
    }

    exit();
}

if($action == "uploadNeighborhoodPhoto") {
    if($connected) {
        $testNum = 0;

        while(isset($_FILES["image_" . $testNum])) {
            $fName = "assets/images/thingsToDo/" . $_FILES["image_" . $testNum]["name"];

            $src = mysqli_real_escape_string($conn, $fName);
            $id = mysqli_real_escape_string($conn, $_POST["id"]);


            $sql = "SELECT src FROM extraphotos WHERE src='" . $src . "' AND id='" . $id . "'";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                echo "fileexists";
                exit();
            }

            $sql = "SELECT cover FROM extralist WHERE cover='" . $src . "' AND id='" . $id . "'";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                echo "fileexists";
                exit();
            }

            if(!move_uploaded_file($_FILES["image_" . $testNum]["tmp_name"], $fName)) {
                echo "fileproblem";
                exit();
            }

            $testNum += 1;
        }

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "uploadApartmentPhoto") {
    if($connected) {
        $testNum = 0;

        while(isset($_FILES["image_" . $testNum])) {
            $fName = "assets/images/properties/" . $_FILES["image_" . $testNum]["name"];

            $src = mysqli_real_escape_string($conn, $fName);
            $id = mysqli_real_escape_string($conn, $_POST["id"]);


            $sql = "SELECT src FROM linkedphotos WHERE src='" . $src . "' AND id='" . $id . "'";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                echo "fileexists";
                exit();
            }

            $sql = "SELECT cover FROM aptlist WHERE cover='" . $src . "' AND id='" . $id . "'";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                echo "fileexists";
                exit();
            }

            if(!move_uploaded_file($_FILES["image_" . $testNum]["tmp_name"], $fName)) {
                echo "fileproblem";
                exit();
            }

            $testNum += 1;
        }

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

        $sql = "DELETE FROM extraphotos WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        $sql = "DELETE FROM categories WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        $sql = "DELETE FROM extralist WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        echo "success";
    } else {
        echo "error";
    }

    exit();
}

if($action == "removeApartment") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "DELETE FROM notify WHERE apt='" . $id . "'";
        $result = $conn->query($sql);

        $sql = "DELETE FROM linkedphotos WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        $sql = "DELETE FROM amenities WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        $sql = "DELETE FROM aptlist WHERE id='" . $id . "'";
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

if($action == "pullApartmentData") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "SELECT name, description, assoc, available, price, address, bedroom, bathroom, sqfoot FROM aptlist  WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            echo "apartmentName==" . $row["name"] . "<br>apartmentDesc==" . $row["description"] . "<br>apartmentTag==" . $row["assoc"] . "<br>apartmentPrice==" . $row["price"] . "<br>apartmentAddr==" . $row["address"] . "<br>apartmentBed==" . $row["bedroom"] . "<br>apartmentBath==" . $row["bathroom"] . "<br>apartmentSqft==" . $row["sqfoot"] . "<br>apartmentAvail==" . $row["available"];
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }

    exit();
}

if($action == "pullNeighborhoodPhotos") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "SELECT cover FROM extralist WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if(!is_null($row["cover"])) {
                echo "<div class=\"photoListEntry coverPhoto\">";
                echo "<img src=\"" . $row["cover"] . "\" onclick=\"setNeighborhoodCoverPhoto(this)\"/>";
                echo "<div class=\"static-remove-button \" onclick=\"removeNeighborhoodPhoto(this)\"></div>";
                echo "</div>";
            }
        }

        $sql = "SELECT src FROM extraphotos WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class=\"photoListEntry\">";
                echo "<img src=\"" . $row["src"] . "\" onclick=\"setNeighborhoodCoverPhoto(this)\"/>";
                echo "<div class=\"static-remove-button \" onclick=\"removeNeighborhoodPhoto(this)\"></div>";
                echo "</div>";
            }
        }
    } else {
        echo "error";
    }

    exit();
}

if($action == "pullApartmentPhotos") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "SELECT cover FROM aptlist WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if(!is_null($row["cover"])) {
                echo "<div class=\"photoListEntry coverPhoto\">";
                echo "<img src=\"" . $row["cover"] . "\" onclick=\"setApartmentCoverPhoto(this)\"/>";
                echo "<div class=\"static-remove-button \" onclick=\"removeApartmentPhoto(this)\"></div>";
                echo "</div>";
            }
        }

        $sql = "SELECT src FROM linkedphotos WHERE id='" . $id . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class=\"photoListEntry\">";
                echo "<img src=\"" . $row["src"] . "\" onclick=\"setApartmentCoverPhoto(this)\"/>";
                echo "<div class=\"static-remove-button \" onclick=\"removeApartmentPhoto(this)\"></div>";
                echo "</div>";
            }
        }
    } else {
        echo "error";
    }

    exit();
}

if($action == "pullNeighborhoodCategories") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "SELECT type FROM categories WHERE id='" . $id . "' ORDER BY type";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class=\"catListEntry\">";
                echo "<div class=\"static-move-button remove \" onclick=\"removeNeighborhoodCatRow(this)\"></div>";
                echo "<input class=\"subField\" placeholder=\"Enter Category...\" data-old=\"" . $row["type"] . "\" value=\"" . $row["type"] . "\" type=\"text\" list=\"catSuggestions\" />";
                echo "</div>";
            }
        }

        $sql = "SELECT distinct type FROM categories ORDER BY type";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<datalist id=\"catSuggestions\">";

            while($row = $result->fetch_assoc()) {
                echo "<option value=\"" . $row["type"] . "\">";
            }

            echo "</datalist>";
        }

    } else {
        echo "error";
    }

    exit();
}

if($action == "pullApartmentAmenities") {
    if($connected) {
        $id = mysqli_real_escape_string($conn, $_POST["id"]);

        $sql = "SELECT type FROM amenities WHERE id='" . $id . "' ORDER BY type";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class=\"amenListEntry\">";
                echo "<div class=\"static-move-button remove \" onclick=\"removeApartmentAmenRow(this)\"></div>";
                echo "<input class=\"subField\" placeholder=\"Enter Amenity...\" data-old=\"" . $row["type"] . "\" value=\"" . $row["type"] . "\" type=\"text\" list=\"amenSuggestions\" />";
                echo "</div>";
            }
        }

        $sql = "SELECT distinct type FROM amenities ORDER BY type";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<datalist id=\"amenSuggestions\">";

            while($row = $result->fetch_assoc()) {
                echo "<option value=\"" . $row["type"] . "\">";
            }

            echo "</datalist>";
        }

    } else {
        echo "error";
    }

    exit();
}

echo "invalidaction";
exit();

?>

