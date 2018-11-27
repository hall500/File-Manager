<?php
/**
   * Checks if user is connected to the internet
   * @param String $link Link to check for verifying internet connectivity
   */
  function is_connected($link = 'www.google.com'){
    $connected = @fsockopen("www.google.com", 80); 
    //website, port  (try 80 or 443)
    if ($connected){
      $is_conn = true; //action when connected
      fclose($connected);
    }else{
      $is_conn = false; //action in connection failure
    }
    return $is_conn;
 }