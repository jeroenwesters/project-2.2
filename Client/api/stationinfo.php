<?php
// Made by Jeroen - © 2019

/**
 * Handles request to receive stations from the given country
 *
 * @param Message $msg The previous message
 */
function handleStationsRequest($msg){
  if(isset($_GET["country"])){
    $country = $_GET["country"];

    $info = retrieveStations($country);

    if($info){
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
