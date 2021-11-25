<?php
require_once 'inc.php';
require_once dirname(dirname(__FILE__)).'/src/Controllers/PromoController.php';

function main($post, $get){
    if($post["code"] ?? false){
        echo json_encode(PromoController::validatePromo($post["code"], $post["cart_id"]));
    }
    exit;
}

main($_POST, $_GET);