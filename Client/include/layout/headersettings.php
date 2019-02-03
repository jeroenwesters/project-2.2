<?php
// Made by Jeroen - © 2019


// Header settings
class HeaderSettings {
  public $title = 'Weather Application';
  public $styles = array();
  public $js = array();
  public $redirect = true;

  function AddStyle($styleURL, $global = false){
    if(!$global){
      $this->styles[] = "http://" . $_SERVER['SERVER_NAME'] .'/'. $styleURL;
    }

  }
}


?>
