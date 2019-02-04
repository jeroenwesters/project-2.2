<?php
// Made by Jeroen - © 2019

function handleDataRequest($msg){
  if(isset($_GET["stn"]) && isset($_GET["var"]) && isset($_GET["date"]) && isset($_GET["time"])){
    $stn = $_GET["stn"];
    $date = $_GET["date"];
    $time = $_GET["time"];
    $var = $_GET["var"];

    $date = explode('-', $date);
    $time = explode(':', $time);


    // Get station data based on year-month-day-hour-minute-second
    $info = getStationData($stn, $var, $date[2], $date[1], $date[0], $time[0], $time[1], $time[2]);

    if($info->error == false){
      $msg->error = false;
      $msg->data = $info->data;
      $msg->toJson();
    }else{
      $msg->message = 'Couldn\'t find station or data';
      $msg->toJson();
    }
  }else{
    $msg->message = 'Parameters aren´t complete!';
    $msg->toJson();
  }
}


function handleMultipleData($msg){
  if(isset($_GET["var"]) && isset($_GET["date"]) && isset($_GET["time"])){
    $date = $_GET["date"];
    $time = $_GET["time"];
    $var = $_GET["var"];

    $date = explode('-', $date);
    $time = explode(':', $time);

    $var = explode('|', $var);
    var_dump($var);

    return;

    // Get station data based on year-month-day-hour-minute-second
    $info = getStationData($stn, $var, $date[2], $date[1], $date[0], $time[0], $time[1], $time[2]);

    if($info->error == false){
      $msg->error = false;
      $msg->data = $info->data;
      $msg->toJson();
    }else{
      $msg->message = 'Couldn\'t find station or data';
      $msg->toJson();
    }
  }else{
    $msg->message = 'Parameters aren´t complete!';
    $msg->toJson();
  }
}
?>
