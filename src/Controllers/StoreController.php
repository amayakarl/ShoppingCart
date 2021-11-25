<?php
require_once dirname(dirname(__FILE__)).'/Database/Sauce.php';

class StoreController{
    static public function getAllSauces(){
        return Sauce::all();
    }
}