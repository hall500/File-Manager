<?php 
    class Validate {
        private static $_errors = [];
        
        public static function email($value){
            if(!is_string($value)){
                self::$_errors['email'] = 'Email should be a string';
                return false;
            }

            if(strpos($value, '@') === false){
                self::$_errors['email'] = 'Email should contain @';
                return false;
            }

            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i';
            if(!preg_match($regex, $value, $output_array)){
                self::$_errors['email'] = 'Invalid email format';
                return false;
            }

            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                self::$_errors['email'] = 'Invalid email format';
                return false;
            }

            
            return $value;
        }

        public static function password($value, $options = ['min' => 8, 'max' => 255]){
            if(!is_string($value)){
                self::$_errors['password'] = 'Password should be a string';
                return false;
            }

            if(strlen($value) < $options['min']){
                self::$_errors['password'] = 'Password too short';
                return false;
            }

            if(strlen($value) > $options['max']){
                self::$_errors['password'] = 'Password too long';
                return false;
            }

            
            return $value;
        }

        public static function password_match($value1, $value2){
            if(!is_string($value1)){
                self::$_errors['password'] = 'Password should be a string';
                return false;
            }

            if(!is_string($value2)){
                self::$_errors['password'] = 'Password should be a string';
                return false;
            }

            if($value1 !== $value2){
                self::$_errors['password'] = 'Passwords do not match';
                return false;
            }

            
            return true;
        }

        public static function hash($value, $check = true){
            if($check){
                $value = self::password($value);
            }

            $options = array(
                'salt' => crypt($value, PASS_SALT),
                'cost' => 12,
            );
            
            $hash = password_hash($value, PASSWORD_BCRYPT, $options);
            
            return $hash;
        }

        public static function phone($value = ''){
            if(is_null($value)){
                self::$_errors['phone'] = 'Value should not be empty';
                return false;
            }

            $filtered_phone_number = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            $phone_to_check = str_replace("-", "", $filtered_phone_number);

            if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
                self::$_errors['phone'] = 'Phone Length does not match a valid phone number!';
                return false;
            }

            
            return $phone_to_check;
        }

        public static function string($value){
            if(!is_string($value)){
                self::$_errors['string'] = 'Not a string';
                return false;
            }

            
            return $value;
        }

        public static function integer(){
            if(!is_int($value)){
                self::$_errors['integer'] = 'Not an integer';
                return false;
            }

            
            return $value;
        }

        public function errors(){
            $errs = self::$_errors;
            self::$_errors = [];
            return $errs;
        }
    }