<?php
  /**
   * Base Controller Class
   * Loads Models and Views
   */
   class Controller {
     /**
      * Defines the layout of the site to use
      * @var $layout defines the application layout to use
      */
     protected $layout = 'site';

     /**
      * Load a new model class
      * @param String $model Gets the required model class
      * @return Model new $model() returns the called model classs
      */
     protected function model($model = ''){
      //Checks if $model is set 
      if(!empty($model) && is_string($model)){
        $model_path = APP_ROOT . 'models/' . ucwords($model) . '.php';
        if(file_exists($model_path)){
          require_once $model_path;
          return new $model();
        }

        error([
          'title' => 'Model Not Found',
          'description' => 'Ops! It seems the model <strong>' . ucwords($model) . '</strong> does not exist<br>Please Create It first'
        ]);
      }
     }

     /**
      * Sets the corresponding view
      * @param String $view Sets the view to display
      * @param Array $data Parameters passed onto the page for display
      */
     protected function view($view = null, $data = ['title'=>'kingquick'], $extract = true){
       if(has_session('view_data')){
        $data = session('view_data');
        session_end('view_data');
       }

       if(has_session('view_extract')){
         $extract = session('view_extract');
         session_end('view_extract');
       }

       //Extract data for single variable referencing
       if(!empty($data) && $extract == true){
        extract($data);
       }
       
       if($view == null){
         error([
           'title' => 'File Not Specified',
           'description' => 'No file was specified for handling'
         ]);
       }else{
         //Check if a specific path value is provided
         if(strpos($view, '/') === false){
           $view_path = APP_ROOT . 'views/' . get_called_class() . '/' . $view . '.php';
         }else{
           $view_path = APP_ROOT . 'views/' . $view . '.php';
         }
       }
       
       //Check if file path exists in the views folder
       if(file_exists($view_path)){
         $content = $view_path;
         require_once APP_ROOT . 'views/_layout/' . $this->layout . '.php';
         exit();
       }
       else {
        error([
          'title' => 'Error 404',
          'description' => 'Page Not Found'
        ]);
       }
     }

     /**
     * Redirect to a different Page: redirect($goto = './')
     * @method This redirects the to a different page
     * @param String $goto page to redirect to
     * @return Void
     */
    protected function redirect($goto = './', $data = '', $extract = true){
      if(!empty($data)){
        session('view_data', $data);
        session('view_extract', $extract);
      }else{
        session_end('view_data');
        session_end('view_extract');
      }

      header("Location: " . $goto);
      exit();
    }
   }