<?php
// Made by Jeroen & Emiel - © 2019

// Load settings!
require 'include/layout/headersettings.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->title = 'Ukraine real-time';
$headerSettings->AddStyle("style/main.css");
// $headerSettings->AddStyle("style/style.css");


require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Ukraine real-time map</h1>

  <br/>
  <br/>
  
</div>


<!-- End of content  -->
<?php include 'include/layout/footer.php';  ?>
