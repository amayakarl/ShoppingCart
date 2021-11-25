<?php
require_once dirname(dirname(__FILE__)).'/Database/User.php';
require_once dirname(dirname(__FILE__)).'/Database/Auth.php';
require_once dirname(dirname(__FILE__)).'/Lib/Validate.php';

class UserController{
    // performs server side validation of login form.
    public function validateLoginForm($post){
        $retMessage = [ "state" => false, "message" => ""];

        if(empty($post["email"]) || empty($post["password"])){
            $retMessage["message"] = "Please make sure email and password are not empty.";
        }
        else if(!Validate::isValidEmail($post["email"])){
            $retMessage["message"] = "Please make sure to provide a valid email.";
        }
        else $retMessage["state"] = true;

        return $retMessage;
    }
    public function authenticateUser($user){
        $retMessage = [ "state" => false, "message" => ""];
        // get the user from the database, if the user doesn't exist then null is returned.
        $userFromDB = User::findBy('email', $user["email"]);

        // check if the user exists
        if(is_null($userFromDB)){
            $retMessage["message"] = "Your email or password is incorrect. as";
            return $retMessage;
        }
        //or if the user's password matches the password with which they are attempting to login
        else if(md5($user["password"]) !== $userFromDB["password"]){
            $retMessage["message"] = "Your email or password is incorrect. fdd";
            return $retMessage;
        }
        
        // if the user is authentic, then return the user's data.
        $retMessage["state"] = true;
        $key = $userFromDB["email"].$userFromDB["password"].date('Y-m-d i:s:u');
        $key = Auth::addKey(//create an api key for the user
            $userFromDB["id"], 
            md5($key)
        );
        
        $userFromDB["api_key"] = $key;
        $retMessage["user"] = $userFromDB;
        return $retMessage;

    }
    // performs a server side validation of the signup form.
    public function validateUserSignUpForm($post){
        
        $retMessage = [ "state" => false, "message" => ""];

        if(empty($post["firstname"]) 
            || empty($post["lastname"]) 
            || empty($post["email"])
            || empty($post["password"]
            || empty($post["conf_password"]))){
                $retMessage["message"] = "Please make sure you fill out all required fields.";
            }
        else if(!Validate::isValidEmail($post["email"])){
            $retMessage["message"] = "Please enter a valid email.";
        }
        else if(!Validate::isValidPassword($post["password"])){
            $retMessage["message"] = "Make sure your password meets all password requirements.";
        }
        else if(!Validate::confirmPassword($post["password"], $post["conf_password"])){
            $retMessage["message"] = "Your password and confirmation password do not match.";
        }
        else if(!isset($post["tos"])){
            $retMessage["message"] = "please make sure you accept our terms of service.";
        }
        else $retMessage["state"] = true;
        
        return $retMessage;
    }
    // call the user database interface to create a new user using $user data.
    public function createUser($user){
        $newUser = User::create($user);
        return $newUser;
    }
}