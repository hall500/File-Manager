<?php
  /**
   * Setting up controller
   */
  class ControllerNameController extends Controller {
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
      //echo "Welcome to " . get_called_class();
      $this->view('index');
    }
  }