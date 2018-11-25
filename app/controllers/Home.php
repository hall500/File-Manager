<?php
  class HomeController extends Controller {

    public function __construct(){
      //Define All required models here
    }

    public function index(){
      $data = [
        'title' => 'Home Page'
      ];
      $this->view('index', $data);
    }

    public function about(){
      $data = [
        'title' => 'About Page'
      ];
      
      $this->view('about', $data);
    }

    public function contact(){
      $this->view('contact');
    }
  }