<?php
  class Core {
		private static $instance = NULL;
	  protected $currentController = 'Pages';
	  protected $currentMethod = 'index';
		protected $params = [];
		
		public static function instantiate(){
			if(self::$instance == NULL){
				self::$instance = new Core();
			}
			return self::$instance;
		}
	  
	  private function __construct(){
			$url = $this->getUrl();
		  
		  //Look in controllers for first value
		  if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')){
			  $this->currentController = ucwords($url[0]);
			  unset($url[0]);
			}
		  
		  require_once('../app/controllers/' . $this->currentController . '.php');
		  $this->currentController = new $this->currentController;
		  
		  //Check for Controller Method
		  if(isset($url[1])){
			  if(method_exists($this->currentController, $url[1])){
				  $this->currentMethod = strtolower($url[1]);
			  }
			}
			
			unset($url[1]);

			$this->params = $url ? array_values($url) : [];

			call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
	  }
	  
	  protected function getUrl(){
		  if(isset($_GET['url'])){
			  $url = rtrim($_GET['url'], '/');
			  $url = filter_var($url, FILTER_SANITIZE_URL);
			  $url = explode('/',$url);
			  return $url;
		  }
		}
		
		public static function debug($test, $die = 0){
			print_r("<pre>");
			print_r($test);
			print_r("</pre>");
			if($die == 1){
				die();
			}
		}
	}