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
            // STARTS A CONNECTION
            $conn = (new DB())->connect();                  
            // DEFINES A SQL STATEMENT
            $sql_stmt = "SELECT 
                            `ID`,
                            `NAME`,
                            `FLAG` FROM `SG_TOURNAMENTS`
                             WHERE `ACTIVE` != '0';";    
            // RUNS THE QUERY
            $query = $conn->query($sql_stmt);               
            
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
        $givenInfo = json_decode( $this->urlParameters['info'],true );
        $givenInfo = array_change_key_case($givenInfo,CASE_UPPER);

        $ids = implode(",",$givenInfo['ID']);    //CLEAN THE ARRAY OF IDS PASSED BY THE USER
        
        // ADDITIONAL OPTIONS
        // $getTeams = $givenInfo->getTeams;
        // if($getTeams) {
        //     $sql_allTournamentsWithTeams = "SEle.....";
        // } 

        // MOUNT THE SQL STATEMENT
        $sql_allTournaments = "SELECT 
                                    `ID`,
                                    `NAME`,
                                    `FLAG` FROM `SG_TOURNAMENTS`
                                    WHERE 
                                        `ID` IN ({$ids})
                                        AND `ACTIVE` != 0";

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

    public function addTournaments() {
        $tournamentInfo = json_decode( $this->urlParameters['info'],true );
        
        try {
            $conn = (new DB())->connect();

            // MOUNT THE SQL STATEMENT
            $sql_insertTournaments = $conn->prepare("INSERT INTO `SG_TOURNAMENTS` 
                                                        (`NAME`,`FLAG`)
                                                        VALUES (:tournamentName, :flag)");

            $sql_insertTournaments->bindParam(':tournamentName', $tournamentName);
            $sql_insertTournaments->bindParam(':flag', $flag);            
            
            foreach ($tournamentInfo as $tournament) {
                $tournament = array_change_key_case($tournament,CASE_UPPER);
                $tournamentName = $tournament['NAME'];
                $flag = $tournament['FLAG'];
                
                $sql_insertTournaments->execute();    
            }
            echo json_encode(
                array(
                    "request type" => $_SERVER['REQUEST_METHOD'], 
                    "status" => "success", 
                    "message" => "Tournament(s) successfully inserted. Plis bilivi mi!",
                    'code' => '100'
                )
            );


        }catch(PDOException $error){
            echo "Message: {$error->getMessage()}<br>";
            echo "Code: {$error->getCode()}";
        }
    }

    public function updateTournaments() {
        $rawInfo = json_decode( $this->urlParameters['info'], true ); // DECODE THE JSON INTO AN ARRAY
        
        try{        
            $conn = (new DB())->connect();   
        
            foreach($rawInfo as &$tournament) {
                /**
                 * CONVERTS ALL KEYS TO UPPERCASE. 
                 * THE REASON IS THAT ALL COLUMNS NAMES ON DATABASE ARE CURRENTLY IN UPPERCASE
                */   
                $tournament = array_change_key_case($tournament,CASE_UPPER);        
                /**
                 * RETURNS ALL PROPERTIES OF THE ARRAY GIVEN BY THE USER BUT THE 'ID' PROPERTY. T
                 * THIS NEW ARRAY WILL CONTAING ALL FIELDS AND ITS VALUES THAT SHOULD BE UPDATED. 
                */  
                $dataToUpdate = array_slice($tournament,1);

                $tournament["prepareParams"] = array_slice($tournament,0);
            
                /**
                 * GERERATES SQL SYNTAX FORMAT AND STORE THEM INTO THE TEMPORARY ARRAY
                 */                        
                $temp = [];      // A TEMPORARY ARRAY TO STORE THE DATA THAT MUST BE UPDATED IN A SQL SYNTAX FORMAT   
                
                foreach($dataToUpdate as $field => $key) {
                    $temp[] = " $field = :{$field}"; 
                }                
                /**
                 * - CONVERTS THE ARRAY INTO A STRING, WITH ITS ITEMS SEPARETED BY COMMA 
                 * - REMOVES A WHITESPACE AT THE STRING'S START
                */ 
                $tournament["placeholders"] = ltrim(implode(",",$temp));  
                /**
                 * - CREATES A STRING TO BE SET AS CONTENT OF A PREPARE STATEMENT
                */ 
                $tournament["prepareStmt"] = "UPDATE `SG_TOURNAMENTS` SET " . $tournament["placeholders"] . " WHERE ID = :ID";
                                
                $prepareUpdate = $conn->prepare($tournament["prepareStmt"]); // SET THE PREPARE STATEMENT
                $prepareUpdate->execute($tournament["prepareParams"]);       // EXECUTE IT

            }
            $this->s_update(); // OUTPUT THE SUCCESS RESULT   
        }catch(PDOException $error){
            echo "Message: {$error->getMessage()}";
            echo "\nCode: {$error->getCode()}";
        }
    }

    public function removeTournaments() {
        $teamsInfo = json_decode( $this->urlParameters['info'], true ); // DECODE THE JSON INTO AN ARRAY
        $teamsInfo = array_change_key_case($teamsInfo,CASE_UPPER);      // CONVERT THE KEYS TO UPPERCASE

        /**
         * BUILDS THE PARAMETERS STRUCUTURE TO BE USED IN A PREPARE STATEMENT
        */
        $ids = $teamsInfo['ID'];                                        // CONVERT THE GIVEN ID'S TO A SQL SYNTAX FORMAT
        $placeholders = implode(",", array_fill(0,count($ids),"?") );   // PREPARE PLACEHOLDERS

        try {
            $conn = (new DB())->connect(); 
            $prepareDelete = $conn->prepare("UPDATE `SG_TOURNAMENTS` SET  ACTIVE = 0 
                                                                    WHERE ID IN ( {$placeholders} ) ");
            $prepareDelete->execute($ids);
            $this->s_remove();

        }catch(PDOException $error) {
            echo "Message: {$error->getMessage()}<br>";
            echo "Code: {$error->getCode()}";
        } 

        // $targetID = $teamsInfo['ID'];                     // STORE THE GIVEN ID
        // $raw_dataToUpdate = array_slice($teamsInfo,1);    // STORE THE REST OF THE GIVEN DATA

        // // TRANSFORM THE RAW DATA INTO A FORMAT THAT CAN BE INCLUDE IN AN UPDATE SQL STATEMENT
        // $sql_dataToUpdate = "";
        // foreach($raw_dataToUpdate as $info => $key) {
        //     $sql_dataToUpdate .= " {$info} = '{$key}',";
        // }
        // // NOW THAT YOUR STRING IS FORMATED...REMOVE THE LAST COMMA
        // $sql_dataToUpdate = preg_replace('/,$/im','',$sql_dataToUpdate);

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
                'code' => '002'
            )
        ); 
    }

    public function e_noTournamentsFound() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "Sorry, could not find any tournament on database",
                'code' => '003'
            )
        );
    }

    public function e_updates() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "No record updated. Check if the 'identification' that was passed is correct OR if the field's content you want to update are diffent than the previous ones.",
                'code' => '004'
            )
        );
        exit();
    }


    // RESPONSES USERS FEEDBACK
    public function s_update() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "success", 
                "message" => "Tournament(s) successfully updated. Plis bilivi mi!",
                'code' => '101'
            )
        );
    }

    public function s_delete() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "success", 
                "message" => "Tournament(s) successfully deleted. Plis bilivi mi!",
                'code' => '102'
            )
        );
    }

    public function s_remove() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "success", 
                "message" => "Tournament(s) successfully removed. Plis bilivi mi!",
                'code' => '103'
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
         /* 
        *  SET THE POSSIBLE GIVEN PARAMETERS INTO THE OBJECT...
        */ 
        $this->setAllParameters();   

        $this->addTournaments();
    }

    public function response_PATCH() {
         /* 
        *  SET THE POSSIBLE GIVEN PARAMETERS INTO THE OBJECT...
        */ 
        $this->setAllParameters();   

        $this->updateTournaments();
    }

    public function response_DELETE() {
        /* 
       *  SET THE POSSIBLE GIVEN PARAMETERS INTO THE OBJECT...
       */ 
       $this->setUrlParameters();   

       $this->removeTournaments();
   }

    public function response() {
        
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':  
                // CALL A CUSTOM RESPONSE FOR MADE FOR A 'GET' REQUEST
                $this->response_GET();
                break;
            case 'POST':;
                // CALL A CUSTOM RESPONSE FOR MADE FOR A 'POST' REQUEST
                $this->response_POST();
                break;
            case 'PATCH':;
                // CALL A CUSTOM RESPONSE FOR MADE FOR A 'PATCH' REQUEST
                $this->response_PATCH();
                break;
            case 'DELETE':;
                // CALL A CUSTOM RESPONSE FOR MADE FOR A 'DELETE' REQUEST
                $this->response_DELETE();
                break;
            default:
                $this->requestMethod = "GET";
                $this->response_GET();
        }
       
    }
    // PARENT METHODS
    
} 