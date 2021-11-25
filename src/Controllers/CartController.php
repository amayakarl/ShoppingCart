<?php
require_once dirname(dirname(__FILE__)).'/Database/Cart.php';
require_once dirname(dirname(__FILE__)).'/Database/CartItem.php';


class CartController{
    static public function addItemsToCart($items, $userId){
        // this will sync any cart that already exists in the database.
        //find cart with the state of 0
        // if a cart exists with the state of 0
                // then add any new items to the cart
        // else create a new cart and add the items there.
        // check if a reciept already exists for the current cart.
            // if no reciept exists then create a new reciept for the cart as unpaid
    }
    static public function addItemFor($userId, $item){
        // get active cart for user
        $cart_id = $item["cart_id"];

        if(empty($cart_id)){
            $cart_id = Cart::create($userId);

            if(is_null($cart_id)){
                return [
                    "status"=> false,
                    "message"=> "Cart not found."
                ];
            }
        }

        if(CartItem::exists($cart_id, $item["sauce_id"])){
            return [
                "status"=> false,
                "message"=> "sauce already in cart."
            ];
        }
        
        $status = CartItem::addItem($item, $cart_id);

        //$cartItem = CartItem::getNewlyAdded($cart_id, $item["sauce_id"]);

        if(!$status){
            return [
                "status"=>false,
                "message"=>"unable to add sauce to cart.",                
            ];
        }
        else{
            return [
                "status"=>true,
                "message"=>"sauce added to cart!",
                "data"=>["cart_id"=>$cart_id]
            ];
        }
        // if no active cart then create one
    }
    static public function getCartIfExists($userId){

        // check if a cart already exists with a state of 0, 
        $cart = Cart::findByUserWithState($userId);    
        
        return $cart ?? [];

            //else return false
    }
    static public function removeCartItem($userId, $cartTtemId){
        // check if the item exists, return false if it doesn't
        // delete cart_item and return true
    }

}