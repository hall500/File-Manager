<?php
  abstract class Widget {
    private $instance;

    private function __construct(){
    }

    public function init(){
      if(self::$instance == null){
        self::$instance = new get_called_class();
      }
      return self::$instance;
    }

    protected function run(){
      
    }

    protected function getBody(){

    }

    /* protected function setSettings($settings = null){
      if(!is_array($settings)){
        $this->_settings = $settings;
      }
    } */
  }