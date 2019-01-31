<!doctype html> <!-- open html-->
<?php
  // require all necessary files
  require "functions.php";
  // require 'menu.php';
  require 'session.php';
  // require 'right.php';

 ?>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
  <ul>
    <li><a id="a01"href='webpage.php'><img src='pictures/vectorpaint2.svg' width="73px"></a></li>
    <!-- <li><a href='webpage.php'>Home</a></li> -->
    <li><a href='view1.php'>View 1</a></li>
    <li><a href='view2.php'>View 2</a></li>
    <li><a href='view3.php'>View 3</a></li>
    <li style="float:right"><a class="active" href="logout.php">Logout</a></li>
    <li style="float:right"><a href="account.php">Account</a></li>
    <?php
      if($_SESSION['admin'] == 1){ //only display the admin panel when the user has admin rights
        echo "<li style='float:right'><a href='admin.php'>Admin Panel</a></li>";
      }
     ?>
  </ul>
</body>

<?php
// check if the user is logged in
if($_SESSION['loggedIn'] != true){
  header("Location: login.php");//if the user is not logged in, redirect the user to the login page
}
 ?>

<div class="body">
