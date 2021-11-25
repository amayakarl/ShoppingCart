<?php
require_once 'Database.php';


class Sauce{
    static $table = "sauce";
    static public function all(){
        $query = "select * from ".Sauce::$table;
        $sauces = Database::exec($query, [], function($stmt){
            $stmt->execute();            
            $stmt->bind_result($id, $brand, $title, $price, $description, $imgPath, $ingredients, $added_ts);            
            $sauces = [];
            
            while($stmt->fetch()){
                $sauces[] = [
                    "id"=>$id,
                    "brand"=>$brand,
                    "title"=>$title,
                    "price"=>$price,
                    "description"=>$description,
                    "img"=>$imgPath,
                    "ingredients"=>$ingredients
                ];
            }
            return $sauces;
        });
        return $sauces;
    }
}