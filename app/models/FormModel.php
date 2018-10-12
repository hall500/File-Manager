<?php
  class FormModel {
    private $db;
    private $table;
    private $redirect;
    private $button;

    public function __construct($table, $redirect = "", $button = 'save'){
      $this->db = new Database();
      $this->table = $table;
      $this->redirect = $redirect;
      $this->button = $button;
    }

    public function columns(){
      return [
        //Array of All Columns contained in your table 
      ];
    }

    public function insert(){
      /* $this->db->query("SELECT * FROM students");
      return $this->db->resultSet(); */
    }

    public function edit(){

    }

    public function findall(){

    }

    public function findone(){
      
    }
  }