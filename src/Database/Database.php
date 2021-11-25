<?php
class Database{   
    static public function exec($sql_query, $params, $callback){
        $sql = Database::db();
        $stmt = $sql->prepare($sql_query);
        Database::log($sql_query, $params);
        if(count($params) > 0)
            $stmt->bind_param(...$params);
        $data = $callback($stmt);
        $sql->close();
        $stmt->close();
        return $data;
    }
    static public function db(){
        $db = [
            "host"=>'localhost',
            "port"=>3306,
            "database"=>"tongue_spice",
            "user"=>"root",
            "password"=>""
        ];
        return new \mysqli($db["host"], $db["user"], $db["password"], $db["database"], $db["port"]);
    }
    static private function log($query, $params){
        $myfile = fopen("sql.log", "a");
        $txt = $query.' With Values: '.json_encode($params).PHP_EOL;
        fwrite($myfile, $txt);
        fclose($myfile);
    }
}
