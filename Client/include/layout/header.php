<?php
// Made by Jeroen & Jarco - © 2019
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
    foreach ($headerSettings->styles as $value){
      echo '<link href="'.$value.'" rel="stylesheet">';
    }

    ?>
    <meta charset="utf-8">
    <title><?php echo $headerSettings->title; ?></title>
  </head>
  <body>
