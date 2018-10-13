<?php
  class Request {
    static private $instance = NULL;
    private $_bodyParams;

    private function __construct(){}
    
    static public function get(){
      if(self::$instance == NULL){
        self::$instance = new Request;
      }
      return self::$instance;
    }
    /**
     * Returns the method of the current request (e.g. GET, POST, HEAD, PUT, PATCH, DELETE).
     * @return string request method, such as GET, POST, HEAD, PUT, PATCH, DELETE.
     * The value returned is turned into upper case.
     */
    public function getMethod()
    {

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    public function post($name = null, $defaultValue = null){
      if(is_null($name)){
        $this->getBodyParams();
        return $this->_bodyParams;
      }
      return $this->getBodyParam($name, $defaultValue);
    }

    public function getBodyParams(){
      if($this->getMethod() == 'POST'){
        $this->_bodyParams = $_POST;
      }
    }

    public function has_post(){
      if(!empty($this->post())){
        return true;
      }
      return false;
    }

    public function setBodyParams($values){
      $this->_bodyParams = $values;
    }

    public function getBodyParam($name = null, $defaultValue = null){
      $params = $this->getBodyParams();

      if(is_object($params)){
        try{
          return $params->{$name};
        }catch(Exception $e){
          return $defaultValue;
        }
      }

      return isset($params[$name]) ? $params[$name] : $defaultValue;
    }
  }
