<?php
require_once("connect.php");

if(!isset($_POST["action"])) { exit(); }
$action = $_POST["action"];

if($action == "getMM") {
    if($connected) {
        $sql = "SELECT val FROM settings WHERE setting='maintenance'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if(is_null($row["val"])) {
                echo "false";
            } else {
                if($row["val"] == "true") {
                    echo "true";
                } else {
                    echo "false";
                }
            }
        } else {
            echo "false";
        }
    } else {
        echo "true";
    }

    exit();
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
                //no cover
                //echo "<img class=\"slide_" . $row["id"] . "\" src=\"" . $row["cover"] . "\" />";
                
                $sub_sql = "SELECT src FROM extraphotos WHERE id='" . $row["id"] . "'";
                $sub_result = $conn->query($sub_sql);

                if ($sub_result->num_rows > 0) {
                    while($sub_row = $sub_result->fetch_assoc()) {
                        echo "<img class=\"slide_" . $row["id"] . "\" src=\"" . $sub_row["src"] . "\" />";
                    }
                } else {
                    if(is_null($row["maplink"])) {
                        echo "<img class=\"slide_" . $row["id"] . "\" src=\"assets/images/noimage.png\" />";
                    }
                }

                if(!is_null($row["maplink"])) echo "<iframe class=\"slide_" . $row["id"] . "\" frameborder=\"0\" data-src=\"" . $row["maplink"] . "\"></iframe>";
                echo "<a class=\"prevSlide\" onclick=\"showSlides('_" . $row["id"] . "', -1)\">&#10094;</a>";
                echo "<a class=\"nextSlide\" onclick=\"showSlides('_" . $row["id"] . "', 1)\">&#10095;</a>";
                echo "</div>";
                echo "<h1>" . $row["name"] . "</h1>";
                echo "<p>" . $row["description"] . "</p>";
                if(!is_null($row["weblink"])) echo "<a class=\"web_button\" target=\"_blank\" href=\"" . $row["weblink"] . "\"><i class=\"fa fa-globe\"></i></a>";
                echo "<a class=\"close_button\" onclick=\"hideContent('_" . $row["id"] . "')\"><i class=\"fa fa-window-close\"></i></a>";
                echo "</div>";
            }
        }
    }

    exit();
}

if($action == "aptlist") {
    if($connected) {
        $sql = "SELECT id, name, available, cover, assoc FROM aptlist WHERE available IS NULL ORDER BY name DESC";
        $partApts = $conn->query($sql);

        if($partApts->num_rows > 0) {
            while($row = $partApts->fetch_assoc()) {
                $putDate = "nill";

                if(is_null($row["cover"])) {
                    echo "<div class=\"sImg\" style=\"background-image: url('assets/images/noimage.png')\" data-id=\"" . $row["id"] . "\" data-name=\"" . $row["name"] . "\" data-avail=\"" . $putDate . "\" data-assoc=\"" . $row["assoc"] . "\" ></div>";
                }
                else {
                    echo "<div class=\"sImg\" style=\"background-image: url('" . $row["cover"] ."')\" data-id=\"" . $row["id"] . "\" data-name=\"" . $row["name"] . "\" data-avail=\"" . $putDate . "\" data-assoc=\"" . $row["assoc"] . "\" ></div>";
                }
            }
        }

        $sql = "SELECT id, name, available, cover, assoc FROM aptlist WHERE available IS NOT NULL ORDER BY available DESC, name DESC";
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

                if(is_null($row["cover"])) {
                    echo "<div class=\"sImg\" style=\"background-image: url('assets/images/noimage.png')\" data-id=\"" . $row["id"] . "\" data-name=\"" . $row["name"] . "\" data-avail=\"" . $putDate . "\" data-assoc=\"" . $row["assoc"] . "\" ></div>";
                }
                else {
                    echo "<div class=\"sImg\" style=\"background-image: url('" . $row["cover"] ."')\" data-id=\"" . $row["id"] . "\" data-name=\"" . $row["name"] . "\" data-avail=\"" . $putDate . "\" data-assoc=\"" . $row["assoc"] . "\" ></div>";
                }
            }
        }
    }

    exit();
}

if($action == "dropdown") {
    if($connected) {
        $sql = "SELECT val FROM settings WHERE setting='maintenance'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if(!is_null($row["val"])) {
                if($row["val"] == "true") {
                    exit();
                }
            }
        }

        $sql = "SELECT id, name, available FROM aptlist WHERE available IS NOT NULL ORDER BY available ASC";
        $fullApts = $conn->query($sql);

        if($fullApts->num_rows > 0) {
            while($row = $fullApts->fetch_assoc()) {
                $putDate = "nill";
                if(!is_null($row["available"])) {
                    if(new DateTime($row["available"]) <= new DateTime()) {
                        $putDate = "Now";
                    }
                    else {
                        $phpdate = strtotime( $row["available"] );
                        $putDate = date( 'm-d-Y', $phpdate );
                    }
                }

                echo "<option id=\"" . $row["id"] . "\" value=\"" . $row["name"] . "\">" . $row["name"] . " (Available " . $putDate . ")</option>";
            }
        }

        $sql = "SELECT id, name, available FROM aptlist WHERE available IS NULL ORDER BY name ASC";
        $partApts = $conn->query($sql);

        if($partApts->num_rows > 0) {
            while($row = $partApts->fetch_assoc()) {
                echo "<option id=\"" . $row["id"] . "\" value=\"" . $row["name"] . "\">" . $row["name"] . " (Not Available)</option>";
            }
        }
    }

    exit();
}

exit();
?>