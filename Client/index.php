<?php
// Made by

// Load settings!
require 'include/layout/headersettings.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->AddStyle("style/main.css");
$headerSettings->title = 'Home';

$headerSettings->AddJs('js/xmlparser.js');

require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Welcome!</h1>
  <button class="ripple" onclick="getXMLMeasurements()">Click</button>
</div>

<?php include 'include/layout/footer.php';  ?>
