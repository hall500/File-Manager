<?php

  //Start a session for the app
  session_start();

  /**
   * Sets and Displays a flash message
   * @param string $name The name of the flash session
   * @param string $message Message to display
   * @param string $class Alert class to display 
   */
  function flash($name = '', $message = '', $class = 'alert alert-success' ,$script = false){
    if(!empty($name)){
      if(!empty($message) && empty($_SESSION[$name])){
        if(!empty($_SESSION[$name])){
          unset($_SESSION[$name]);
        }

        if(!empty($_SESSION[$name . '_class'])){
          unset($_SESSION[$name . '_class']);
        }

        if(!empty($_SESSION[$name . '_script'])){
          unset($_SESSION[$name . '_script']);
        }

        $_SESSION[$name] = $message;
        $_SESSION[$name. '_class'] = $class;
        if($script === true) {
          $_SESSION[$name. '_script'] = 
          "<script>
              const flash = document.getElementById('msg_flash');
              window.setTimeout(() => {
                flash.style.display = 'none';
              }, 2000);
          </script>";
        }
      }elseif(empty($message) && !empty($_SESSION[$name])){
        $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
        $script = !empty($_SESSION[$name . '_script']) ? $_SESSION[$name . '_script'] : '';
        echo "
        <div class='col-md-12'>
          <div class='$class' id='msg_flash'>$_SESSION[$name]</div>
        </div>
        $script
        ";

        unset($_SESSION[$name]);
        unset($_SESSION[$name . '_class']);
        unset($_SESSION[$name . '_script']);
      }
    }
  }

  /**
   * Set or Get the value of a session
   * @param String $name The name of the session
   * @param Mixed $value The value of the session
   * @return Mixed $_SESSION[$name] Return the session name
   */
  function session($name = '', $value = null){
    if(!empty($name)){
      if(!empty($value) && empty($_SESSION[$name])){
        if(!empty($_SESSION[$name])){
          unset($_SESSION[$name]);
        }

        $_SESSION[$name] = $value;
      }elseif(empty($value) && !empty($_SESSION[$name])){
        return $_SESSION[$name];
      }
    }
  }

  /**
   * Checks if the Database
   * @param String $name name of session
   * @return Boolean
   */
  function has_session($name = ''){
    if(!empty($name)){
      return isset($_SESSION[$name]);
    }
  }

  /**
   * End the Session
   * @param String $name name of session
   * @return Void
   */
  function session_end($name = ''){
    if(!empty($name)){
      unset($_SESSION[$name]);
    }
  }