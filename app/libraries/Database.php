<?php
  /**
   * PDO Database Class
   * COnnects to database
   */
  
  class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;

    private $dbh;
    private $stmt;
    private $error;
    private static $instance = NULL;

    public static function init(){
      if(self::$instance == NULL){
        self::$instance = new Database();
      }
      return self::$instance;
    }
    
    private function __construct(){
      $this->dbh = null;
      $this->host = DB_HOST;
      $this->user = DB_USER;
      $this->pass = DB_PASS;
      $this->dbname = DB_NAME;
      $this->connect();
    }

    private function connect(){
      if(!isset($this->dbh)){
        try{
          $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
          $this->dbh->setAttribute(PDO::ATTR_PERSISTENT, true);
          $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $this->dbh->exec('SET NAMES "utf8"');
        }
        catch(PDOException $e){
          $this->error = $e->getMessage();
        }
      }
      if(!$this->dbh){
        function __destruct(){
          $this->dbh = null;
        }
      }else{
        return $this->dbh;
      }
    }

    /**
     * Query Database using prepared statements
     * @param String:$sql
     */
    public function query($sql){
      if($this->dbh){
          $this->stmt = $this->dbh->prepare($sql);
      }
    } 

    public function bind($param, $value, $type = null){
      if(is_null($type)){
        switch(true){
          case is_int($value):
            $type = PDO::PARAM_INT;
            break;
          case is_bool($value):
            $type = PDO::PARAM_BOOL;
            break;
          case is_null($value):
            $type = PDO::PARAM_NULL;
            break;
          default:
            $type = PDO::PARAM_STR;
            break;
        }
      }

      if($this->stmt){
          $this->stmt->bindValue($param, $value, $type);
      }
    }

    public function execute(){
      if($this->stmt){
          return $this->stmt->execute();
      }
    }

    public function resultSet(){
      $this->execute();
      if($this->stmt){
          return $this->stmt->fetchAll(PDO::FETCH_OBJ);
      }
    }

    public function single(){
      $this->execute();
      if($this->stmt){
          return $this->stmt->fetch(PDO::FETCH_OBJ);
      }
    }

    public function count(){
      $this->execute();
      if($this->stmt){
          return $this->stmt->rowCount();
      }
    }
  }