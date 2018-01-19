<?php

if(!isset($_POST["action"])) { exit(); }
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

if($action == "coverphotos") {
    if($connected) {
        $sql = "SELECT id, src, caption FROM coverphotos ORDER BY id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li class=\"bg-dark\" style=\"background-image:url('" . $row["src"] . "')\">";
                echo "<div class=\"container\">";
                echo "<div class=\"image-caption\">";
                echo "<div class=\"font-alt caption-text\">";
                if(!is_null($row["caption"])) echo $row["caption"];
                echo "</div></div></div></li>";
            }
        }
    }
    exit();
}

if($action == "categories") {
    if($connected) {
        $sql = "SELECT DISTINCT type FROM categories ORDER BY type";
        $result = $conn->query($sql);
        $num = 0.1;

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            $catShort = str_replace(" ", "", $row["type"]);
            echo "<li><a class=\"wow fadeInUp\" href=\"#\" data-filter=\"." . $catShort . "\" data-wow-delay=\"" . $num . "s\">" . $row["type"] . "</a></li>";
            $num += 0.1;
            }
        }
    }

    exit();
}

if($action == "gridthings") {
    if($connected) {
        $sql = "SELECT id, name, description, maplink, weblink, cover FROM extralist ORDER BY name";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sub_sql = "SELECT type FROM categories WHERE id='" . $row["id"] . "' ORDER BY type";
                $sub_res = $conn->query($sub_sql);

                $disp = "<li class=\"work-item";

                if($sub_res->num_rows > 0) {
                    while($sub_row = $sub_res->fetch_assoc()) {
                        $sub_cat = str_replace(" ", "", $sub_row["type"]);
                        $disp = $disp . " " . $sub_cat;
                    }
                }

                echo $disp . "\"><a onclick=\"showContent('_" . $row["id"] . "')\">";
                echo "<div class=\"work-image\"><img src=\"" . $row["cover"] . "\" alt=\"Attraction\"/></div>";
                echo "<div class=\"work-caption font-alt\">";
                echo "<h3 class=\"work-title\">" . $row["name"] . "</h3>";
                //echo "<div class=\"work-descr\">Something</div>";
                echo "</div></a></li>";
            }
        }
    }
    exit();
}

if($action == "thingstodo") {
    if($connected) {
        $sql = "SELECT id, name, description, maplink, weblink, cover FROM extralist ORDER BY name";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class=\"cb content_" . $row["id"] . "\">";
                echo "<div class=\"sb slideBox_" . $row["id"] . "\">";
                echo "<img class=\"slide_" . $row["id"] . "\" src=\"" . $row["cover"] . "\" />";
                //extra photos
                echo "<iframe class=\"slide_" . $row["id"] . "\" frameborder=\"0\" data-src=\"" . $row["maplink"] . "\"></iframe>";
                echo "<a class=\"prevSlide\" onclick=\"showSlides('_" . $row["id"] . "', -1)\">&#10094;</a>";
                echo "<a class=\"nextSlide\" onclick=\"showSlides('_" . $row["id"] . "', 1)\">&#10095;</a>";
                echo "</div>";
                echo "<h1>" . $row["name"] . "</h1>";
                echo "<p>" . $row["description"] . "</p>";
                echo "<a class=\"web_button\" target=\"_blank\" href=\"" . $row["weblink"] . "\"><i class=\"fa fa-globe\"></i></a>";
                echo "<a class=\"close_button\" onclick=\"hideContent()\"><i class=\"fa fa-window-close\"></i></a>";
                echo "</div>";
            }
        }
    }

    exit();
}

if($action == "aptlist") {
    if($connected) {
        $sql = "SELECT id, name, available, cover FROM aptlist WHERE available IS NULL ORDER BY name DESC";
        $partApts = $conn->query($sql);

        if($partApts->num_rows > 0) {
            while($row = $partApts->fetch_assoc()) {
                $putDate = "nill";

                echo "<div class=\"sImg\" style=\"background-image: url('" . $row["cover"] ."')\" data-id=\"" . $row["id"] . "\" data-name=\"" . $row["name"] . "\" data-avail=\"" . $putDate . "\" ></div>";
            }
        }

        $sql = "SELECT id, name, available, cover FROM aptlist WHERE available IS NOT NULL ORDER BY available DESC";
        $fullApts = $conn->query($sql);

        if($fullApts->num_rows > 0) {
            while($row = $fullApts->fetch_assoc()) {
                $putDate = "nill";
                if(!is_null($row["available"])) {
                    if(new DateTime($row["available"]) <= new DateTime()) {
                        $putDate = "now";
                    }
                    else {
                        $phpdate = strtotime( $row["available"] );
                        $putDate = date( 'm-d-Y', $phpdate );
                    }
                }

                echo "<div class=\"sImg\" style=\"background-image: url('" . $row["cover"] ."')\" data-id=\"" . $row["id"] . "\" data-name=\"" . $row["name"] . "\" data-avail=\"" . $putDate . "\" ></div>";
            }
        }
    }

    exit();
}

exit();
?>