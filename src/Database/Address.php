<?php 
class Address{
    /**
     *  user_id,
        firstname,
        lastname ,
        email, 
        address_type ,
        address_line, 
        address_line2,
        district
     */
    static private $table = "address";
    static public function createBill($address, $userId){
        $query = "insert into ".Address::$table." (user_id, firstname, lastname , email, address_type , address_line, address_line2,district) values(?,?,?,?,'bill',?,?,?)";
        $params = [
            'issssss',
            $userId,
            $address["firstName"],
            $address["lastName"],
            $address["email"],
            $address["address"],
            $address["address2"],
            $address["district"]
        ];
        return Database::exec($query, $params, function($stmt){
            $stmt->execute();
            return $stmt->insert_id;
        }) ?? NULL;
    }
    static public function createShip($address, $userId){
        
        $query = "insert into ".Address::$table." (user_id, firstname, lastname , email, address_type , address_line, address_line2,district) values(?,?,?,?,'ship',?,?,?)";
        $params = [
            'issssss',
            $userId,
            $address["firstNameShipping"],
            $address["lastNameShipping"],
            $address["emailShipping"],
            $address["addressShipping"],
            $address["address2Shipping"],
            $address["districtShipping"]
        ];
        return Database::exec($query, $params, function($stmt){
            $stmt->execute();
            return $stmt->insert_id;
        }) ?? NULL;
    }
    static public function get($id){
        $query = "select * from ".Address::$table." where id = ?";

        return Database::exec($query, ['i', $id], function($s){
            $s->execute();
            $s->bind_result($_id, $user_id, $firstname,$lastname ,$email, $address_type ,$address_line, $address_line2,$district, $created_ts);
            $address = [];
            while($s->fetch()){
                $address [] = [
                    "id"=>$_id,
                    "user_id"=> $user_id,
                    "firstname"=>$firstname,
                    "lastname"=>$lastname ,
                    "email"=>$email,
                    "address_type"=>$address_type,
                    "address_line"=> $address_line,
                    "address_line2"=>$address_line2,
                    "district"=>$district,
                    "created_ts"=>$created_ts
                ];

                return $address[0] ?? NULL;
            }
        });
    }
}