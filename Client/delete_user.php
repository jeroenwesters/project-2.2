<?php
  require "include/header.php";

  $userid = $_SESSION['userid'];

  $result = deleteAccount($userid);
  // If failed
  if($result->error){
    echo $result->message . "   ";
    echo "<button onclick='history.go(-2);'>Go back</button>";
    echo "<button onclick='history.go(-1);'>Try again</button>";
  }else{
    echo $result->message . "   ";
    echo "<button onclick='history.go(-2);'>Back </button>";
  }

  // $query = "DELETE from users WHERE `userid` = '$userid'";
  // $result = doQuery($conn, $query);
  // $sqlerror = $conn->error;
  // if(strlen($sqlerror) < 1){
  //   echo "Deletion complete";
  //   header("Location: admin.php");
  // }
  // else{
  //   echo "An sql error has occured:";
  //   echo $sqlerror;
  //   echo "<br><a href='admin.php'>Back to the admin panel";
  //
  // }

 ?>
