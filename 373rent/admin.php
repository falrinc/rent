<?php
session_start();

if(!isset($_POST["action"])) {
?>
<html lang="en-US" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--  
    Document Title
    =============================================
    -->
    <title>373-RENT</title>
    <!--  
    Favicons
    =============================================
    -->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/images/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--  
    Stylesheets
    =============================================
    
    -->
    <!-- Default stylesheets-->
    <link href="assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template specific stylesheets-->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Volkhov:400i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="assets/lib/animate.css/animate.css" rel="stylesheet">
    <link href="assets/lib/components-font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/lib/et-line-font/et-line-font.css" rel="stylesheet">
    <link href="assets/lib/flexslider/flexslider.css" rel="stylesheet">
    <link href="assets/lib/owl.carousel/dist/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/lib/owl.carousel/dist/assets/owl.theme.default.min.css" rel="stylesheet">
    <link href="assets/lib/magnific-popup/dist/magnific-popup.css" rel="stylesheet">
    <link href="assets/lib/simple-text-rotator/simpletextrotator.css" rel="stylesheet">
    <!-- Main stylesheet and color file-->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
    <link id="color-scheme" href="assets/css/colors/default.css" rel="stylesheet">
  </head>

  <body onload="loaded()">
    <script src="assets/lib/jquery/dist/jquery.js"></script>
      <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#custom-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
          </div>
          <div class="collapse navbar-collapse" id="custom-collapse">
          </div>
        </div>
      </nav>
      <br><br><br>
<?php
    if(!isset($_SESSION["username"])) {
?>
    <center><h1>Invalid login information.</h1>
    <h2><a href="login.html"><u>Back</u></a></h2></center>
    </body>
    </html>
<?php
        exit();
    }

    $time = $_SERVER['REQUEST_TIME'];

    //10 minutes
    if(isset($_SESSION["timeout"]) && ($time - $_SESSION["timeout"]) > 600) {
        session_unset();
        session_destroy();
?>

    <center><h1>You have timed out due to inactivity.<br>Please login again.</h1>
    <h2><a href="login.html"><u>Back</u></a></h2></center>
    </body>
    </html>

<?php
        exit();
    }

    $_SESSION["timeout"] = $time;

    $page = "default";

    if(isset($_GET["page"])) {
        $page = $_GET["page"];
    }

?>
<button class="floating-logout" type="button" onclick="logout()">Logout</button>
<script>
function loaded() {
    $(".cover-caption").bind("input propertychange", function() { coverChange(); });
    $(".cover-caption").on('keyup', function (e) {
        if (e.keyCode == 13) {
            if(!$(".cover-button").attr("disabled")) {
                coverUpdate();
            }
        }
    });
}

function handleResult(result) {
    if(result == "notloggedin") {
        window.location = "login.html";
        return false;
    }

    if(result == "timeout") {
        window.location = "admin.php";
        return false;
    }

    if(result == "noaction") {
        return false;
    }

    if(result == "error") {
        return false;
    }

    return true;
}

function logout() {
    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "admin.php",
        data     : {action: "logout"},
        success  : function(data) {
            window.location = "login.html";
        },
        error: function (xhr, ajaxOptions, thrownError) {
            window.location = "login.html";
        }
    });
}

function coverUpdate() {
    imgSrc = $(".altSelected").html();
    imgCap = $(".cover-caption").val();

    $.ajax({
        type     : "POST",
        cache    : false,
        url      : "action.php",
        data     : {action: "updateCoverCaption",
                    src: imgSrc,
                    caption: imgCap},
        success  : function(data) {
            if(handleResult(data)) {
                element = null;

                $(".cover-entry").each(function() {
                    if($(this).html() == imgSrc) {
                        element = $(this);
                    }
                });

                element.data("caption", imgCap);
                coverSelect(element);
            }
        }
    });
}

function coverSelect(element) {
    $(".cover-entry").removeClass("altSelected");
    $(element).addClass("altSelected");
    imgSrc = $(element).html();
    imgCap = $(element).data("caption");

    $(".cover-preview").html("<img src=\"" + imgSrc + "\" style=\"width:100%;height:100%\" />");
    $(".cover-caption").val(imgCap);
    $(".cover-caption").data("old", imgCap);
    $(".cover-caption").removeAttr("disabled");
    $(".cover-button").attr("disabled", "disabled");
    $(".cover-button").addClass("disabledButton");

    if($("#coverUpArrow").length == 0) {
        $(".cover-list").append("<div class=\"floating-move-button\" id=\"coverUpArrow\" onclick=\"moveUpSelected()\"></div>");
    }
    if($("#coverDownArrow").length == 0) {
        $(".cover-list").append("<div class=\"floating-move-button\" id=\"coverDownArrow\" onclick=\"moveDownSelected()\"></div>");
    }
    if($("#coverRemove").length == 0) {
        $(".cover-list").append("<div class=\"floating-move-button\" id=\"coverRemove\" onclick=\"removeSelectedCover()\"></div>");
    }

    $("#coverUpArrow").css("left", $(element).position().left + $(element).width() + $("#coverUpArrow").width() - 2);
    $("#coverUpArrow").css("top", $(element).position().top);
    $("#coverDownArrow").css("left", $(element).position().left + $(element).width() + $("#coverDownArrow").width() - 2);
    $("#coverDownArrow").css("top", $(element).position().top + $("#coverDownArrow").height() + 1);
    $("#coverRemove").css("left", $(element).position().left + - $("#coverDownArrow").width() - 2);
    $("#coverRemove").css("top", $(element).position().top + ($("#coverDownArrow").height() / 2) + 1);
}

function coverChange() {
    if($(".cover-caption").val() == $(".cover-caption").data("old")) {
        $(".cover-button").attr("disabled", "disabled");
        $(".cover-button").addClass("disabledButton");
    } else {
        $(".cover-button").removeAttr("disabled");
        $(".cover-button").removeClass("disabledButton");
    }
}
</script>
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

    if($page == "default") {
?>
        <center><h1 class="large-heading">Administration Menu</h1></center>
        <table class="main-menu-table">
        <tr>
            <td><a href="admin.php?page=cover">Cover Photos</a></td>
        </tr>
        <tr>
            <td><a href="admin.php?page=apartments">Apartments</a></td>
        </tr>
        <tr>
            <td><a href="admin.php?page=neighborhood">Neighborhood Attractions</a></td>
        </tr>
        <tr>
            <td><a href="admin.php?page=waitlist">Waitlist</a></td>
        </tr>
        </body></html>
<?php
        exit();
    }

    if($page == "cover") {
?>
        <table class="nav-table">
            <tr>
                <td><a href="admin.php?page=cover"><u>Cover Photos</u></a></td>
                <td><a href="admin.php?page=apartments"><u>Apartments</u></a></td>
                <td><a href="admin.php?page=neighborhood"><u>Neighborhood Attractions</u></a></td>
                <td><a href="admin.php?page=waitlist"><u>Waitlist</u></a></td>
            </tr>
        </table>
        <br>
        <div class="menu-box">
            <table class="two-col">
                <tr>
                    <td>
                        <div class="cover-list">
<?php
                            if($connected) {
                                $sql = "SELECT src, caption FROM coverphotos ORDER BY id";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {

                                        echo "<div class=\"cover-entry\" onclick=\"coverSelect(this)\" data-caption=\"";
                                        if(!is_null($row["caption"])) echo $row["caption"];
                                        echo "\">" . $row["src"] . "</div>";
                                    }
                                }
                            }
?>
                        </div>
                    </td>
                    <td>
                        <div class="cover-preview">
                            Select a cover image from the left
                        </div>
                        <input class="cover-caption" type="text" data-old="" placeholder="No Caption" value="" disabled />
                        <button class="cover-button disabledButton" type="button" onclick="coverUpdate()" disabled>Update</button>
                    </td>
                </tr>
            </table>
        </div>
<?php
        exit();
    }

?>
    <center><h1>You have requested an unknown page.</h1>
    <h2><a href="admin.php"><u>Back</u></a></h2></center>
    </body>
    </html>
<?php

    exit();
}
$action = $_POST["action"];

if($action == "checksession") {
    if(isset($_SESSION["username"])) {
        echo "true";
    } else {
        echo "false";
    }
}

if($action == "logout") {
    session_unset();
    session_destroy();
    echo "true";
}

if($action == "login") {
    if(isset($_POST["username"]) && isset($_POST["password"])) {
        $username = "root";
        $password = "password";
        $hostname = "localhost";
        $dbname = "aptinfo";
        $connected = TRUE;

        $conn = new mysqli($hostname, $username, $password, $dbname);

        if ($conn->connect_error) {
            $connected = FALSE;
        }

        if($connected) {
            $uname = mysqli_real_escape_string($conn, $_POST["username"]);
            $pword = mysqli_real_escape_string($conn, $_POST["password"]);

            $sql = "SELECT * FROM admin WHERE username='" . $uname . "' and password='" . $pword . "'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $_SESSION["username"] = $uname;
                echo "true";
            } else {
                echo "false";
            }
        }
        else {
            echo "false";
        }
    } else {
        echo "false";
    }
}

?>