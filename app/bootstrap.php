<?php
  error_reporting(1);

  /**
   * Entering PHP Command Line
   */

  if('cli' == php_sapi_name()){    
    chdir("../app/templates");
    $cmd = 'php handler.php ' . $argv[1] . ' ' . $argv[2];
    $command = escapeshellcmd($cmd);
    $output = shell_exec($command);
    print $output . "\n";
    exit();
  }

  //Load Config
  require_once 'config/config.php';

  require_once 'helpers/_autoload.php';
  
  //require_once 'packages/autoload.php';
  
  //Load Libraries
  spl_autoload_register(function($className){
    require_once 'libraries/' . $className . '.php';
  });

  //Instantiate the App
  App::instantiate();

