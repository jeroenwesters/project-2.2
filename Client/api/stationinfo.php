<?php
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
