<!DOCTYPE html>
<html lang="en">
<head>
  <title>Map Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
<body>
   <div id="mapid"></div>
   <script>createMap('mapid')</script>


   <?php

   echo '<script>';

   for($y = 0; $y < 10; $y++){
     echo "createMarker(60, ". (17 + $y) . ", 'test', 5125);";

   }
   echo '</script>';

   ?>


</body>
</html>
