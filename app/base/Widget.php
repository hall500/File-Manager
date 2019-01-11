<?php

  class Widget {
    static private $instance = NULL;

    private function __construct(){
    }

    static public function init(){
      if(self::$instance == null){
        self::$instance = new Widget;
      }
      return self::$instance;
    }

    public function run($widget = ''){
      $widget = ucfirst($widget);
      $widget_path = APP_ROOT . 'widgets/' . $widget . '.widget.php';
      if(file_exists($widget_path)){
        include $widget_path;
      }
    }

    public function getBody($widget = ''){
      
    }

    /* protected function setSettings($settings = null){
      if(!is_array($settings)){
        $this->_settings = $settings;
      }
    } */
  }