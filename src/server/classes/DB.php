<?php
require_once("App.php");

final class DB extends App{
   
    public function __construct(){ }
    
    public function connect() {
        $iniConfig = parse_ini_file("../configuration/{$this->ENV}-db.ini");

        print_r($iniConfig);

        try {
            $myPDO = new PDO("{$iniConfig['driver']}:host={$iniConfig['host']};dbname={$iniConfig['dbName']}","{$iniConfig['userName']}","{$iniConfig['pass']}");         
            return $myPDO;
        } catch(PDOException $pdoError){
            echo "Message: {$pdoError->getMessage()}<br>";
            echo "Code: {$pdoError->getCode()}";
        }
        
    }
}

$DB = new DB();
print_r($DB->connect());

?>
