<?php
  //Load Config
  require_once 'config/db.php';
  require_once 'config/config.php';

  //Load Libraries
  spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
  });

  Core::instantiate();
  //Core::debug(get_required_files());