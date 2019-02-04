<?php
// Made by Jeroen - © 2019

include 'bin_reader.php';
include 'types.php';

// Gets specified data variable based on station id, date and time
function getStationData($station, $dataType, $year, $month, $day, $hour, $minute, $second){
  $result = new Message();
  $dataIndex = '';


  // Convert type string to index
  $type = typeToIndex($dataType);


  // Check for error (exists or not!)
  if($type['error'] == false){
    $dataIndex = $type['data'];
  }else{
    $result->error = true;
    return $result;
  }

    $url = getFilePath($year, $month, $day, $hour, $minute, $second);

    if($url['error'] == false){
      // Search for data in given file
      $data = searchData($station, $dataIndex, $url['data']);
      // Check for error (no matching station)
      if($data['error'] == false){
        $result->error = false;
        $result->data = array('stn' => $station, 'value' => $data['data']);
        return $result;
      }
    }


  return $result;
}


// Creates file string for given time
function getFilePath($year, $month, $day, $hour, $minute, $second){
  $result = ['error' => true];

  $fileLocation = $year . '/'. $month . '/'. $day . '/'. $hour . '/'. $minute . '/';
  $file = 'measurement_' . $second . '.bin';
  $url = realpath(dirname(__FILE__) . '/../../api/data/' . $fileLocation . $file);


  if(file_exists($url)){
    $result['error'] = false;
    $result['data'] = $url;
    return $result;
  }else{
    $result['message'] = 'File not found!';
  }

  return $result;
}


// Searches for the data based on station and filepath
function searchData($station, $dataIndex, $filePath){
  $result = ['error' => true];

  // Returns the data if it found any
  $data = findInFile($station, $dataIndex, $filePath);

  if($data['error'] == false){
    $result['error'] = false;
    $result['data'] = $data['data'];
  }

  return $result;
}

?>
