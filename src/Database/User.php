<?php
require_once 'Database.php';

class User{
    static private $table = "user";
    static public function create($user){
        $existingUser = User::findBy('email', $user["email"]);
        
        if(!is_null($existingUser))
            return ["status" => false, "message" => "An account with the same email already exists."];
        
        $query = "insert into ".User::$table." (fname, lname, email, password) values (?,?,?,?)";
        $user["password"] = md5($user["password"]);
        $params = ['ssss', $user["firstname"], $user["lastname"], $user["email"], $user["password"]];

        $result = Database::exec($query, $params, function ($stmt){
            $stmt->execute();
            $stmt->store_result();
            return [
                "status" => true, 
                "message"=>"User created, ".$stmt->affected_rows,                                
            ];

        });
        $result["data"] = User::findBy('email', $user["email"]);
        return $result;
    }
    static public function findBy($col, $value){
        $query = "select * from ".User::$table." where $col = ?";

        $user = Database::exec($query, ['s', $value], function($stmt){    
            $stmt->execute();            
            $stmt->bind_result($id, $fname, $lname, $email, $password, $role_id, $created_ts, $updated_ts);            
            $user = [];            
            while($stmt->fetch()){
                $user[] = [
                    "id"=>$id,
                    "fname"=>$fname,
                    "lname"=>$lname,
                    "email"=>$email,
                    "password"=>$password,
                    "role_id"=>$role_id,
                    "created"=>$created_ts,
                    "updated_ts"=>$updated_ts
                ];
            }         
            return $user[0] ?? NULL;
        });

        return $user;
    }
    static public function exists(){
        
    }
}