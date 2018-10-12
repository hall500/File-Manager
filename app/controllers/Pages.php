<?php
  class Pages extends Controller {

    public function __construct(){
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
      return $this->view('about', $data);
    }
  }