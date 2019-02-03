<?php
// Made by Jeroen & Emiel - © 2019

// Load settings!
require 'include/layout/headersettings.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->title = 'Overview Scandinavia';
$headerSettings->AddStyle("style/main.css");
// $headerSettings->AddStyle("style/style.css");



// Map styles
$mapStyle = '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
crossorigin=""/>';
$headerSettings->AddStyle($mapStyle, true);

// Map JS
$headerSettings->AddJs('js/mapcontroller.js');
$mapJs = '<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
crossorigin=""></script>';
$headerSettings->AddJs($mapJs, true);


require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Scandinavia Overview</h1>
  <br/>
  <br/>
 <div id="mapid"></div>
 <script>createMap('mapid')</script>
</div>

 <?php
 // Test
 echo '<script>';
 
 for($y = 0; $y < 10; $y++){
   echo "createMarker(60, ". (17 + $y) . ", 'test', 5125);";

 }
 echo '</script>';
?>



<!-- End of content  -->
<?php include 'include/layout/footer.php';  ?>
