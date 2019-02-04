<?php
// Made by Jeroen & Emiel - © 2019

// Load settings!
require 'include/layout/headersettings.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->title = 'Scandinavia Top 10 precipitation';
$headerSettings->AddStyle("style/main.css");
// $headerSettings->AddStyle("style/style.css");

// Top 10 JS
$headerSettings->AddJs('js/top.js');



require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Scaninavia Top 10 precipitation</h1>

  <div class="dateTime">
    <p>Measurement on:</p>
    <p id="currentDate">Date: 01-01-2019</p>
    <p id="currentTime">Time: 00:00:00</p>
  </div>

  <br/>
  <br/>
  <script>setInterval(getTimeDate, 1000);</script>
  <script>loadData('asf756saf5asf75a7s6f')</script>



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

</div>




<!-- End of content  -->
<?php include 'include/layout/footer.php';  ?>
