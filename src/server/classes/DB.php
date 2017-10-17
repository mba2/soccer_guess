<?php

final class DB {
   
    public function __construct(){ }
    
    public static function connect($connType){
        $iniConfig = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/soccer_guess/src/server/configuration/{$connType}-db.ini");
        
        $myPDO = new PDO("{$iniConfig['driver']}:host={$iniConfig['host']};dbname={$iniConfig['dbName']}","{$iniConfig['user']}","{$iniConfig['pass']}");         
        return $myPDO;
    }
}

?>
