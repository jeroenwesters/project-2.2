<?php
// Made by Jeroen & Emiel - © 2019

// Load settings!
require 'include/layout/headersettings.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->title = 'Scandinavia Top 10 precipitation';
$headerSettings->AddStyle("style/main.css");
// $headerSettings->AddStyle("style/style.css");




require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Scandinavia Top 10 precipitation</h1>
  <br/>
  <br/>




<!-- 2 decimals -->
  <table id="topten">
    <tr>
      <th>Index</th>
      <th>Country</th>
      <th>Station</th>
      <th>precipitation</th>
    </tr>
    <?php

    for($x = 0; $x < 10; $x++){
      echo "<tr>";
      echo "<td>". ($x + 1) . "</td>";
      echo "<td>Germany</td>";
      echo "<td>Frankfurt</td>";
      echo "<td>53.15</td>";
    }

    ?>

  </table>










</div>




<!-- End of content  -->
<?php include 'include/layout/footer.php';  ?>
