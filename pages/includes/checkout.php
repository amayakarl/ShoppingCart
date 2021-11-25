<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once 'src/Controllers/CheckoutController.php';

    $reciept = CheckoutController::checkout($_POST, $cart, $_USER);
    if($reciept["status"]){
        header('Location: '.url('/?page=reciept').'&reciept='.$reciept["reciept_id"]);
        die();
    }
    else{
        $_SESSION["checkout_error"] = $reciept["message"];
        header('Location: '.url('/?page=checkout'));
        die();
    }
}