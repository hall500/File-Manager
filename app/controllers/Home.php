<?php
  class Home extends Controller {

    public function __construct(){
      //Define All required models here
      $this->users = $this->model('Users');
      $this->feeds = $this->model('TwitterFeeds');
      $this->widget = $this->widget('components/Alert');
    }

    public function index(){
      //Core::debug(get_object_vars($this->users), true);
      $data = [
        'title' => 'Home Page',
        'user' => ''//$this->feeds->delete(2)
      ];
      $this->view('index', $data);
    }

    public function about(){
      $data = [
        'title' => $this->widget->run(),
      ];

      if(Request::get()->has_post()){
        $data = Request::get()->post();
        $this->redirect('contact');
      }
      $this->view('about', $data);
    }

    public function contact(){
      $this->view('contact');
    }
  }