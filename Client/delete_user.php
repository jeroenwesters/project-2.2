<?php
  require "include/header.php";

  $userid = $_SESSION['userid'];
  $query = "DELETE from users WHERE `userid` = '$userid'";
  $result = doQuery($conn, $query);
  $sqlerror = $conn->error;
  if(strlen($sqlerror) < 1){
    echo "Deletion complete";
    header("Location: admin.php");
  }
  else{
    echo "An sql error has occured:";
    echo $sqlerror;
  }

 ?>
