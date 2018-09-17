<?php
  /**
   * PDO Database Class
   * COnnects to database
   */
  class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbhandler;
    private $stmt;
    private $error;

    public function __constuct(){
      $dsn = 'mysql=host=' . $this->host . ';dbname=' . $this->dbname;
      $options = [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ];

      try{
        $this->dbhandler = new PDO($dsn, $this->user, $this->pass, $options);
      }catch(PDOException $e){
        $this->error = $e->getMessage();
      }
    }
  }