<?php
  session_start(); //start a new session

  //reset all session variables
  unset($_SESSION['loggedIn']);
  unset($_SESSION['username']);
  unset($_SESSION['admin']);

  //redirect user to the login page
  header("Location: login.php");
?>
