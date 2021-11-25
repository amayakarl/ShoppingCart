<?php
require dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/src/Database/Auth.php';

$API_KEY = getallheaders()["X-TONGUE-SPICE-API-KEY"]??'';
$key = Auth::doesKeyExist($API_KEY);

if(!isset($key["id"])){
    header('HTTP/ 401 access denied');
    exit;
}
else{
    header('Content-Type: application/json');
}