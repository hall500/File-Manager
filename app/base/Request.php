<?php
  class Request {
    private static $_bodyParams;

    /**
     * Returns the method of the current request (e.g. GET, POST, HEAD, PUT, PATCH, DELETE).
     * @return string request method, such as GET, POST, HEAD, PUT, PATCH, DELETE.
     * The value returned is turned into upper case.
     */
    public static function getMethod()
    {

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    public static function post($name = null, $defaultValue = null){
      if(is_null($name)){
        self::getBodyParams();
        return self::validate_post();
      }
      return self::getBodyParam($name, $defaultValue);
    }

    public static function getBodyParams(){
      if(self::getMethod() == 'POST'){
        /* if(!empty($_POST)){
          $post = [];
          foreach($_POST as $key => $data){
            $post[$key] = trim($data); //trim(htmlspecialchars($data));
          }
        } */
        self::$_bodyParams = $_POST;
      }
    }

    public static function has_post(){
      //$csrf = NoCSRF::check( 'token', $_POST, true, 60*10, false );
      if(!empty($_POST)){
        return true;
      }
      return false;
    }

    public static function setBodyParams($values){
      self::$_bodyParams = $values;
    }

    public static function getBodyParam($name = null, $defaultValue = null){
      self::getBodyParams();
      $params = self::validate_post();

      if(is_object($params)){
        try{
          return $params->{$name};
        }catch(Exception $e){
          return $defaultValue;
        }
      }

      return isset($params[$name]) ? $params[$name] : $defaultValue;
    }
	
    protected static function validate_post(){
      foreach(self::$_bodyParams as $key => $param){
        switch(true){
          case is_int($param): 
            $param = filter_var($param, FILTER_VALIDATE_FLOAT);
          case is_float($param): 
            $param = filter_var($param, FILTER_VALIDATE_INT);
          default: 
            $param = filter_var($param, FILTER_SANITIZE_STRING);
        }
        $_params[$key] = $param;
      }
      
      return $_params;
    }
  }