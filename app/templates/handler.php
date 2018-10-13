<?php
function read_this_file($path = '', $name = ''){
  $pathName = $path . '.php';
  if(file_exists($pathName)){
    $file = file_get_contents($pathName);
    return str_replace($path . "Name", $name, $file);
  }else{
    return false;
  }
}

function create_file($path = '', $name = '', $content = ''){
  $path = "../" . $path . "/" . $name . ".php";
  if(!(strpos($name, '/') === false)){
    $path_parts = pathinfo($path);
    $path = $path_parts['dirname'];
    if(!is_dir($path)){
      mkdir($path, 0700, true);
    }

    $path = $path . "/" . $path_parts['basename'];
  }

  if(!file_exists($path)){
    $file_handle = fopen($path, 'w+');
    fwrite($file_handle, $content);
    fclose($file_handle); 
    print $name . ".php Created Successfully\n";
  }else{
    print $name . ".php Already exists\n";
  }
}

if('cli' == php_sapi_name()){
    if($argc != 3){
      die('Incomplete parameters');
    }

    if(!isset($argv[2])){
      die("Please specify a file name to create");
    }

    $file_name = ucwords(strtolower($argv[2]));

    switch($argv[1]){
      case '-m':
      case '-model':
        $output = read_this_file('Model', $file_name);
        if($output != false){
          create_file('models', $file_name, $output);
        }else{
          print "Model Template File not Found\n";
        }
        break;
      case '-c':
      case '-controller':
        $output = read_this_file('Controller', $file_name);
        if($output != false){
          create_file('controllers', $file_name, $output);
        }else{
          print "Controller Template File not Found\n";
        }
        break;
      case '-v':
      case '-view':
        $output = read_this_file('View', $file_name);
        if($output != false){
          create_file('views', $file_name, $output);
        }else{
          print "View Template File not Found\n";
        }
        break;
      case '-w': 
      case '-widget': 
        $output = read_this_file('Widget', $file_name);
        if($output != false){
          create_file('widgets', $file_name, $output);
        }else{
          print "Widget Template File not Found\n";
        }
        break;
      case '-q':
      case '-quick': 
        $output = read_this_file('Controller', $file_name);
        if($output != false){
          create_file('controllers', $file_name, $output);

          $output = read_this_file('View', $file_name);
          if($output != false){
            create_file('views', strtolower($file_name) . "/index", $output);
          }else{
            print "View Template File not Found\n";
          }
        }else{
          print "Controller Template File not Found\n";
        }
        break;
      default:
        print "Invalid Option Specified\n";
        break;
    }

    exit();
  }

  echo "This page is being handled by CommandLine Input\n";
  exit();