<?php
  /**
   * Base Model Class
   */

   class Model {
     protected $table = '';
     protected $db;
     protected $page_limit = 10;

    public function __construct(){
      $this->table = from_camel_case(get_called_class());
      $this->db = Database::init();

    }

    public function findall(){
      $this->db->query("SELECT * FROM " . $this->table);
      return $this->db->resultSet();
    }

    public function findbyid($id = null){
      if(!is_null($id)){
          $sql = "SELECT * FROM $this->table WHERE id=:id";
          $this->db->query($sql);
          $this->db->bind(':id', $id);
          return $this->db->single();
      }
      return false;
    }

    public function findby($params = ['param' => 'id', 'value' => null], $return_val = 'single'){
      $params_default = [
        'param' => 'id'
      ];
      $params = array_merge($params_default, $params);
      if(!is_null($params['value'])){  
          $sql = "SELECT * FROM $this->table WHERE {$params['param']}=:{$params['param']}";
          $this->db->query($sql);
          $this->db->bind(':'. $params['param'], $params['value']);
          if($return_val === 'multiple'){
            return $this->db->resultSet();
          }elseif($return_val === 'single'){
            return $this->db->single();
          }
      }
      return false;
    }

    public function describe(){
      $sql = "DESC " . $this->table;
      $this->db->query($sql);
      $rows = $this->db->resultSet();

      $f = array();
      for ( $x=0; $x<count( $rows ); $x++ ) {
        $f[] = $rows[$x]->Field;
      }
      
      return $f;
    }

    /**
     * Cleaning the raw data before submitting to Database
     * @return Boolean returns true if the key exists
     */
    private function has_attribute($attribute) {
      // We don't care about the value, we just want to know if the key exists
      return array_key_exists($attribute, $this->attributes());
    }

    protected function attributes() { 
      // return an array of attribute names and their values
      $attributes = array();
      foreach($this->describe() as $field) {
        if(property_exists($this, $field)) {
          $attributes[$field] = $this->$field;
        }
      }
      return $attributes;
    }

    protected function sanitized_attributes() {
      $clean_attributes = array();
      // sanitize the values before submitting
      // Note: does not alter the actual value of each attribute
      foreach($this->attributes() as $key => $value){
        $clean_attributes[$key] = $value; //$mydb->escape_value($value);
      }
      return $clean_attributes;
    }

    /**
     * Saves or Updates data attributes to database. It Updates $this->id is set 
     */
    /*--Create,Update and Delete methods--*/
    public function save() {
      // A new record won't have an id yet.
      return isset($this->id) ? $this->update() : $this->create();
    }

    /**
   * Insert data into database table 
   * @return boolean returns boolean true if data is saved successfully
   */
    public function create() {
      // Don't forget your SQL syntax and good habits:
      // - INSERT INTO table (key, key) VALUES ('value', 'value')
      // - single-quotes around all values
      // - escape all values to prevent SQL injection
      $attributes = $this->sanitized_attributes();
      $sql = "INSERT INTO $this->table (";
      $sql .= join(",", array_keys($attributes)); 
      $sql .= ") VALUES (:"; 
      $sql .= join(",:", array_keys($attributes)); 
      $sql .= ")";

      $this->db->query($sql);

      for($i=0; $i < count($attributes); $i++){
          $this->db->bind(":". array_keys($attributes)[$i], array_values($attributes)[$i]);
      }

      if($this->db->execute()){
        return $this->db->execute();
      }
      return false;
    }

    /**
   * Select data from database
   * @param Int $id Where condition for limiting the size of rows returned
   * @return Boolean returns boolean true if the update is successful
   */
    public function update($id="") {
      $attributes = $this->sanitized_attributes();
      if(empty($id) && isset($attributes['id'])){
        $id = $attributes['id'];
        unset($attributes['id']);
      }
      $attribute_pairs = array();
      foreach($attributes as $key => $value) {
        $attribute_pairs[] = "{$key}=:{$key}";
      }
      $sql = "UPDATE ". $this->table ." SET ";
      $sql .= join(", ", $attribute_pairs);
      $sql .= " WHERE id =:id";

      $this->db->query($sql);

      for($i=0; $i < count($attributes); $i++){
          $this->db->bind(":". array_keys($attributes)[$i], array_values($attributes)[$i]);
      }

      $this->db->bind(":id", $id);

      if(!$this->db->execute()) return false;
    }

    /**
   * Select data from database
   * @param Array $columns table fields to select from the table
   * @param String $where Where condition for limiting the size of rows returned
   * @return Array returns an array of rows from the database table, if nothing is found returns 0
   */
    public function read($columns = [], $where = [], $condition = 'AND'){
      $sql = "SELECT ";
      $sql .= join(",", array_values($columns));
      $sql .= " FROM ". $this->table;

      $attribute_pairs = array();
      foreach($where as $key => $value) {
        $attribute_pairs[] = "{$key}=:{$key}";
      }

      if(!empty($where)){
          $sql .= " WHERE ";
          $sql .= join(" $condition ", $attribute_pairs);
      }

      $this->db->query($sql);

      foreach($where as $key => $value){
        $this->db->bind(":".$key, $value);
      }

      return $this->db->resultSet();
    }

    /**
   * Delete a row from a database table
   * @param String $table Table to delete row from
   * @param Int $id Row ID to delete
   * @return boolean
   */
    public function delete($id=0) {
        $sql = "DELETE FROM ". $this->table;
        $sql .= " WHERE id = :id";

        $this->db->query($sql);

        $this->db->bind(":id", $id);
        
        if(!$this->db->execute()) return false;
    }	

    public function pagination($page = 0){
      if($page > 0){
        $page_diff = (($page * $this->page_limit) - $this->page_limit);
        return " LIMIT $page_diff, {$this->page_limit}";
      }
    }

    public function page_count($total_rows = 0){
      return ceil($total_rows/$this->page_limit);
    }
   }

