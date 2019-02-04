<?php
// Made by Jeroen - © 2019

$type_conversion = [
  "STN" => 0,
  "YEAR" => 1,
  "MONTH" => 2,
  "DAY" => 3,
  "HOUR" => 4,
  "MINUTE" => 5,
  "SECOND" => 6,
  "TEMP" => 7,
  "DEWP" => 8,
  "STP" => 9,
  "SLP" => 10,
  "VISIB" => 11,
  "WDSP" => 12,
  "PRCP" => 13,
  "SNDP" => 14,
  "FRSHTT" => 15,
  "CLDC" => 16,
  "WNDDIR" => 17,
];

// Returns id based on giving type (string)
function typeToIndex($type){
  $result = ['error' => true, 'data' => ''];

  // Get global var
  global $type_conversion;

  // Check if this string in in array
  if(in_array($type, $type_conversion)){
    $type = strtoupper($type);

    if(array_key_exists($type, $type_conversion)){
      // Get id of the requested type
      $type_id = $type_conversion[$type];

      $result['error'] = false;
      $result['data'] = $type_id;
    }
  }

  return $result;
}


?>
