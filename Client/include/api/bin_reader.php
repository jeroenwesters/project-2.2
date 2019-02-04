<?php
// Made by Jeroen - © 2019

// Length of values inside the file
$offset = 4;
$amount_values = 18;    // Todo: test this value
$station_amount = 8000; // Todo: Match with java!
$format = "G";


// Reads bytes from given startindex
function readFiles($startIndex, $type){
  global $format, $offset;

  // Path to data
  $url = realpath(dirname(__FILE__) . '/../bin/measurementb.bin');

  // Open file
  $fp = fopen($url, 'r');


  // Read file from index
  fseek($fp, $startIndex + ($type * $offset));

  // get data
  $data = fgets($fp, $offset + 1);

  // Unpack / convert the data to floats
  $data = unpack ($format, $data);
  $round = round($data[1], 2);

  // Close file
  fclose($fp);

  return $round;
}



// Searches through the file
function findInFile($id, $dataType, $url){
  // Global values
  global $offset, $station_amount, $amount_values, $format;

  // Prepare result
  $result = ['error' => true, 'data' => ''];

  // Convert id to binary
  $d = pack($format, $id);

  // Open file
  $fp = fopen($url, 'r');

  if($fp == false){
    return $result;
  }

  // Now scan the file, search for bit index of station
  for($x = 0; $x < $station_amount; $x++){
    $stationIndex = ($x * $amount_values) * $offset;

    // Set pointer to station X
    fseek($fp, $stationIndex, SEEK_SET);

    // get data
    $data = fgets($fp, $offset + 1);


    // If we found our station
    if($data == $d){
      // Set pointer to variable position
      fseek($fp, $stationIndex + ($dataType * $offset));
      // Read the variable
      $var = fgets($fp, $offset + 1);
      // Unpack / convert the data to floats
      $var = unpack ($format, $var);
      // Round number on 2 decimals
      $round = round($var[1], 2);



      // Add to result
      $result['error'] = false;
      $result['data'] = $round;

      break;

    }
  }

  // Close file
  fclose($fp);

  return $result;
}

// Gets binary code
function getBinary($startIndex){
  global $format, $offset;

  // Path to data
  $url = realpath(dirname(__FILE__) . '/../bin/measurementb.bin');

  // Open file
  $fp = fopen($url, 'r');

  // Read file from index
  fseek($fp, $startIndex);

  // get data
  $data = fgets($fp, $offset + 1);

  // Close file
  fclose($fp);

  return array('binary' => $data, 'index' => $startIndex);
}


function urlBuilder($date){
  global $defaultUrl;

  $url = $date['year'] . '/' . $date['month'] . '/' . $date['day'] . '/' . $date['hour'] . '/' . $date['minute'] . '/';
  $file = 'measurement_' . $date['second'] . '.bin';

  return $defaultUrl . $url . $file;
}


// Searches through the file
function getValuesFromFile($station_id, $dataTypes, $url){
  // Global values
  global $offset, $station_amount, $amount_values, $format;

  // Prepare result
  $result = ['error' => true, 'data' => ''];

  // Convert id to binary
  $s = pack($format, $station_id);

  // Open file
  $fp = fopen($url, 'r');

  if($fp == false){
    return $result;
  }

  // Now scan the file, search for bit index of station
  for($x = 0; $x < $station_amount; $x++){
    $stationIndex = ($x * $amount_values) * $offset;

    // Set pointer to station X
    fseek($fp, $stationIndex, SEEK_SET);

    // get data
    $data = fgets($fp, $offset + 1);


    // If we found our station
    if($data == $s){
      $allData = [];

      // loop through all requested datatypes
      for($d = 0; $d < sizeof($dataTypes); $d++){
        //echo $dataTypes[$d] . '<br>';
        // Set pointer to variable position
        fseek($fp, $stationIndex + ($dataTypes[$d] * $offset));
        // Read the variable
        $var = fgets($fp, $offset + 1);
        // Unpack / convert the data to floats
        $var = unpack ($format, $var);
        // Round number on 2 decimals
        $allData[$dataTypes[$d]] = round($var[1], 2);


      }

      // Add to result
      $result['error'] = false;
      $result['data'] = $allData;

      break;

    }
  }

  // Close file
  fclose($fp);
  //header('Content-Type: application/json');
  echo json_encode($result);
  return $result;
}










// Debug values
$reqTypes = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
$defaultUrl = 'C:/xampp/htdocs/sites/WeatherStation new/api/data/';
$date = ["year" => 2019, "month" => 1, "day" => 30, "hour"=> 14, "minute"=>0, "second"=> 0];
$url = urlBuilder($date);


// Gets all variables from file
function getAll($stns, $dataType, $url){
  // Global values
  global $offset, $station_amount, $amount_values, $format;

  // Prepare result
  $result = ['error' => true, 'data' => ''];

  // Convert id to binary
  $s = pack($format, $station_id);

  // Open file
  $fp = fopen($url, 'r');

  if($fp == false){
    return $result;
  }

  // Now scan the file, search for bit index of station
  for($x = 0; $x < $station_amount; $x++){
    $stationIndex = ($x * $amount_values) * $offset;

    // Set pointer to station X
    fseek($fp, $stationIndex, SEEK_SET);

    // get data
    $data = fgets($fp, $offset + 1);


    // If we found our station
    if($data == $s){
      $allData = [];

      // loop through all requested datatypes
      for($d = 0; $d < sizeof($dataTypes); $d++){
        //echo $dataTypes[$d] . '<br>';
        // Set pointer to variable position
        fseek($fp, $stationIndex + ($dataTypes[$d] * $offset));
        // Read the variable
        $var = fgets($fp, $offset + 1);
        // Unpack / convert the data to floats
        $var = unpack ($format, $var);
        // Round number on 2 decimals
        $allData[$dataTypes[$d]] = round($var[1], 2);


      }

      // Add to result
      $result['error'] = false;
      $result['data'] = $allData;

      break;

    }
  }

  // Close file
  fclose($fp);
  //header('Content-Type: application/json');
  echo json_encode($result);
  return $result;
}



?>
