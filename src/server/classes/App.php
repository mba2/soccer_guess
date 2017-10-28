<?php

class App {
    protected $PATH_CLASSES = "ae";
    
    protected $ENV = "development";

    public function __construct() {
        echo "<pre>";
        // print_r($_SERVER['DOCUMENT_ROOT']);
        print_r($_SERVER);
        echo "</pre>";
    }
}


?>
