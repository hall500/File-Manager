<?php 
  /**
   * Verify if user is logged in already
   * @return boolean
   */
  class UserHelper {
    public static function isLoggedIn(){
      if(!isset($_SESSION['_login_token'])){
        return false;
      }
      
      $key = $_SESSION['_login_token'];
      
      if(!isset($_SESSION[$key . '_logged_in'])){
        return false;
      }

      if($_SESSION[$key . '_logged_in'] == false){
        return false;
      }
  
      return true;
    } 
  
    public static function getToken(){
      if(!isset($_SESSION['_login_token'])){
        return false;
      }
  
      return $_SESSION['_login_token'];
    }
  
    public static function role($role = 'user'){
      if(empty($role)){
        return false;
      }

      if(!is_string($role)){
        return false;
      }
  
      if(!isset($_SESSION['_login_token'])){
        return false;
      }
  
      $key = $_SESSION['_login_token'];
      
      if($_SESSION[$key . '_role'] == $role){
        return true;
      }
  
      return false;
    }
  
    /**
     * Verify if user is not logged in
     * @return boolean
     */
    public static function isGuest(){
      if(isset($_SESSION['_login_token'])){
        return false;
      }
  
      return true;
    }
  
    /**
     * Get Logged in Users Id
     * @return int:id
     */
    public static function getUserId(){
      if(isset($_SESSION['_login_token'])){
        return 0;
      }
      $key = $_SESSION['_login_token'];
      if(isset($_SESSION[$key . '_id'])){
        return $_SESSION[$key . '_id'];
      }
      return 0;
    }
  
    /**
     * Return to Home
     * @return void
     */
    public static function goHome(){
      header("Location: " . APP_HOST . URL_ROOT);
      exit();
    }
  }