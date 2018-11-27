<?php
/**
   * Include a required file: _inc($file, $require = false)
   * @param string $file include desired php file fragment
   * @param boolean $require decides whether to require the file or not
   * @return void
   */
  function _use($path = ''){
    if(!empty($path)){
      require_once APP_ROOT . 'packages/' . $path;
    }else{
      echo "File does not exist";
    }
  }