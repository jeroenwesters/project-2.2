<?php
// Made by Jarco - © 2019

// Load settings!
require 'include/layout/headersettings.php';
require 'include/functions.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->AddStyle("style/main.css");
$headerSettings->title = 'Succesfully registerd!';

require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">


    <?php

    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST))
    {
      $username = $_POST['username'];
      $password0 = $_POST['password0'];
      $password1 = $_POST['password1'];
      if(isset($_POST['admin'])){
        $admin = 1;
      }
      else{
        $admin = 0;
      }

      $result = createAccount($username, $password0, $password1, $admin);

      if($result->error){
        echo '<br><br><br>';
        echo '<h1>Failed!</h1>';
        echo '<p1>'.$result->message.'</p1>';

      }else{
        echo '<br><br><br>';
        echo '<h1>Registerd!</h1>';
        echo '<p1>Username: '.$username.'</p1>';
      }

    }

    echo '<div class="center-box" id="loading">';
    echo '<br><button class="button" onclick="goBack()">Go Back</button>';
    echo '<script>function goBack() { window.history.back(); }</script>';


    ?>

  </div>
</div>

<?php include 'include/layout/footer.php';  ?>
