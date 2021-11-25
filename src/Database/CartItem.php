<?php
require_once 'Database.php';


class CartItem{
    static private $table = "cart_item";

    static public function allFor($cartId){
        $query = "  select cart_item.id, sauce_id, qty, item_total, title, price, img_path 
                    from ".CartItem::$table."
                        inner join sauce on cart_item.sauce_id = sauce.id 
                    where cart_id = ?";
        $cartItems = Database::exec($query, ['i', $cartId], function($stmt){
            $stmt->execute();
            $stmt->bind_result($id, $sauce_id, $qty, $item_total, $title, $price, $img_path);
            $items = [];
            while($stmt->fetch()){
                $items[] = [
                    'id' => $id,
                    'sauce_id'=> $sauce_id,
                    'qty'=> $qty,
                    'item_total'=> $item_total,
                    'title'=> $title,
                    'price'=> $price,
                    'img_path'=> $img_path
                ];
            }
            return $items;           
        });
        return $cartItems;
    }
    static public function getNewlyAdded($cart_id, $sauce_id){
        $query = "  select cart_item.id, sauce_id, qty, item_total, title, price, img_path 
                    from ".CartItem::$table." 
                        inner join sauce on cart_item.sauce_id = sauce.id 
                    where cart_id = ? and sauce_id = ?";
        $cartItem = Database::exec($query, ['ii', $cart_id, $sauce_id], function($stmt){
            $stmt->execute();
            $stmt->bind_result($id, $sauce_id, $qty, $item_total, $title, $price, $img_path);
            $items = [];
            while($stmt->fetch()){
                $items[] = [
                    'id' => $id,
                    'sauce_id'=> $sauce_id,
                    'qty'=> $qty,
                    'item_total'=> $item_total,
                    'title'=> $title,
                    'price'=> $price,
                    'img_path'=> $img_path
                ];
            }
            return $items[0] ?? NULL;           
        });
        return $cartItem;
    }
    static public function addItem($item, $cart_id){
        $query = "insert into ".CartItem::$table." (sauce_id, qty, item_total, cart_id) values(?,?,?,?)";
        $params = ['iidi', $item["sauce_id"], $item["qty"], $item["item_total"], $cart_id];

        $rows_affected = Database::exec($query, $params, function($stmt){
            $stmt->execute();
            $stmt->store_result();
            return $stmt->affected_rows;
        });

        return $rows_affected > 0;
    }
    static public function remove($cartId, $itemId){
        $query = "delete from ".CartItem::$table." where cart_id = ? and id = ?";
        return Database::exec($query, ['ii', $cartId, $itemId], function($stmnt){
            $stmnt->execute();
            $stmnt->store_result();
            return $stmnt->affected_rows > 0;
        });
    }
    static public function removeAllForCart($cartId){
        $query = "delete from ".CartItem::$table." where cart_id = ?";
        return Database::exec($query, ['i', $cartId], function($stmnt){
            $stmnt->execute();
            $stmnt->store_result();
            return $stmnt->affected_rows > 0;
        });
    }
    static public function exists($cart_id, $sauce_id){
        $query = "select count(id) from ".CartItem::$table." where cart_id = ? and sauce_id = ?";
        return Database::exec($query, ['ii', $cart_id, $sauce_id], function($stmnt){
            $stmnt->execute();

            $stmnt->bind_result($count);
            $cnt = 0;
            while($stmnt->fetch()){
                $cnt = $count;
            }

            return $cnt > 0;
        });
    }
    static public function update($cartItem){
        $query = "update ".CartItem::$table." 
            set qty = ?, item_total = ? 
            where id = ? and cart_id = ?";
        $params = ['idii', $cartItem["qty"], $cartItem['charge'], $cartItem["item_id"], $cartItem["cart_id"]];
        return Database::exec($query, $params, function($stmnt){
            $stmnt->execute();
            $stmnt->store_result();         
            return $stmnt->affected_rows > 0;
        });
    }
}