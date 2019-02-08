<?php
// Made by Jeroen, Emiel - © 2019

// Handles specific station/data requests
include '../include/api/data_handler.php';
include '../include/api/get_stations.php';
include '../include/functions.php';
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

/**
 * Verifies the API key.
 *
 * @param Message $msg The previous message
 */
function verifyKey($msg){

  // TODO: check if this key is in the database!
  if(isset($_GET['key'])){
    $apiKey = $_GET['key'];
    $userid = $_GET['userid'];

    if($apiKey == getApiKey($userid)){
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

/**
 * Filters the request types
 *
 * @param Message $msg The previous message
 */
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
