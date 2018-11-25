<?php
  /**
   * Print out an error using defined layout
   */
  function error($data = ['title'=>'', 'description' => ''], $layout = 'site'){
    if(!empty($data)){
     extract($data);
    }
     $content = APP_ROOT . 'views/error/index.php';
     if(file_exists($content)){
       require_once APP_ROOT . 'views/_layout/'. $layout . '.php';
       exit();
     }else{
       die('An Error occurred');
     }  
  }   