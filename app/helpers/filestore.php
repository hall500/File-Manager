<?php
    class FileStore {

        private static $valid_extensions = ['jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt', 'xlsx', 'docx'];

        public function view($file_name, $link = ''){
          $mime_content = mime_content_type($link);
          ob_clean();
          header("Cache-Control: no-store");
          header("Expires: 0");
          header("Content-Type: $mime_content");
          header("Cache-Control: public");
          header("Content-Disposition: inline; filename=\"".$file_name."\"");
          header("Content-Transfer-Encoding: binary");
          header('Accept-Ranges: bytes');
          @readfile($link);
          exit;
        }

        public function download_file($file_name = '', $link = ''){
          /* Download Image via link */
          /* header('Content-Type: application/octet-stream');
          header("Content-Transfer-Encoding: Binary"); 
          header("Content-disposition: attachment; filename=\"".$file_name."\""); 
          readfile($link); */

          ob_clean();
          header("Cache-Control: no-store");
          header("Expires: 0");
          header("Content-Type: application/octet-stream");
          header("Cache-Control: public");
          header("Content-Description: File Transfer");
          header("Content-Disposition: attachment; filename=\"".$file_name."\"");
          header("Content-Transfer-Encoding: binary");
          header('Accept-Ranges: bytes');
          @readfile($link);
          exit;
        }

        public static function read_folder($path = ''){
          $path = APP_ROOT . "uploads/" . $path;
          $files = [];

          if(empty($path)){
            return false;
          }

          //Declaring Directory Interator
          $iterator = new \DirectoryIterator($path);

          //Read the content of folder and require functions
          foreach ($iterator as $fileinfo) {
              if (!$fileinfo->isDot()) {
                  $files[] = (Object) [
                    'name' => $fileinfo->getFilename(),
                    'ext' => $fileinfo->getExtension(),
                    'size' => $fileinfo->getSize(),
                    'isDir' => $fileinfo->isDir(),
                    'isFile' => $fileinfo->isFile(),
                    'type' => $fileinfo->getType(),
                    'pathname' => $fileinfo->getPathname(),
                    'path' => $fileinfo->getPath()
                  ];
              }
          }

          return $files;
        }

        public static function create_folder($path = ''){
          $path = APP_ROOT . "uploads/" . $path;

          if(empty($path)){
              return false;
          }
          
          if(is_dir($path)){
              return false;
          }

          if (!@mkdir($path, 0755, true)) {
            return false;
          }

          return true;
        }
        /**
         * Save a file to a folder within the server
         * @param Array $tmp Specify the name of the passed file and its temporary name
         * @param String $path Specify the path to save the file
         * @return void
         */
        protected static function save_file($tmp = ['tmp_name' => '', 'name' => ''], $path){
            $expand = explode(".", $tmp['name']);
            $ext = end($expand);
            
            if(!in_array($ext, self::$valid_extensions)){
              return false;
            }

            $key = NoCSRF::randomString();

            $name = $tmp['name'];
            if(strpos($name, ' ') !== false){
              $name = str_replace(' ', '_', $name);
            }
            $link = $path . "/{$name}";
            //Check if path exists already
            if(is_dir($path)){
              //Open path and save desired file into it
              if($dh = opendir($path)){
                move_uploaded_file($tmp['tmp_name'], $link);
              }
            }else{
              //Create path if it doesn't exist
              mkdir($path, 0755, true);
              //Save desired file into the newly created path
              move_uploaded_file($tmp['tmp_name'], $link);
            }

            return ['link' => $link, 'key' => $key, 'ext' => $ext];
          }

        //Save File
        public static function upload($file, $path){
            if($file['size'] === 0){
              return json_encode([
                'success' => false,
                'error' => 'No file was uploaded'
              ]);
            }

            if($file['error'] !== UPLOAD_ERR_OK){
              return json_encode([
                'success' => false,
                'error' => 'An Error Occurred during upload'
              ]);
            }

            if(!is_uploaded_file($file['tmp_name'])){
              return json_encode([
                'success' => false,
                'error' => 'Invalid file name specified'
              ]);
            }

            $save_path = APP_ROOT . "uploads/" . $path;
            $saved = self::save_file($file, $save_path);
            if($saved !== false){
              return json_encode([
                'success' => true,
                'name' => $file['name'],
                'link' => $saved['link'],
                'key' => $saved['key'],
                'type' => $file['type'],
                'ext' => $saved['ext']
              ]);
            }else{
              return json_encode([
                'success' => false,
                'error' => 'Invalid Extension Specified'
              ]);
            }
        }

        //Save multiple images
        /* public static function uploads($files, $path){
            $docs = [];
            foreach($files as $file){
              $name = $file['name'];
              if($file['error'] !== UPLOAD_ERR_OK){
                $docs[$name]['error'] = 'An error occurred';
                continue;
              }
  
              if(!is_uploaded_file($file['tmp_name'])){
                $docs[$name]['error'] = 'Unable to save file';
                continue;
              }
  
              $save_path = "./uploads/" . $path;
              list($link, $key) = self::save_file($file, $save_path);
              $docs[$name] = [
                  'name' => $file['name'],
                  'link' => $link,
                  'key' => $key,
                  'type' => $file['type']
              ];
            }
        } */
        //retrieve image
        public static function retrieve($link = ''){
          if(empty($link)){
            return false;
          }

          if(!is_string($link)){
            return false;
          }

          $image = file_get_contents($link);
          $data = base64_encode($image); 
          return $data;
        }
        //manipulate images
        public function resize($link){

        }

        public function crop($link){

        }

        public function watermark($link){

        }
        
        //remove image
        public static function delete($link){
          //check if file exists
          if(file_exists($link)){
            //If file exists delete and return true
            unlink($link);
          }
          //If file does not exist return false
        }
    }