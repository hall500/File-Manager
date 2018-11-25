<?php 

  class Users extends Model {
    function get(){
      self::findById(123);
    }
  }