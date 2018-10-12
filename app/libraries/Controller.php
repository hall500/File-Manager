<?php
  /**
   * Base Controller Class
   * Loads Models and Views
   */

   class Controller {
     protected $layout = 'site';
     //Load Model
     protected function model($model){

      require_once '../app/models/' . ucwords($model) . '.php';

      return new $model();
     }

     protected function view($view, $data = []){
       extract($data);
       
       if(file_exists('../app/views/' . strtolower(get_called_class()) . '/' . $view . '.php')){
         $content = '../app/views/' . strtolower(get_called_class()) . '/' . $view . '.php';
         require_once '../app/views/_layout/' . $this->layout . '.php';
       }
       else {
         die("View does not exist");
       }
     }
   }