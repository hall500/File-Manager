<?php
  class Widget {
    private $instance;

    private function __construct(){
      echo "Widget class call";
    }

    public function init(){
      if(self::$instance == null){
        self::$instance = new Widget();
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