<?php
require_once 'Database.php';

class Auth{
    static private $table = "auth";
    
    static public function removeAllKeysForUser($userId){
        $sql = Database::db();
        $stmt = $sql->prepare("delete from ".Auth::$table." where user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return;
    }
    static public function addKey( $userId,$apiKey){
        
        Auth::removeAllKeysForUser($userId);

        $sql = Database::db();
        $stmt = $sql->prepare("insert into ".Auth::$table." (user_id, api_key) values(?,?)");
        $stmt->bind_param('is', $userId, $apiKey);
        $stmt->execute();

        return $apiKey;
        
    }
    static public function doesKeyExist($key){
        $sql = Database::db();
        $stmt = $sql->prepare("select * from ".Auth::$table." where api_key = ?");
        $stmt->bind_param('s', $key);
        $stmt->execute();
        $stmt->bind_result($id, $user_id, $api_key);
        $key = [];
        while($stmt->fetch()){
            $key = [
                "id"=>$id,
                "user_id"=>$user_id,
                "api_key"=>$api_key
            ];
        }
        $stmt->close();
        $sql->close();
        return $key;
    }
    static public function getUserId($key){
        $query = "select user_id from ".Auth::$table." where api_key = ?";
        $user_id = Database::exec($query, ['s', $key], function($stmt){
            $stmt->execute();
            $stmt->bind_result($user_id);
            $userId = [];

            while($stmt->fetch()){
                $userId[] = $user_id;
            }

            return $userId[0] ?? NULL;
        });
        return $user_id;
    }
}