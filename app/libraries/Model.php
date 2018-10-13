<?php
  /**
   * Base Model Class
   */
   class Model {
     private $table = '';
     protected $db;

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
          $sql = "SELECT * FROM $this->table WHERE id = $id";
          $this->db->query($sql);
          return $this->db->single();
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

    /*--Create,Update and Delete methods--*/
    public function save() {
      // A new record won't have an id yet.
      return isset($this->id) ? $this->update() : $this->create();
    }

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

    public function read(){

    }

    public function delete($id=0) {
        $sql = "DELETE FROM ". $this->table;
        $sql .= " WHERE id = :id";

        $this->db->query($sql);

        $this->db->bind(":id", $id);
        
        if(!$this->db->execute()) return false;
    }	
   }

