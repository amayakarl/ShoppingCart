<?php
/*
id, user_id, cart_id, tax, tax_amount, subtotal, total, item_qty, is_paid, payment_id, promo_code, promo_code_value, reciept_ts, bill_address_id, ship_address_id
*/
require_once 'Database.php';
require_once 'Payment.php';
require_once 'Address.php';
require_once 'Cart.php';
class Reciept{
    static private $table = "reciept";
    
    static public function create($post, $userId, $cart, $paymentId, $billAddrId, $shipAddrId){
        $params = Reciept::genParams($post, $userId, $cart, $paymentId, $billAddrId, $shipAddrId);
        $query = "insert into ".Reciept::$table." 
            (user_id, cart_id, tax, tax_amount, subtotal, total, item_qty, is_paid, payment_id, promo_code, promo_code_value,  bill_address_id, ship_address_id)
                values (?,?,?,?,?,?,?,?,?,?,?,?,?)";

        return Database::exec($query, $params, function($s){
            $s->execute();
            return $s->insert_id;
        }) ?? NULL;
    }
    static private function genParams($post, $userId, $cart, $paymentId, $billAddrId, $shipAddrId){
        $subtotal = Reciept::getSubTotal($cart["items"]);
        $params = [];
        $params [] = 'iiddddiiisdii';
        $params [] = $userId;
        $params [] = $cart["id"];
        $params [] = 5.00;
        $params [] = $subtotal * 0.05;
        $params [] = $subtotal;
        $params [] = $subtotal + ($subtotal*0.05);
        $params [] = $cart["itemCount"];
        $params [] = 1;
        $params [] = $paymentId;

        if(isset($cart["promoCode"])){
            $params [] = $cart["promoCode"]["code_str"];
            $params [] = $cart["promoCode"]["value"];
        }
        else{
            $params [] = 'no promo';
            $params [] = 0.00;
        }

        $params[] = $billAddrId;
        $params[] = $shipAddrId;

        return $params;

 
    }
    static private function getSubTotal($items){
        $subtotal = 0.00;
        foreach($items as $item){
            $subtotal += floatval($item["item_total"]);
        }
        return $subtotal;
    }

    static public function get($rId, $userId){
        $query = "select * from ".Reciept::$table." where id = ?  and user_id = ?";
        return Database::exec($query, ['ii', $rId, $userId], function($s){
            $s->execute();
            $s->bind_result($id, $user_id, $cart_id, $tax, $tax_amount, $subtotal, $total, $item_qty, $is_paid, $payment_id, $promo_code, $promo_code_value, $reciept_ts, $bill_address_id, $ship_address_id);
            $recpt = [];

            while($s->fetch()){
                $payment = Payment::get($payment_id);
                $billAddress = Address::get($bill_address_id);
                $cart = Cart::get($cart_id);
                $shipAddress = $bill_address_id == $ship_address_id? $billAddress : Address::get($ship_address_id);
                $recpt[] = [
                    "id" => $id, 
                    "user_id" => $user_id, 
                    "cart" => $cart, 
                    "tax" => $tax, 
                    "tax_amount" => $tax_amount, 
                    "subtotal" => $subtotal, 
                    "total" => $total, 
                    "item_qty" => $item_qty, 
                    "is_paid" => $is_paid, 
                    "payment" => $payment, 
                    "promo_code" => $promo_code, 
                    "promo_code_value" => $promo_code_value, 
                    "reciept_ts" => $reciept_ts, 
                    "bill_address" => $billAddress, 
                    "ship_address" => $shipAddress
                ];

                return $recpt[0] ?? NULL;
            }
        });
    }
}