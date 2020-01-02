<?php
  /**
   * Setting up controller
   */
  
  class FilesController extends Controller {
    /**
     * Models and Widgets are initialized Here
     */
    public function __construct(){
      //Define All required models here
      if(!isset($_SESSION['_dfsefyyesbdshfydfwg3453nd_dsd'])){
        $this->redirect('login');
      }
    }

    /**
     * Index Page for ge tting started
     */
    public function index(){
      $data = [
        'title' => 'E-Room'
      ];
      $this->view('index', $data);
    }

    public function read_dir(){
      $dir = APP_ROOT . "uploads/files";
      $response = $this->scan($dir);
      // Output the directory listing as JSON

      header('Content-type: application/json');

      echo json_encode(array(
        "name" => "files",
        "type" => "folder",
        "path" => "files",
        "items" => $response
      ));
    }

    protected function scan($dir){
      $files = array();

      // Is there actually such a folder/file?

      if(file_exists($dir)){
      
        foreach(scandir($dir) as $f) {
        
          if(!$f || $f[0] == '.') {
            continue; // Ignore hidden files
          }

          if(is_dir($dir . '/' . $f)) {

            // The pa th is a folder
            $full_path = $dir . '/' . $f;
            $path = explode("uploads/", $full_path);
            $path = end($path);
            $files[] = array(
              "name" => $f,
              "type" => "folder",
              "path" => $path,
              "items" => $this->scan($dir . '/' . $f) // Recursively get the contents of the folder
            );
          }
          
          else {
            // It is a file
            $full_path = $dir . '/' . $f;
            $path = explode("uploads/", $full_path);
            $path = end($path);
            $path = explode("/", $path);
            $path = join("::", $path);
            $path = urlencode($path);
            $files[] = array( 
              "name" => $f,
              "type" => "file",
              "path" => $full_path,
              "view" => './files/v/' . $path,
              "size" => filesize($dir . '/' . $f) // Gets the size of this file
            );
          }
        }
      
      }

      return $files;
    }

    public function new_folder(){
      $body = (Object) json_decode(file_get_contents("php://input"));
      $name = $body->name;
      $hash = $body->hash;

      if(!empty($body)){
        $create = FileStore::create_folder($hash . "/" . $name);

        if(!$create){
          echo 'failed';
          exit;
        }

        echo 'success';
        exit;
      }
    }

    public function re_name(){
      $body = (Object) json_decode(file_get_contents("php://input"));
      echo json_encode($body);
      exit;
    }

    public function upload(){
      if(isset($_FILES['files'])){
        $body = (Object) Request::post();
        $files = files('files');

        if(empty($files[0]['name']) || $files[0]['size'] == 0){
          header('Content-type: application/json');
          http_response_code(500);
          echo json_encode([
            'success' => false,
            'message' => "No file was selected"
          ]);
          exit;
        }

        foreach($files as $file){
          $file = FileStore::upload($file, $body->hash);

          $file = json_decode($file);
          if($file->success == false){
            header('Content-type: application/json');
            http_response_code(500);
            echo json_encode([
              'success' => false,
              'message' => "An error occurred during upload"
            ]);
            exit;
          }
        }

        header('Content-type: application/json');
        http_response_code(200);
        echo json_encode([
          'success' => true,
          'message' => "All files uploaded successfully"
        ]);
        exit;
      }
    }

    public function v($link){
      $link =  urlencode($link);
      $file_path = explode("::", $link);
      $name = end($file_path);
      $path = join("/", $file_path);
      App::debug($path, true);
      $path = APP_ROOT . "uploads/" . $path;
      //$file = file_get_contents($path);
      //App::debug($file, true);
      
      $mime_content = mime_content_type($path);
      ob_clean();
      header("Cache-Control: no-store");
      header("Expires: 0");
      header("Content-Type: $mime_content");
      header("Cache-Control: public");
      header("Content-Disposition: inline; filename=\"".$name."\"");
      header("Content-Transfer-Encoding: binary");
      header('Accept-Ranges: bytes');
      @readfile($path);
      exit;
    }

    public function logout(){
      unset($_SESSION['_dfsefyyesbdshfydfwg3453nd_dsd']);
      $this->redirect('login');
    }
  }