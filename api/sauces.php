<?php
use App\Database\Sauce;
require_once 'inc.php';

function main($post, $get){
   
    echo json_encode(Sauce::all());
    exit;
}

main($_POST, $_GET);