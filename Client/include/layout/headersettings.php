<?php
// Made by Jeroen - © 2019


// Header settings
class HeaderSettings {
  public $title = 'Weather Application';
  public $styles = array();
  public $externalCss = array();
  public $externalJs = array();
  public $js = array();
  public $redirect = true;

  // Add CSS links
  function AddStyle($styleURL, $external = false){

    if(!$external){
      $this->styles[] = $styleURL;
      //$this->styles[] = $this->GetType() . $_SERVER['SERVER_NAME'] .'/'. $styleURL;
    }else{
      $this->externalCss[] = $styleURL;
    }
  }

  // Add javascript links
  function AddJs($jsURL, $external = false){
    if(!$external){
      $this->js[] = $this->GetType() . $_SERVER['SERVER_NAME'] .'/'. $jsURL;
    }else{
      $this->externalJs[] = $jsURL;
    }
  }

  function GetType(){
    $type = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
    return $type;
  }
}


?>
