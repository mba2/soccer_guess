<?php

require_once("App.php");
require_once("DB.php");


 class Tournaments extends App {
     
    private $requiredParameters = array();
    private $missingParameters = array();
    
    private $requestMethod;
    private $ajaxParameters;
    private $urlParameters;
    
    public function __construct() { 
        echo "<pre>Starting the " . __CLASS__ . " class!! <br><br>";
        $this->getAllParameters();
    }
    
    // PARENT METHODS
    // private function setRequestMethod() {}

    private function getRequestMethod() {
        return $this->requestMethod;
    }
    
    private function getParameters() {
        return $this->urlParameters = $_GET;                                   // GET ALL POSSIBLE PARAMETERS PASSED BY URL 
    }
    
    private function getAjaxParameters() {
        return $this->ajaxParameters = file_get_contents('php://input');       // GET ALL POSSIBLE PARAMETERS PASSED BY AN AJAX CALL
    }
    
    private function getAllParameters() {
        $this->urlParameters = $_GET;                                   // GET ALL POSSIBLE PARAMETERS PASSED BY URL 
        $this->ajaxParameters = file_get_contents('php://input');       // GET ALL POSSIBLE PARAMETERS PASSED BY AN AJAX CALL
    }

    private function checkRequiredParemetes() {
        // RETRIEVE ALL  MISSING REQUIRED PARAMETERS
    }

    private function checkEmptyParameters() {
        // echo "without encode"; 
        // print_r($this->ajaxParameters);
        // echo gettype($this->ajaxParameters);
        // echo "<br>\n\n<br>";
        // echo "with encode"; 
        // print_r(json_encode($this->ajaxParameters));

        print_r('empty($this->ajaxParameters) ===> ' . empty($this->ajaxParameters));
        print_r(' print_r(($this->ajaxParameters) ===> ' . $this->ajaxParameters);


        // echo empty($this->ajaxParameters);

        if( empty( $this->urlParameters) && empty($this->ajaxParameters) ){
            echo json_encode(
                array(
                    "request type" => $_SERVER['REQUEST_METHOD'], 
                    "status" => "failure", 
                    "message" => "You have not passed any parameter",
                    'code' => '001'
                )
            );
            exit();
        }
    }

    public function response_GET() {
        echo "Starting a GET response...<br><br>";
        
        // WHEN THE GET REQUEST HAS NO PARAMETERS...RETRIEVE ALL TOURNAMENTS
        if(empty($this->urlParameter)) {
            echo "Show all tournaments";
            return;
        }

        // CHECK FOR AN 'ID' PARAMETER
        
        
    }
    public function response() {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->requestMethod = "GET";
                $this->response_GET();
                break;
            case 'POST':;
                $this->requestMethod = "POST";
                break;
            default:
                $this->requestMethod = "GET";
                $this->response_GET();
        }
       
    }
    // PARENT METHODS
    
} 