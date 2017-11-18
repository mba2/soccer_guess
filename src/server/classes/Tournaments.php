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

    /**
     *  @param  
     *      $wu (boolean) : indicates if the user needs to be warned that it haven't passed any parameter ($wu means 'warnUser')
     */
    private function emptyParameters($wu) {
        if( empty( $this->urlParameters) && empty($this->ajaxParameters) ) {
            if($wu) $this->e_emptyParameters();     // WARN THE USER THAT IT HAVEN'T PASSED ANY PARAMETER          
            return true;                            // INDICATES THAT NONE PARAMETERS HAVE BEEN PASSED
        }
        return false; // INDICATES THAT AT LEAST ONE PARAMETER HAS BEEN PASSED
    }

    public function getAllTournaments() {
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
        exit();
    }

    public function getSpecificTournaments() {
        $givenInfo = json_decode( $this->urlParameters['info'] );

        
        $ids = implode(",",$givenInfo->ids);    //CLEAN THE ARRAY OF IDS PASSED BY THE USER
        
        // ADDITIONAL OPTIONS
        // $getTeams = $givenInfo->getTeams;
        // if($getTeams) {
        //     $sql_allTournamentsWithTeams = "SEle.....";
        // } 

        // MOUNT THE SQL STATEMENT
        $sql_allTournaments = "SELECT * FROM `SG_TOURNAMENTS`
                                        WHERE `ID` IN ({$ids})";

        try {
            $conn = (new DB())->connect();
            $query = $conn->query($sql_allTournaments);
            if($query->rowCount()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
            }else {
                    $this->e_noTournamentsFound();
            }
        }catch(PDOException $error){
            echo "Message: {$error->getMessage()}<br>";
            echo "Code: {$error->getCode()}";
        }
    }

    // ERRORS
    public function e_invalidStructure() {
        echo json_encode(
                array(
                    "request type" => $_SERVER['REQUEST_METHOD'], 
                    "status" => "failure", 
                    "message" => "Sorry, your request doesn't fit any valid structure. Try something like this: ?info={ids:'12,13,14',...}",
                    'code' => '001'
                )
        );
        exit();
    }

    public function e_emptyParameters() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "You have not passed any parameter",
                'code' => '001'
            )
        ); 
    }

    public function e_noTournamentsFound() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "Sorry, could not find any tournament on database",
                'code' => '002'
            )
        );
    }



    // RESPONSES


    public function response_GET() {
        /* 
        * SINCE IT'S A 'GET' REQUEST WE'RE ONLY GONNA NEED PARAMETERS PRESENT INSIDE THE $_GET SUPERGLOBAL. SO, SET THOSE...
        */ 
        $this->setUrlParameters();   
    
        /*  
        *   TERMINATE THIS FUNCTION IF:        *
        *       - MORE THAN ONE PARAMETERS WERE PASSED... 
        *       - AN ARGUMENT NAMED 'info' WASN'T PASSED... 
        */
        if( sizeof($this->urlParameters) > 1) {
            if( !key_exists("info",$this->urlParameters)) $this->e_invalidStructure();
        } 

        /* 
         * WHEN THE GET REQUEST HAS NO PARAMETERS...RETRIEVE ALL TOURNAMENTS
        */
        if( $this->emptyParameters(false) ) $this->getAllTournaments();

        /* 
         * WHEN THE GET REQUEST HA PARAMETERS AND THEY'RE VALID... RETRIEVE THE REQUIRED TOURNAMENTS
        */
        $this->getSpecificTournaments(); 


        // TREAT THE RECEIVED STRING AND FIXES SOME POSSIBLE ERRORS : e.g REMOVE WHITESPACES, UNEXPECTED COMMAS AND SO ON...
        // $ids = preg_replace('/,[\s,]+/i',',',$ids);  
        // $ids = preg_replace('/,$/i','',$ids);
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