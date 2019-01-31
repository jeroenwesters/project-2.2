<?php
$debug = true;

$databaseServer = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "unwdmi";

if($debug){
  $databaseServer = "localhost";
  $databaseUsername = "root";
  $databasePassword = "";
  $databaseName = "unwdmi";
}else{
  $databaseServer = "localhost";
  $databaseUsername = "oneitphp";
  $databasePassword = "Ybp44&Z8abHQBc2";
  $databaseName = "unwdmi";
}



$conn = connect(); // Makes connection to databas

// Returns all station (id's) from given country!
function retrieveStations($country){
  $query = "	SELECT stn, name, latitude, longitude, elevation
            FROM stations
            WHERE country = '$country'"; //put the sql query in a string


  $conn = connect();

  $result = getData($conn,$query); //put the result of the query in a variable


  return $result;
}

// Connects to the database
function connect(){
global $databaseServer, $databaseUsername, $databasePassword, $databaseName;
//connect to database
$conn = new mysqli($databaseServer, $databaseUsername, $databasePassword, $databaseName);
if($conn->connect_error) {
  return false;
}
return $conn;
}

//executes query, returns data AKA for SELECT statements
function getData($conn, $sql) {
  $result = $conn->query($sql);
  //execute the query
  $ret = Array();
  if($result === false) {
    //echo $conn->error;
    //if $resut is false, there's an error, return false
    $ret = false;
    return $ret;
  }
  if($result->num_rows > 0) {
    //if there are rows
    for($i = 0; $i < $result->num_rows; $i++) {
      //put them in $ret array
      $row = $result->fetch_assoc();
      //var_dump($row);
      array_push($ret, $row);
    }
  }
  $result->close();
  return $ret;
}



?>
