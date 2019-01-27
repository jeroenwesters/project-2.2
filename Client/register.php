<?php
  require "include/header.php";
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
    $error = "";
    if(strlen($password0) < 5){
      $error = "The password should have at least 5 characters.";
    }
    if($password0 != $password1){
      $error = "The passwords do not match.";
    }
    if(strlen($error) > 0){
      echo $error;
    }
    else{
      $query = "INSERT INTO `users` (`userid`, `username`, `password`, `admin`)
              VALUES (NULL, '$username', '$password0', '$admin')";
      $result = doQuery($conn, $query);
      $sqlerror = $conn->error;
      if(strlen($sqlerror) < 1){
        echo "Registration complete";
      }
      else if(strtok($sqlerror, " ") == "Duplicate"){
        echo "This username has already been taken";
      }
      else{
        echo "An sql error has occured:";
        echo $sqlerror;
      }
    }
  }
  echo "<br><a href='admin.php'>Back to the admin panel";
 ?>
