<?php
  /**
   * Base Controller Class
   * Loads Models and Views
   */

   abstract class Controller {
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
      * Load a new package class
      * @param String $model Gets the required package class
      * @return Package new $package() returns the called package classs
      */
     protected function package($package = ''){
      //Checks if $model is set 
      if(!empty($package) && is_string($package)){
        $package_path = APP_ROOT . 'packages/' . ucwords($package) . '.php';
        if(file_exists($model_path)){
          require_once $package;
          return new $package();
        }
		
		/*
        error([
          'title' => 'Package Not Found',
          'description' => 'Ops! It seems the package <strong>' . ucwords($package) . '</strong> does not exist<br>Please Create It first'
        ]);*/
      }
     }

     /***
      * Load a new Widget Class
      */
     protected function widget($widget = ''){
       $url = explode('/',$widget);
       $widget_name = $url[count($url) - 1];
       //Check if $widget is available
       if(!empty($widget) && is_string($widget)){
         $widget_path = APP_ROOT . 'widgets/' . $widget . '.php';
         if(file_exists($widget_path)){
           include_once $widget_path;
           return new $widget_name();
         }

         error([
          'title' => 'Widget Not Found',
          'description' => 'Ops! It seems the widget <strong>' . ucwords($model) . '</strong> does not exist<br>Please Create It first'
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
			     $class_name =  str_replace("Controller","",get_called_class());
           $content = APP_ROOT . 'views/' . $class_name . '/' . $view . '.php';
         }else{
           $content = APP_ROOT . 'views/' . $view . '.php';
         }
       }
       
       //Check if file path exists in the views folder
       if(file_exists($content)){
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