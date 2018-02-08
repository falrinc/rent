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

    //20 minutes
    if(isset($_SESSION["timeout"]) && ($time - $_SESSION["timeout"]) > 1200) {
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
<script src="assets/js/admin.js?1000"></script>
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
        <script src="assets/js/cover.js?1000"></script>
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
                        <br>
                        <input type="file" accept="image/*" name="cover_file" id="uploadCover" style="display: none;" onchange="gotChange(this)" />
                        <input class="uploadButton" type="button" value="Browse..." onclick="document.getElementById('uploadCover').click();" />
                        <span class="uploadHeading">Upload New Cover: </span>
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
    </body>
    </html>
<?php
        exit();
    }

    if($page == "neighborhood") {
?>
        <script src="assets/js/neighborhood.js?1000"></script>
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
                        <div class="neighborhood-list">
<?php
                            if($connected) {
                                $sql = "SELECT id, name FROM extralist ORDER BY name";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<div class=\"neighborhood-entry\" onclick=\"neighborhoodSelect(this)\" data-id=\"" . $row["id"] . "\">" . $row["name"] . "</div>";
                                    }
                                }
                            }
?>
                        </div>
                        <br>
                        <input type="file" accept="image/*" name="cover_file" id="uploadCover" style="display: none;" onchange="gotChange(this)" />
                        <input class="uploadButton" type="button" value="Browse..." onclick="document.getElementById('uploadCover').click();" />
                        <span class="uploadHeading">Upload New Cover: </span>
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
    </body>
    </html>
<?php
        exit();
    }

    if($page == "waitlist") {
?>
        <script src="assets/js/waitlist.js?1000"></script>
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
           <div class="wait-list">
<?php
                if($connected) {
                    $sql = "SELECT email, apt, name FROM notify LEFT JOIN aptlist ON apt=id ORDER BY name";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<div class=\"wait-entry\" data-email=\"" . $row["email"] . "\" data-apt=\"" . $row["apt"] . "\" onclick=\"waitlistSelect(this)\">" . $row["email"] . "<span class=\"align-right\">" . $row["name"] . "</span></div>";
                        }
                    }
                }
?>
            </div>
        </div>
    </body>
    </html>
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