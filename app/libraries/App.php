<?php
	
  class App {
		private static $instance = NULL;
	  protected $currentController = 'Home';
	  protected $currentMethod = 'index';
		protected $params = [];
		
		public static function instantiate(){
			if(self::$instance == NULL){
				self::$instance = new App();
			}
			return self::$instance;
		}
	  
	  private function __construct(){
			$url = $this->getUrl();
		  
		  //Look in controllers for first value
		  if(file_exists(APP_ROOT . 'controllers/'.ucwords($url[0]).'.php')){
			  $this->currentController = ucwords($url[0]);
			  unset($url[0]);
			}

		  require_once(APP_ROOT . 'controllers/' . ucwords($this->currentController) . '.php');
		  $this->currentController = $this->currentController . 'Controller';
		  $this->currentController = new $this->currentController;
		  
		  //Check for Controller Method
		  if(isset($url[1])){
			  if(method_exists($this->currentController, $url[1])){
				  $this->currentMethod = strtolower($url[1]);
			  }
			}
			
			unset($url[1]);

			$this->params = $url ? array_values($url) : [];

			$result = call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
			//App::debug($result, true);
	  }
	  
	  protected function getUrl(){
		  if(isset($_GET['url'])){
			  $url = rtrim($_GET['url'], '/');
			  $url = filter_var($url, FILTER_SANITIZE_URL);
			  $url = explode('/',$url);
			  return $url;
		  }
		}
		
		/**
		 * Function to test data at specific code points
		 * @param Mixed $test Data for testing
		 * @param Boolean $die End code execution
		 */
		public static function debug($test, $die = false){
			print_r("<pre>");
			print_r($test);
			print_r("</pre>");
			if($die == true){
				die();
			}
		}
	}