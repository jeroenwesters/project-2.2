<!doctype html> <!-- open html-->
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="header">
  <a href="webpage.php"></a>

  <p align="left">OneIT, project 2.2</p>
</div>
<?php

// require all necessary files
require "config.php";
require 'menu.php';
require 'session.php';
require 'right.php';

// check if the user is logged in
if($_SESSION['loggedIn'] != true){
  header("Location: login.php");//if the user is not logged in, redirect the user to the login page
}
 ?>

<div class="body"> 
