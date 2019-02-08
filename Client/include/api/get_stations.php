<?php
// Made by Jeroen - © 2019

/**
 * Function to retrieve al ID's from the given country
 *
 * @param string $country Country to use
 */
function retrieveStations($country){
  $msg = new Message();
  $country = strtolower($country);

  $file = 'stations.csv';
  $url = realpath(dirname(__FILE__) . '/../../api/data/' . $file);

  if(file_exists($url)){
    // Parse CSV to array!
    $csv = array_map('str_getcsv', file($url));

    if($csv){
      // Loop through all stations
      // 0 = station ID, 1 = name, 2 = country, 3 = latitude, 4 = longitude, 5 = elevator
      $stations = array();

      for($i = 0; $i < count($csv); $i++){
        if(strtolower($csv[$i][2]) == $country){
          $stations[] = array("stn" => $csv[$i][0], "name" => $csv[$i][1], "latitude" => $csv[$i][3], "longitude" => $csv[$i][4], "elevation" => $csv[$i][5]);
        }
      }

      if($stations){
        $msg->data = $stations;
        $msg->error = false;
      }else{
        $msg->message = 'Error occured. Please contact the administrator!';
      }

    }else{
      $msg->message = 'Error occured. Please contact the administrator!';
    }

  }else{
    $msg->message = 'File not found!';
    $msg->error = true;
  }


  return $msg;

}


?>
