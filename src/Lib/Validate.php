<?php

class Validate{
    
    static public function confirmPassword($passrd, $confPassrd){
        return $passrd == $confPassrd;
    }
    static public function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    static public function isValidPassword($password){
       
        $number = preg_match('@[0-9]@', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
       
       return strlen($password) >= 8 && $number && $uppercase && $lowercase && $specialChars;
    }
    
}