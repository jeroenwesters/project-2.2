<?php

// Handles specific station/data requests
include '../include/api/data_handler.php';
include '../include/api/get_stations.php';
include '../include/message.php';
include 'datarequest.php';
include 'stationinfo.php';

// Calls the API if get is used.
$msg = new Message();

// Verify if $_GET is set / used
if($_GET){
  verifyKey($msg);
}else{
  $msg->message = 'Use $_GET to get acces to the API!';
  $msg->toJson();
}

// Verifies the api key!
function verifyKey($msg){

  // TODO: check if this key is in the database!
  if(isset($_GET['key'])){
    $apiKey = $_GET['key'];

    if($apiKey == 'asf756saf5asf75a7s6f'){
        // Fillter on datatype
        filterRequest($msg);
    }else{
      $msg->message = 'Invalid key!';
      $msg->toJson();
    }
  }else{
    $msg->message = 'Key not set!';
    $msg->toJson();
  }
}

function filterRequest($msg){
  if(isset($_GET['type'])){
    $type = $_GET['type'];

    // Verify data type
    if($type == 'data'){
      handleDataRequest($msg);
    }else if($type == 'multiple'){
      handleMultipleData($msg);
    }else if($type == 'station'){
      handleStationsRequest($msg);
    }else{
      $msg->message = 'Requested type not existing!';
      $msg->toJson();
    }
  }else{
    $msg->message = 'Type not set!';
    $msg->toJson();
  }
}

?>
