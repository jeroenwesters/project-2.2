<?php
// Made by Jeroen - © 2019

// Message object to return
class Message {
    public $error = true;
    public $message = '';
    public $data = '';

    function __construct($error = true, $message = '', $data = null) {
      $this->error = $error;
      $this->message = $message;
      $this->data = $data;
    }

    // Echo's data in json
    function toJson() {
        header('Content-Type: application/json');
        $arr = array('error' => $this->error, 'message' => $this->message, 'data' => $this->data);
        echo json_encode($arr);
    }

    // Returns data in json format
    function getJson(){
      $arr = array('error' => $this->error, 'message' => $this->message, 'data' => $this->data);
      return json_encode($arr);
    }

    function getArray(){
      $arr = array('error' => $this->error, 'message' => $this->message, 'data' => $this->data);
      return $arr;
    }
}


?>
