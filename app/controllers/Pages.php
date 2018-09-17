<?php
  class Pages extends Controller {

    public function __construct(){
      $this->stateModel = $this->model('User');
    }

    public function index(){
      $this->view('index', ['name' => 'Jenny Smith']);
    }

    public function about($name, $id){
      echo 'Your Name is ' . $name; echo '<br>';
      echo 'Your id is '. $id;
    }
  }