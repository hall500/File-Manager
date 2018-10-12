<?php

  //Start a session for the app
  session_start();

  /**
   * Sets and Displays a flash message
   * @param string $name The name of the flash session
   * @param string $message Message to display
   * @param string $class Alert class to display 
   */
  function flash($name = '', $message = '', $class = 'alert alert-success'){
    if(!empty($name)){
      if(!empty($message) && empty($_SESSION[$name])){
        if(!empty($_SESSION[$name])){
          unset($_SESSION[$name]);
        }

        if(!empty($_SESSION[$name . '_class'])){
          unset($_SESSION[$name . '_class']);
        }

        $_SESSION[$name] = $message;
        $_SESSION[$name. '_class'] = $class;
      }elseif(empty($message) && !empty($_SESSION[$name])){
        $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
        echo "
        <div class='col-md-12>
          <div class='$class' id='msg_flash'>$_SESSION[$name]</div>
        </div>
        ";
        unset($_SESSION[$name]);
        unset($_SESSION[$name . '_class']);
      }
    }
  }