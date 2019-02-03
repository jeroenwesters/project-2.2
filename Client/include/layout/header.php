<?php
// Made by Jeroen & Jarco - © 2019
$companyName = 'DEOL partners';

session_start();

include_once 'headersettings.php';

if(isset($headerSettings)){
}else{
  $headerSettings = new HeaderSettings();
}

$loggedIn = false;
if(isset($_SESSION['loggedIn'])){
  $loggedIn = true;
}else{
  if($headerSettings->redirect){
    header("Location:Login.php");
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php
    foreach ($headerSettings->styles as $css){
      echo '<link href="'.$css.'" rel="stylesheet">';
    }

    foreach ($headerSettings->externalCss as $ecss){
      echo $ecss;
    }

    foreach ($headerSettings->js as $js){
      echo '<script src="'.$js.'" crossorigin=""></script>';
    }

    foreach ($headerSettings->externalJs as $ejs){
      echo $ejs;
    }

    ?>
    <meta charset="utf-8">
    <title><?php echo $headerSettings->title . ' - ' . $companyName; ?></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500" rel="stylesheet">
  </head>
  <body>
    <div class="wrapper">
