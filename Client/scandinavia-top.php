<?php
// Made by Jeroen - © 2019

// Load settings!
require 'include/layout/headersettings.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->title = 'Scandinavia Top 10 precipitation';
$headerSettings->AddStyle("style/main.css");
// $headerSettings->AddStyle("style/style.css");

// Top 10 JS
$headerSettings->AddJs('js/top.js');
$headerSettings->AddJs('js/xmlparser.js');



require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Scaninavia Top 10 precipitation</h1>

  <div class="dateTime">
    <p id="currentDateTime">Measurement on: 01-01-2019 @ 00:00:00 a.m.</p>
  </div>

  <br/>
  <br/>
  <script>getTimeDate();</script>
  <script>setInterval(getTimeDate,1000);</script>

  <?php
  // Call script with api key
  echo   "<script>loadData('". $apikey ."')</script>"
 ?>


<!-- 2 decimals -->

  <table id="topten">
    <tr>
      <th>Index</th>
      <th>Country</th>
      <th>Station</th>
      <th>precipitation in CM</th>
    </tr>
  </table>

  <div class="center-box" id="loading">
    <div class="loader"></div>
  </div>

  <div class="center-box">
    <button id="export" hidden class='ripple' onclick="getXMLMeasurements()">Export to XML</button>
  </div>
</div>




<!-- End of content  -->
<?php include 'include/layout/footer.php';  ?>
