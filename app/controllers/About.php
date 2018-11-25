<?php
  /**
   * Setting up controller
   */
  class AboutController extends Controller {
    /**
     * Models and Widgets are initialized Here
     */
    public function __construct(){
      //Define All required models here
    }

    /**
     * Index Page for getting started
     */
    public function index(){
      $this->view('index');
    }
  }