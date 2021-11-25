<?php

/***
 * Change this to the base url of your server, 
 *      for example if you are using xampp, you would use this url as the
 *      base path localhost/foldername
 * Do not add a trailing "/"
 * This Global is used to link to css files, javascript files
 *      images as well as other pages.
 */
$BASE_PATH = ''; 

$isLoggedIn = $_SESSION['isLoggedIn'] ?? false;

$_USER = $_SESSION["user"] ?? NULL;

function ld_($obj, $val){
    return is_null($obj)?"": $obj[$val] ?? "";
}

function url($path){ // used to append the Base path global to the resource path
    global $BASE_PATH;
    return $BASE_PATH.$path;
}

function requiresLogin($page){// will check if the page requires to be logged in first.
    $pagesRequireLogin = ["checkout","reciept", "store", "logout", "cart"];
    return in_array($page, $pagesRequireLogin);
}

function isInCart($sauceId, $cart){
    $is = false;

    if(!isset($cart["id"])){
        return $is;
    }

    foreach($cart["items"] as $item){
        if($item["sauce_id"] == $sauceId) return true;
    }
    return $is;
    
}