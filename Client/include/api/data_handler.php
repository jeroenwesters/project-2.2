<?php
// Made by Jeroen - © 2019

include 'bin_reader.php';
include 'types.php';

/**
 * Gets specified data variable based on station id, date and time
 *
 * @param integer $station The station ID to use
 * @param integer $dataType Which datatype should be used
 * @param integer $year, $month, $day The date to use for the data
 * @param integer $hour, $minute, $second The timestamp to use for the data
 * @return Message Message class containg result (error, data, message)
 */
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


/**
 * Creates a file path to look for the data
 *
 * @param integer $year, $month, $day The date used to create the path
 * @param integer $hour, $minute, $second Timestamp used to find the file
 * @return string Array containg the result (error, data, message)
 */
function getFilePath($year, $month, $day, $hour, $minute, $second){
  $result = ['error' => true];

  $fileLocation = $year . '/'. $month . '/'. $day . '/'. ($hour-1) . '/'. $minute . '/';

  $file = 'measurement_' . $second . '.bin';
  //$url = realpath(dirname(__FILE__) . '/../../api/data/' . $fileLocation . $file);

  $url = realpath('/private/Measurements/' . $fileLocation . $file);
  //var_dump($url);

  if(file_exists($url)){
    $result['error'] = false;
    $result['data'] = $url;
    return $result;
  }else{
    $result['message'] = 'File not found!';
  }

  return $result;
}


/**
 * Gets data based from selected data and file
 *
 * @param integer $station The station ID to use
 * @param integer $dataIndex Position of the datatype within a station its data range
 * @param string $filePath The path containing the datafile
 * @return array Array containg error and data.
 */
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
