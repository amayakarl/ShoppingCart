<?php

require_once 'inc.php';
require_once dirname(dirname(__FILE__)).'/src/Controllers/CartController.php';



function handle_post($post){
    global $key;
    $userId = $key["user_id"];

    if(!is_null($userId)){
        $cart_item = CartController::addItemFor($userId, $post);
        echo json_encode($cart_item);
    }
    else{
        echo '{"status":false, "message":"Could not add '.$post["sauce_title"].'"}';
    }
    die();
}
function handle_get($get){
    global $key;
    $userId = $key["user_id"];

    if($userId ?? false){
        $cart = Cart::findByUserWithState($userId);
        if(is_null($cart)){
            echo '{"status":false, "message":"no data"}';
        }
        else{
            echo json_encode([
                "status" => true,
                "data" => $cart
            ]);
        }
    }
    else{
        echo '[]';
    }
    die();
}
function handle_delete($get){
    
    if(isset($get["item_id"])){

        if(CartItem::remove($get["cart_id"], $get["item_id"]))
            echo '{"status":true, "message":"item removed"}';
        else{
            echo '{"status":false, "message":"Could not remove."}';
        }
    }
    else{
        if(CartItem::removeAllForCart($get["cart_id"])){

            echo '{"status":true, "message":"items removed"}';
        }
        else{
            echo '{"status":false, "message":"items not removed"}';

        }
    }

}
function handle_update($get){
    if(CartItem::update($get)){

        echo '{"status":true, "message":"item saved!"}';
    }
    else{
        echo '{"status":false, "message":"item not saved."}';

    }
}
function main($get, $post){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        handle_post($post);
    }
    else if($_SERVER["REQUEST_METHOD"] == "GET"){
        handle_get($get);
    }
    else if($_SERVER["REQUEST_METHOD"] == "DELETE"){
        handle_delete($get);
    }
    else if($_SERVER["REQUEST_METHOD"] == "PUT"){
        handle_update($get);
    }
}

main($_GET, $_POST);