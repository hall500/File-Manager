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
      echo '  
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-3">'. (isset($title) ? $title : 'Error Page') .'</h1>
          <h4>'. (isset($description) ? $description : 'An error has definitely occurred') .'</h4>
          <p><a class="btn btn-primary btn-lg" href="./" role="button">Go Home &raquo;</a></p>
        </div>
      </div>
      ';
      exit();
    }else{
      die("An Error has Occurred");
    }
  }   