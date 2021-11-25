<?php
require_once dirname(dirname(__FILE__)).'/Database/Reciept.php';
class CheckoutController{
    static public function checkout($post, $cart, $user){
        // insert payment
        $promoCodeVal = isset($cart["promoCode"])? floatval($cart["promoCode"]["value"]) : 0.00;        
        $payment_id = Payment::create($post, CheckoutController::amountPaid($cart["items"], $promoCodeVal));
        
        if(is_null($payment_id)){
            return [
                "status"=> false,
                "message"=> "unable to process payment"
            ];
        }

        // insert user address
        $billAddressId = Address::createBill($post, $user["id"]);
        $shippAddressId = 0;
        
        if(!isset($post["isSameAddress"])){
            
            $shippAddressId = Address::createShip($post, $user["id"]);
        }
        else $shippAddressId = $billAddressId;

        // insert reciept
        $recieptId = Reciept::create($post, $cart["user_id"], $cart, $payment_id, $billAddressId, $shippAddressId);

        if(is_null($recieptId)){
            return [
                "status"=> false,
                "message"=> "unable to generate reciept."
            ];
        }
        else{
            Cart::updateCartState($cart["id"], 1);
            return [
                "status"=> true,
                "message"=> "unable to generate reciept.",
                "reciept_id" => $recieptId
            ];
        }

    }
    static private function amountPaid($cartItems, $promoVal){
        $paid = 0.00;

        foreach($cartItems as $item){
            $paid += floatval($item["item_total"]);
        }
        return $paid + ($paid * 0.05) - $promoVal;
    }

    static public function getReciept($rId, $userId){
        $receipt  = Reciept::get($rId, $userId);

        return $receipt;
    }
}
