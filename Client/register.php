<?php

  if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST))
  {
    $username = $_POST['username'];
    $password0 = $_POST['password0'];
    $password1 = $_POST['password1'];
    if(isset($POST['admin'])){
      $admin = $_POST['admin'];
    }
  }
require "admin.php"

 ?>
