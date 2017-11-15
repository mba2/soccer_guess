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
    }
    
    // PARENT METHODS
    // private function setRequestMethod() {}

    // private function getRequestMethod() {
    //     return $this->requestMethod;
    // }
    
    private function setUrlParameters() {
        return $this->urlParameters = $_GET;                                   // SET ALL POSSIBLE PARAMETERS PASSED BY URL 
    }
    
    private function setAjaxParameters() {
        return $this->ajaxParameters = file_get_contents('php://input');       // SET ALL POSSIBLE PARAMETERS PASSED BY AN AJAX CALL
    }
    
    private function setAllParameters() {
        $this->setUrlParameters();          // SET ALL POSSIBLE PARAMETERS PASSED BY URL 
        $this->setAjaxParameters();         // SET ALL POSSIBLE PARAMETERS PASSED BY AN AJAX CALL
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
        /* 
        * SINCE IT'S A 'GET' REQUEST WE'RE ONLY GONNA NEED PARAMETERS PRESENT INSIDE THE $_GET SUPERGLOBAL. SO, SET THOSE...
        */ 
        $this->setUrlParameters();   

        /*  
        *   TERMINATE THIS FUNCTION IF:
        *
        * - MORE THAN ONE PARAMETERS WERE PASSED...
        *           OR
        * - THE PARAMETER'S NAME HAS NOT THE VALUE OF 'id'...  
        */
        if( sizeof($this->urlParameters) > 1 || 
            strtolower( key($this->urlParameters) !== "id")             // CONVERTS THE GIVEN PARAMETER TO LOWERCASE AND COMPART IT TO 'id'
          )
           {
                echo json_encode(
                    array(
                        "request type" => $_SERVER['REQUEST_METHOD'], 
                        "status" => "failure", 
                        "message" => "Sorry, your request doesn't fit any valid structure. For a single 'id' try this: ?id=12. For multiple 'ids' try this: ?id=12,13,14",
                        'code' => '001'
                    )
                );
                exit();
            }

        /* 
        * WHEN THE GET REQUEST HAS NO PARAMETERS...RETRIEVE ALL TOURNAMENTS
        */
        if(empty($this->urlParameters)) {            
            try{
                $conn = (new DB())->connect();                  // STARTS A CONNECTION
                $sql_stmt = "SELECT * FROM SG_TOURNAMENTS;";    // DEFINES A SQL STATEMENT
                $query = $conn->query($sql_stmt);               // RUNS THE QUERY
                
                if($query->rowCount()) {
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode($result);
                }
                else {
                    echo json_encode(
                        array(
                            "request type" => $_SERVER['REQUEST_METHOD'], 
                            "status" => "failure", 
                            "message" => "Sorry, could not find any tournament on database",
                            'code' => '002'
                        )
                    );
                }
            } catch(PDOExeption $error) {
                echo "Message: {$error->getMessage()}<br>";
                echo "Code: {$error->getCode()}";
            }
            return;
        }

        /* 
        * WHEN THE REQUEST HAS ONLY AND PARAMETER AND IT HAS A VALUE OF 'ID'... PERFORMS THE QUERY 
        */
        $ids = $this->urlParameters['id'];    
        // TREAT THE RECEIVED STRING AND FIXES SOME POSSIBLE ERRORS : e.g REMOVE WHITESPACES, UNEXPECTED COMMAS AND SO ON...
        $ids = preg_replace('/,[\s,]+/i',',',$ids);  
        $ids = preg_replace('/,$/i','',$ids);

        try {
            $conn = (new DB())->connect();
            $selectStatment = "SELECT * FROM `SG_TOURNAMENTS` 
                                        WHERE `ID` IN ({$ids})";
            $query = $conn->query($selectStatment);
            if($query->rowCount()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            }else {
                echo json_encode(
                    array(
                        "request type" => $_SERVER['REQUEST_METHOD'], 
                        "status" => "failure", 
                        "message" => "Sorry, could not find any tournament on database",
                        'code' => '002'
                    )
                );
            }
        }catch(PDOException $error){
            echo "Message: {$error->getMessage()}<br>";
            echo "Code: {$error->getCode()}";
        }
    }

    public function response_POST() {

    }

    public function response() {
        
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':  
                // CALL A CUSTOM RESPONSE FOR MADE FOR A 'GET' REQUEST
                $this->response_GET();
                break;
            case 'POST':;
                // CALL A CUSTOM RESPONSE FOR MADE FOR A 'GET' REQUEST
                $this->response_POST();
                break;
            default:
                $this->requestMethod = "GET";
                $this->response_GET();
        }
       
    }
    // PARENT METHODS
    
} 