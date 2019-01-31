<!doctype html> <!-- open html-->
<?php
  // require all necessary files
  require "functions.php";
  // require 'menu.php';
  require 'session.php';
  // require 'right.php';

  // check if the user is logged in
  if($_SESSION['loggedIn'] != true){
    header("Location: login.php");//if the user is not logged in, redirect the user to the login page
  }
   ?>

 ?>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="style/style.css">

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
  integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
  crossorigin=""/>
  <link rel="stylesheet" href="style/map.css">
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
  integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
  crossorigin=""></script>
  <script src="js/mapcontroller.js"></script>
  </head>




</head>
<body>
  <ul>
    <li><a id="a01"href='webpage.php'><img src='pictures/vectorpaint2.svg' width="73px"></a></li>
    <!-- <li><a href='webpage.php'>Home</a></li> -->
    <li><a href='view1.php'>View 1</a></li>
    <li><a href='view2.php'>Scandinavia snowfall</a></li>
    <li><a href='view3.php'>View 3</a></li>
    <li style="float:right"><a class="active" href="logout.php">Logout</a></li>
    <li style="float:right"><a href="account.php">Account</a></li>
    <?php
      if($_SESSION['admin'] == 1){ //only display the admin panel when the user has admin rights
        echo "<li style='float:right'><a href='admin.php'>Admin Panel</a></li>";
      }
     ?>
  </ul>

  <div class="body">

   <div id="mapid"></div>
   <script>createMap('mapid')</script>

   <?php

   echo '<script>';

   for($y = 0; $y < 10; $y++){
     echo "createMarker(60, ". (17 + $y) . ", 'test', 5125);";

   }
   echo '</script>';

    require 'include/footer.php';

?>
