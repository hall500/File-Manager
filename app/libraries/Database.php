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

    private function __construct(){
      $this->dbh = null;
      $this->host = DB_HOST;
      $this->user = DB_USER;
      $this->pass = DB_PASS;
      $this->dbname = DB_NAME;
      $this->connect();
    }

    public static function init(){
      if(self::$instance == NULL){
        self::$instance = new Database();
      }
      return self::$instance;
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
          error([
            'title' => 'Database Error',
            'description' => 'Unable to connect to database'
          ]);
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
    public function query(String $sql){
      $this->stmt = $this->dbh->prepare($sql);
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

      $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(){
      try{
        $this->dbh->beginTransaction();
        $this->stmt->execute();
        $this->dbh->commit();
        return $this->dbh->lastInsertId();
      }catch(PDOException $e){
        $this->dbh->rollback();
        error([
          'title' => 'Database Execution Error',
          'description' => $e->getMessage()
        ]);
      }
    }

    public function resultSet(){
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single(){
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function count(){
      return $this->stmt->rowCount();
    }

    public function __destruct(){
      $this->dbh = null;
      $this->host = null;
      $this->user = null;
      $this->pass = null;
      $this->dbname = null;
      if(self::$instance != null){
        self::$instance = null;
      }
    }
  }