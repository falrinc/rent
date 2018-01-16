<!DOCTYPE html>
<html lang="en-US" dir="ltr">
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <link rel="icon" type="image/png" sizes="192x192"  href="assets/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/favicons/manifest.json">
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
    <link id="color-scheme" href="assets/css/colors/default.css" rel="stylesheet">
  </head>
  <body data-spy="scroll" data-target=".onpage-navigation" data-offset="60" onload="loadScript()">
    <main>
      <div class="page-loader">
        <div class="loader">Loading...</div>
      </div>
      <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#custom-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="index.php">373-RENT</a>
          </div>
          <div class="collapse navbar-collapse" id="custom-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#home">Home</a>
              </li>
              <li class="dropdown"><a href="#rentals">Rental Units</a>
              </li>
              <li><a href="#thingsToDo">Things To Do</a>
              </li>
              <li><a href="contact.php" style="color:white">Request a Tour</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

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
	  
	  <!-- Start of home page-->
	  <!-- Home section -->
      <section class="home-section home-full-height photography-page" id="home">
        <div class="hero-slider">
          <ul class="slides">

          <?php
          if($connected) {
            $sql = "SELECT src, caption FROM coverphotos ORDER BY id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo "<li class=\"bg-dark\" style=\"background-image:url('" . $row["src"] . "');\">";
                echo "<div class=\"container\">";
                echo "<div class=\"image-caption\">";
                echo "<div class=\"font-alt caption-text\">";
                if(!is_null($row["caption"])) echo $row["caption"];
                echo "</div></div></div></li>";
              }
            }
          }
          ?>

          </ul>
        </div>
      </section>
      <div class="main">
	  
       <!-- Start of Available Rentals -->
        <section class="module pb-0" id="rentals">
          <div class="container">
		        <h2 class="module-title font-alt">Rental Units</h2>
          </div>

          <?php
          if($connected) {
            $sql = "SELECT id, name, available, price, cover, bedroom, bathroom, sqfoot FROM aptlist ORDER BY price ASC";
            $fullApts = $conn->query($sql);
            $sql = "SELECT DISTINCT type FROM amenities ORDER BY type";
            $amens = $conn->query($sql);

          ?>

          <div class="aptFilters" id="aptFilters">
            <div class="checkRow lrg">
              <div class="checkBox unselectable" id="AvailableNow" onclick="toggleChecked('AvailableNow')"></div>
              <p>Available Now</p>
            </div>
            <?php
            if ($amens->num_rows > 0) {
              while($row = $amens->fetch_assoc()) {
                $amenShort = str_replace(" ", "", $row["type"]);
                echo "<div class=\"checkRow sml\">";
                echo "<div class=\"checkBox unselectable\" id=\"" . $amenShort . "\" onclick=\"toggleChecked('" . $amenShort . "')\"></div>";
                echo "<p>" . $row["type"] . "</p>";
                echo "</div>";
              }
            }
            ?>
          </div>
          <div class="aptList" id="aptList">
            <?php
            if($fullApts->num_rows > 0) {
              while($row = $fullApts->fetch_assoc()) {
                echo "<a href=\"rental.php?id=" . $row["id"] . "\">";
                echo "<div class=\"listBox";

                $classList = "";

                if(!is_null($row["available"])) {
                  if(new DateTime($row["available"]) <= new DateTime()) {
                    $classList = $classList . " AvailableNow";
                  }
                }

                $innerSQL = "SELECT type FROM amenities WHERE id='" . $row["id"] . "'";
                $sub_res = $conn->query($innerSQL);

                if($sub_res->num_rows > 0) {
                  while($sub_row = $sub_res->fetch_assoc()) {
                    $catShort = str_replace(" ", "", $sub_row["type"]);
                    $classList = $classList . " " . $catShort;
                  }
                }

                echo $classList . "\">";
                echo "<img src=\"" . $row["cover"] . "\" />";
                echo "<div class=\"textContain\">";
                echo "<p>" . $row["name"];

                if(!is_null($row["price"])) echo " (<i><b>$" . $row["price"] . "</b> / Mo</i>)";

                echo "</p>";
                echo "<p><b>" . $row["bedroom"] . "</b> BEDROOM / <b>" . $row["bathroom"] . "</b> BATHROOM</p>";

                if(!is_null($row["sqfoot"])) echo "<p><b>" . $row["sqfoot"] . "</b> SQ FEET</p>";

                echo "</div>";
                echo "<div class=\"overlay\"></div>";
                echo "</div></a>";
              }
            }
            ?>
          </div>
          <script>
            document.getElementById('aptList').style.height = document.getElementById('aptFilters').offsetHeight + "px";
          </script>
          <?php
          }
          ?>
        </section>
		
		<div style="height:500px"></div>
        <section class="module pb-0" id="thingsToDo">
      <h2 class="module-title font-alt" >Food & Entertainment in the Area</h2>
        <div class="rwi">
          <div class="container">
            <div class="row">
              <div class="col-sm-12" >
                <ul class="filter font-alt" id="filters" >
                  <li><a class="current wow fadeInUp" href="#" data-filter="*">All</a></li>

                  <?php
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
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <?php
            if($connected) {
              $sql = "SELECT id, name, description, maplink, weblink, cover FROM extralist ORDER BY name";
              $result = $conn->query($sql);

              echo "<ul class=\"works-grid works-hover-w works-grid-4\" id=\"works-grid\">";

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

                $result->data_seek(0);
                echo "</ul></div>";

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
            else {
              echo "</div>";
            }
          ?>
        
        </section>
        <script>

        curSlide = 0;

        function reloadAptlist() {
          anyChecked = false;

          $(".listBox").animate({opacity: 0}, 500);

          setTimeout(function(){
            $(".checkBox").each(function() {
              if($(this).data("checked")) anyChecked = true;
            });

            if(!anyChecked) {
              bgswitch = true;

              $(".listBox").each(function() {
                $(this).css("display", "block");
                if(!$(this).hasClass("AvailableNow")) {
                  $(this).find(".overlay").css("background", "rgba(255,255,255,0.8)");
                } else {
                  if(bgswitch) {
                    $(this).css("background", "rgb(220,220,220)");
                    bgswitch = false;
                  } else {
                    $(this).css("background", "rgb(195,195,195)");
                    bgswitch = true;
                  }
                }
              });
            } else {
              bgswitch = true;

              $(".listBox").each(function() {
                matchedAll = true;
                pElem = $(this);

                $(".checkBox").each(function() {
                  if($(this).data("checked") && !pElem.hasClass($(this).attr("id"))) matchedAll = false;
                });

                if(matchedAll) {
                  $(this).css("display", "block");
                  if(!$(this).hasClass("AvailableNow")) {
                    $(this).find(".overlay").css("background", "rgba(255,255,255,0.8)");
                  } else {
                    if(bgswitch) {
                      $(this).css("background", "rgb(220,220,220)");
                      bgswitch = false;
                    } else {
                      $(this).css("background", "rgb(195,195,195)");
                      bgswitch = true;
                    }
                  }
                } else {
                  $(this).css("display", "none");
                }
              });
            }
          }, 500);

          $(".listBox").animate({opacity: 1}, 500);
        }

        function toggleChecked(checkName) {
          sid = ("#" + checkName).replace(".", "\\.");
          
          if($(sid).data("checked")) {
            $(sid).data("checked", "");
            $(sid).html("");
          } else {
            $(sid).data("checked", "1");
            $(sid).html("X");
          }

          reloadAptlist();
        }

        function showSlides(ext, off) {
          if(off == 0) {
            curSlide = 0;
          }
          else {
            curSlide += off;
          }

          size = $(".slide" + ext).length;
          //var slides = document.getElementsByClassName("slide" + ext);

          if(curSlide < 0) curSlide = size - 1;
          if(curSlide >= size) curSlide = 0;
          //if(curSlide < 0) curSlide = slides.length - 1;
          //if(curSlide >= slides.length) curSlide = 0;

          $(".slide" + ext).each(function(i) {
            if(i == curSlide) {
              $(this).css("display", "block");
              if($(this).data('src')) {
                $(this).prop('src', $(this).data('src'))
                $(this).data('src', false);
              }
            }
            else $(this).css("display", "none");
          });

          /*
          for (i = 0; i < slides.length; i++) {
            if(i == curSlide) {
              slides[i].style.display = "block";
            }
            else slides[i].style.display = "none";
          }
          */
        }

        function showContent(cid) {
          $(".rwi").animate({opacity: 0}, 500, function() {
            $(".rwi").hide();
            $(".content" + String(cid)).show();
            $(".content" + String(cid)).animate({opacity: 1.0}, 500, function() {});
          });
          showSlides(cid, 0);
        }
        function hideContent() {
          $(".cb").animate({opacity: 0}, 500, function() {
            $(".cb").hide();
            $(".rwi").show();
            $(".rwi").animate({opacity: 1.0}, 500, function() {});
          });
        }

        function loadScript() {
          reloadAptlist();
        }
        </script>
		
        <hr class="divider-d">
        <footer class="footer bg-dark">
          <div class="container">
            <div class="row">
              <div class="col-sm-6">
                <a class="copyright font-alt" href="tel:13523737368">(352)373-7368</a>
              </div>
              <div class="col-sm-6">
                <!--<div class="footer-social-links"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-dribbble"></i></a><a href="#"><i class="fa fa-skype"></i></a>
                </div>-->
              </div>
            </div>
          </div>
        </footer>
        <div class="scroll-up"><a href="#totop"><i class="fa fa-angle-double-up"></i></a></div>
      </div>
    </main>
    <!--  
    JavaScripts
    =============================================
    -->
    <script src="assets/lib/jquery/dist/jquery.js"></script>
    <script src="assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/lib/wow/dist/wow.js"></script>
    <script src="assets/lib/jquery.mb.ytplayer/dist/jquery.mb.YTPlayer.js"></script>
    <script src="assets/lib/isotope/dist/isotope.pkgd.js"></script>
    <script src="assets/lib/imagesloaded/imagesloaded.pkgd.js"></script>
    <script src="assets/lib/flexslider/jquery.flexslider.js"></script>
    <script src="assets/lib/owl.carousel/dist/owl.carousel.min.js"></script>
    <script src="assets/lib/smoothscroll.js"></script>
    <script src="assets/lib/magnific-popup/dist/jquery.magnific-popup.js"></script>
    <script src="assets/lib/simple-text-rotator/jquery.simple-text-rotator.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
  </body>
</html>