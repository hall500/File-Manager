<?php
  /**
   * Entering PHP Command Line
   */
  if('cli' == php_sapi_name()){
    exit();
  }
  
  //Parse in Configuration file
  $config = parse_ini_file('config.ini');
  extract($config);
  /**
   * APPLICATION SETTINGS
   */
  //App Root
  define('APP_ROOT', dirname(dirname(__FILE__)) . '/');

  //App Version
  define('APP_VERSION', $version);

  //Setting up base folder location
  $this_file = str_replace('\\', '/', __File__) ;

  $doc_root = $_SERVER['DOCUMENT_ROOT'];

  $web_root =  str_replace(array($doc_root, "app/config/config.php") , '' , $this_file);

  $server_root = str_replace ('app/config/config.php' ,'', $this_file);

  define('URL_ROOT' , $web_root);
  
  define('SERVER_ROOT' , $server_root);

  //Site name
  define('APP_NAME', $app);

  /**
   * DATABASE SETTINGS
   */
  //Database Server Name
  define('DB_HOST',  $host);

  //Database Name
  define('DB_NAME',  $database);

  //Database User Name
  define('DB_USER',  $user);

  //Database Password
  define('DB_PASS',  $password);
  
  unset($config);