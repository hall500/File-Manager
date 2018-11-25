<?php 
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