<?php


  /**
   * Test Given Data
   */
  function debug($text, $kill = false){
    print_r("<pre>");
    print_r($text);
    print_r("</pre>");

    if($kill == true){
      die();
    }
  }

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
      die("An Error has Occurred");
    }
  }
  
  /**
   * Include a required file: _inc($file, $require = false)
   * @param string $file include desired php file fragment
   * @param boolean $require decides whether to require the file or not
   * @return void
   */
  function _inc($file, $require = false){
    if($require !== true){
      $success = include APP_ROOT . '/views/_inc/' . $file . '.php';
      if(!$success){
        error([
          'title' => 'File Not Found',
          'description' => 'The path to file you are looking for does not exist'
        ]);
      }
    }else{
      require APP_ROOT . '/views/_inc/' . $file . '.php';
    }
  }

  /**
   * Verify if user is logged in already
   * @return boolean
   */
  function is_user(){
    if(!isset($_SESSION['user_logged_in'])){
      return false;
    }
    return true;
  }

  /**
   * Verify if user is not logged in
   * @return boolean
   */
  function is_guest(){
    if(isset($_SESSION['user_logged_in'])){
      return false;
    }
    return true;
  }

  /**
   * Get Logged in Users Id
   * @return int:id
   */
  function get_user_id(){
    if(isset($_SESSION['user_id'])){
      return $_SESSION['user_id'];
    }
    return 0;
  }

  /**
   * Return to Home
   * @return void
   */
  function goHome(){
    redirect("../");
  }

  /**
   * Rearrange multiple files passed in from $_FILES array into an organized array
   * @param array:file_post $_FILES array passed in from desired form
   * @return array:file_ary returns a rearranged order of files
   */
  function reArrayFiles(&$file_post) {
      //Declares an empty array
      $file_ary = array();
      //Count the number of files passed in
      $file_count = count($file_post['name']);
      //Get all file keys available in the given array of files
      $file_keys = array_keys($file_post);

      //Loop through all the available file count
      for ($i=0; $i<$file_count; $i++) {
        //Rearrange the stored files 
          foreach ($file_keys as $key) {
              $file_ary[$i][$key] = $file_post[$key][$i];
          }
      }

      //Return rearranged files
      return $file_ary;
  }

  /**
   * Get Files from posted data
   */
  function files($file_handle = ''){
    if(isset($_FILES) && !empty($file_handle)){
      return reArrayFiles($_FILES[$file_handle]);
    }else{
      return null;
    }
  }

  /**
   * Save a file to a folder within the server
   * @param Array $tmp Specify the name of the passed file and its temporary name
   * @param String $path Specify the path to save the file
   * @return void
   */
  function save_file($tmp = ['tmp_name' => '', 'name' => ''], $path = ""){
    //Check if path exists already
    if(is_dir($path)){
      //Open path and save desired file into it
      if($dh = opendir($path)){
        move_uploaded_file($tmp['tmp_name'], $path . "/{$tmp['name']}");
      }
    }else{
      //Create path if it doesn't exist
      mkdir($path, 0700, true);
      //Save desired file into the newly created path
      move_uploaded_file($tmp['tmp_name'], $path . "/{$tmp['name']}");
    }
  }

  function create_file($file_path = '', $file_name = '', $file_content = null){
  }

  /**
	 * Get all files in a specified folder and require them
	 * get_files($folder): void
	 * @param String $folder Name of desired folder in backend 
	 * @return void
	 */
	function get_files($path){
		// Open a known directory, and proceed to read its contents
		if (is_dir($path)) {
			if ($dh = opendir($path)) {
				while (($file = readdir($dh)) !== false) {
					if(!isDot()){
						echo($path . '/' . $file);
					}
				}
				closedir($dh);
			}
		}
  }
  
  /**
   * Convert Text from Camel Case to Snake Case
   * @param String $input String input to convert example: TwitterFeeds, Users
   */
  function from_camel_case($input = '') {
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
      $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
  }