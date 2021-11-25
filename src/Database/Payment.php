<?php
require_once 'Database.php';
class Payment{
    static private $table = "payment";
    static public function create($payment, $amountPaid){
        $description = '';    

        if($payment["paymentMethod"] != "pp"){
            $description = $payment["cc_name"].' '.$payment["cc_number"].' '.$payment["cc_exp"].' '.$payment["cc_cvv"];
        }        
        
        $query = "insert into ".Payment::$table." (amount_paid, payment_type, description) values(?,?,?)";
        return Database::exec($query, ['dss', $amountPaid, $payment["paymentMethod"], $description], function($stmt){
            $stmt->execute();
            return $stmt->insert_id;
            
        }) ?? NULL;
    }
    static public function get($id){
        $query = "select * from ".Payment::$table." where id = ?";
        return Database::exec($query, ['i', $id], function($s){
            $s->execute();
            $s->bind_result($_id, $amount_paid, $payment_type, $description, $payment_ts);
            $payment = [];
            while($s->fetch()){
                $payment[] = [
                    "id"=>$_id, 
                    "amount_paid"=>$amount_paid, 
                    "payment_type"=>$payment_type, 
                    "description"=>$description, 
                    "payment_ts"=>$payment_ts
                ];
            }
            return $payment[0] ?? NULL;
        });
    }
}