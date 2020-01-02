<?php 
  class FileManager {
    protected $_dir = APP_ROOT . "uploads/files";

    public function scan(){
      $files = array();

      // Is there actually such a folder/file?

      if(file_exists($this->_dir)){
      
        foreach(scandir($this->_dir) as $f) {
        
          if(!$f || $f[0] == '.') {
            continue; // Ignore hidden files
          }

          if(is_dir($this->_dir . '/' . $f)) {

            // The path is a folder

            $files[] = array(
              "name" => $f,
              "type" => "folder",
              "path" => $this->_dir . '/' . $f,
              "items" => $this->scan($this->_dir . '/' . $f) // Recursively get the contents of the folder
            );
          }
          
          else {

            // It is a file

            $files[] = array(
              "name" => $f,
              "type" => "file",
              "path" => $this->_dir . '/' . $f,
              "size" => filesize($this->_dir . '/' . $f) // Gets the size of this file
            );
          }
        }
      
      }

      return $files;
    }


  }