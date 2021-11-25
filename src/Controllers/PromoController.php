<?php
require_once dirname(dirname(__FILE__)).'/Database/PromoCode.php';
require_once dirname(dirname(__FILE__)).'/Database/Cart.php';

class PromoController{
    static public function validatePromo($code, $cart_id){
        $promoCode = PromoCode::useCode($code);

        if(is_null($promoCode)){
            return [
                "status"=> false,
                "message"=> "Promo code not valid."
            ];
        }
        else{
            if(Cart::updateCartPromo($cart_id, $promoCode["id"]))
                return [

                    "status"=> true,
                    "message"=> "Promo code applied!",
                    "promoCode"=>$promoCode
                ];
            else return [
                "status"=> true,
                "message"=> "Couldn't add code to cart.",
                "promoCode"=>$promoCode
            ];
        }
    } 
}