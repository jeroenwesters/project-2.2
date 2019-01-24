<?php
//connects to the database
function connect(){
global $databaseServer, $databaseUsername, $databasePassword, $databaseName;
//connect to database
$conn = new mysqli($databaseServer, $databaseUsername, $databasePassword, $databaseName);
if($conn->connect_error) {
  return false;
}
return $conn;
}

//escapes and trim a string so it doesn't cause problems
function escapeTrimString($conn, $str) {
  //trim
  $str2 = trim($str);
  //escape sql chars
  $str2 = $conn->real_escape_string($str2);
  //replace ; with \;
  $str2 = str_replace(";", "\\;", $str2);
  return $str2;
}

//takes a string and return it wrapped in single-quotes
//return "NULL" if string is empty
function emptyToNull($str) {
  if($str == "") {
    return "NULL";
    //if empty string, return NULL, else reurn string wrapped in '...'
  } else {
    return "'$str'";
  }
}

//return true or false, depending on if string is ""
//beacuse empty() also return true if string is "0"
function checkEmpty($str) {
  if($str == "") {
    //if empty string, return true, else false
    return true;
  }
  return false;
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

//executes query, returns no data AKA for everything else
function doQuery($conn, $sql) {
  $result = $conn->query($sql);
  //execute the query
  if($result === true) {
    //if it worked, return true
    return true;
  } else {
    //else, return false
    //echo $conn->error;
    return false;
  }
}
 ?>
