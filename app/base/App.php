<?php

  class App {
		private static $instance = NULL;
	  protected $current_controller = NULL;
	  protected $current_method = 'index';
		protected $params = [];
		
		public static function instantiate(){
			if(self::$instance == NULL){
				self::$instance = new App();
			}
			return self::$instance;
		}
	  
	  private function __construct(){
			$url = $this->parsedUrl();

		  $url[0] = isset($url[0]) ? $url[0] : ucfirst(APP_ENTRY);

			if(strpos($url[0], '-') != false){
				$url[0] = explode('-', $url[0]);
				$url[0] = ucfirst(implode("", $url[0]));
			}

		  //Look in controllers for first value
		  if(file_exists(APP_ROOT . 'controllers/'. ucwords($url[0]) .'.php')){
			  $this->current_controller = ucwords($url[0]);
			  unset($url[0]);
			}else{
				error([
          'title' => '404',
          'description' => 'Controller was not found'
        ]);
			}

			require_once(APP_ROOT . 'controllers/' . ucwords($this->current_controller) . '.php');
			$this->current_controller = $this->current_controller . 'Controller';
			$this->current_controller = new $this->current_controller;

			$url[1] = empty($url[1]) ? 'index' : $url[1];
			
		  //Check for Controller Method
		  if(isset($url[1])){
			  if(method_exists($this->current_controller, $url[1])){
					$this->current_method = strtolower($url[1]);
					unset($url[1]);

					$this->params = $url ? array_values($url) : [];

					$result = call_user_func_array([$this->current_controller, $this->current_method], $this->params);
					//App::debug($result, true);
			  }else{
					error([
						'title' => '404',
						'description' => 'Method does not exist'
					]);
				}
			}
		}
	  
	  protected function parsedUrl(){
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
		public static function debug($test = 'No param passed', $die = false){
			print_r("<pre>");
			print_r($test);
			print_r("</pre>");
			if($die == true){
				die();
			}
		}

		public static function widget($name = '', $params = []){
			return Widget::init()->run($name, $params);
		}


		/****
		 * GET FUNCTIONS
		 */
		public function getUrl(){
			return $this->parsedUrl();
		}
	}