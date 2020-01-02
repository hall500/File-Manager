<?php

  class LoginController extends Controller {
    protected $layout = 'blank';
    public $user = [];

    public function __construct(){
      //Define All required models here
      if(isset($_SESSION['_dfsefyyesbdshfydfwg3453nd_dsd'])){
        $this->redirect('files');
      }
    }

    public function index(){
      if(Request::has_post()){
        $body = (Object) Request::post();
        if($body->email != 'admin@mail.com'){
          echo json_encode([
            'status' => 'false',
            'message' => 'Wrong Email Address'
          ]);
          exit;
        }

        if($body->password != 'admin123456'){
          echo json_encode([
            'status' => 'false',
            'message' => 'Invalid Password'
          ]);
          exit;
        }

        $_SESSION['_dfsefyyesbdshfydfwg3453nd_dsd'] = ['user' => 'admin', 'email' => 'admin@mail.com'];

        echo json_encode([
          'status' => 'true',
          'message' => 'Access Granted',
          'link' => './files'
        ]);
        exit;
      }
      $data = [
        'title' => 'Home Page'
      ];
      $this->view('index', $data);
    }
  }