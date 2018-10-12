<?php
  //Load Config
  require_once 'config/config.php';

  require_once 'helpers/autoload.php';

  //Load Libraries
  spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
  });

  //Instantiate the Core
  Core::instantiate();