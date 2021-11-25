<?php

class PromoCode{
    static private $table = "promocodes";

    static public function useCode($code){
        $query = "update ".PromoCode::$table." 
            set is_used = 1 where is_used = 0 and code = ?";
        $inserted = Database::exec($query, ['s', $code], function($stmt){
            $stmt->execute();
            $stmt->store_result();
            return $stmt->affected_rows > 0;
        });

        if($inserted){
            return PromoCode::get($code);
        }
        else return NULL;
    }
    static private function get($code){
        $query = "select * from ".PromoCode::$table." where code = ?";
        return Database::exec($query, ['s', $code], function($stmnt){
            $stmnt->execute();
            $stmnt->bind_result($id, $code_str, $value, $is_used, $used_ts, $created_ts);
            $promoCode = [];

            while($stmnt->fetch()){
                $promoCode[] = [
                   "id" => $id, 
                   "code_str" => $code_str, 
                   "value" => $value, 
                   "is_used" => $is_used, 
                   "used_ts" => $used_ts, 
                   "created_ts" => $created_ts
                ];
            }

            return $promoCode[0] ?? NULL;
        });
    }
    static public function getById($id){
        if(is_null($id)) return NULL;

        $query = "select * from ".PromoCode::$table." where id = ?";
        return Database::exec($query, ['i', $id], function($stmnt){
            $stmnt->execute();
            $stmnt->bind_result($id, $code_str, $value, $is_used, $used_ts, $created_ts);
            $promoCode = [];

            while($stmnt->fetch()){
                $promoCode[] = [
                   "id" => $id, 
                   "code_str" => $code_str, 
                   "value" => $value, 
                   "is_used" => $is_used, 
                   "used_ts" => $used_ts, 
                   "created_ts" => $created_ts
                ];
            }

            return $promoCode[0] ?? NULL;
        });
    }
}