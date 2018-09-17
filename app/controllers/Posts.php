<?php
  class Posts extends Controller {

    public function index(){
      $this->view('index');
    }

    public function about($name, $id){
      echo 'Your Name is ' . $name; echo '<br>';
      echo 'Your id is '. $id;
    }
  }