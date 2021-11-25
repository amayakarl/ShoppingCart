<?php
require_once 'Database.php';
require_once 'PromoCode.php';
require_once 'CartItem.php';

class Cart{
    static private $table = "cart";

    static public function findByUserWithState($userId, $state_ = 0 ){
        $query = "select * from ".Cart::$table." where user_id = ? and state = ?";
        
        $cart = Database::exec($query, ['ii', $userId, $state_], function($stmt){
            $stmt->execute();            
            $stmt->bind_result($id, $user_id, $state, $start_ts, $end_ts, $promocodeId);            
            $cart = [];    
            while($stmt->fetch()){
                $promoCode = PromoCode::getById($promocodeId);
                $items = CartItem::allFor($id);        
                $cart[] = [
                    "id" => $id,
                    "user_id" => $user_id,
                    "state" => $state,
                    "start_ts" => $start_ts,
                    "end_ts" => $end_ts,
                    "items"=>$items,
                    "itemCount"=>count($items),
                    "promoCode"=>$promoCode
                ];
            }         
            
            return $cart[0] ?? NULL;
        });

        return $cart;
    }
    static public function get($cart_id){
        $query = "select * from ".Cart::$table." where id = ?";
        
        $cart = Database::exec($query, ['i', $cart_id], function($stmt){
            $stmt->execute();            
            $stmt->bind_result($id, $user_id, $state, $start_ts, $end_ts, $promocodeId);            
            $cart = [];    
            while($stmt->fetch()){
                $promoCode = PromoCode::getById($promocodeId);
                $items = CartItem::allFor($id);        
                $cart[] = [
                    "id" => $id,
                    "user_id" => $user_id,
                    "state" => $state,
                    "start_ts" => $start_ts,
                    "end_ts" => $end_ts,
                    "items"=>$items,
                    "itemCount"=>count($items),
                    "promoCode"=>$promoCode
                ];
            }         
            
            return $cart[0] ?? NULL;
        });

        return $cart;
    }
    static public function getActiveCartIdFor($userId){
        
        $query = "select id from ".Cart::$table." where state = 0 and user_id = ?";
        $cart_id = Database::exec($query, ['i', $userId], function($stmt){
            $stmt->execute();
            $stmt->bind_result($id);
            $cart_id = [];
            while($stmt->fetch()){
                $cart_id[] = $id;
            }
            return $cart_id[0] ?? NULL;
        });
        return $cart_id;
    }


    static public function create($userId){
        $query = "insert into ".Cart::$table." (user_id, state) values(?,?)";
        
        return Database::exec($query, ['ii', $userId, 0], function($stmt){
            $stmt->execute();
            return $stmt->insert_id;           
        }) ?? NULL;
    }
    static public function updateCartState($cart_id, $state){
        $query = "update ".Cart::$table." set state = ? where id = ?";
        return Database::exec($query, ['ii', $state, $cart_id], function($stmt){
            $stmt->execute();
            $stmt->store_result();
            return $stmt->affected_rows > 0;           
        }) ;
    }
    static public function updateCartPromo($cart_id, $promoId){
        $query = "update ".Cart::$table." set promo_code_id = ? where id = ?";
        return Database::exec($query, ['ii', $promoId, $cart_id], function($stmt){
            $stmt->execute();
            $stmt->store_result();
            return $stmt->affected_rows > 0;           
        });
    }

}