<?php

require_once("App.php");
require_once("DB.php");

class Groups extends App {
    private $requiredParameters = array();
    private $missingParameters = array();
    
    private $requestMethod;
    private $ajaxParameters;
    private $urlParameters;

    private $selectStmt = "";
    
    public function __construct() { }

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

    public function setSelectStmt() {
        // IF AN ARGUMENT NAMED 'SHOWTEAMS' WAS PASSED..
        if (array_key_exists("SHOWTEAMS", $this->urlParameters))
        
        {
            $this->selectStmt = "SELECT 
                                    G.`ID`,
                                    G.`NAME`,
                                    T.`NAME` AS `BELONGS TO`
                                    FROM `SG_GROUPS` AS G
                                    INNER JOIN `SG_TOURNAMENTS` AS T 
                                    ON G.`TOURNAMENT_ID` = T.`ID`
                                    WHERE G.`ACTIVE` != 0
                                    ORDER BY(G.`ID`)"; 
        }
        else 
        {
                                // $this->selectStmt =  "SELECT `ID`,`NAME` FROM `SG_GROUPS`
                                //                         WHERE `ID` IN ({$placeholders})
                                //                         AND `ACTIVE` != '0';"; 

        } 
    }

    function retrieveAllTeams() {
        // SQL STATEMENT TO RETRIEVE ALL ITEMS, SEPARETED BY GROUPS
        $allTeamsInGroupsQuery = "SELECT  *
                                    FROM sg_group_formation
                                    LEFT JOIN sg_teams USING(TEAM_ID)
                                    LEFT JOIN sg_groups USING(GROUP_ID)";
        
        $conn           = (new DB())->connect();                // CONNECTS TO THE DATABASE

        
        $query          = $conn->query($allTeamsInGroupsQuery); // RUNS THE QUERY
        $allTeamsInfo   = $query->fetchAll(PDO::FETCH_ASSOC);   // STORES THE QUERYY AS A MULTIDIMENSION ARRAY
        
        $teamsByGroup   = 4;
        $groups         = array();
        $group          = array();
        $groupID        = null;
        $groupName      = null;
        
        foreach($allTeamsInfo as $index => $teamInfo) {

            // IF THERE'RE ALREADY FOUR TEAMS IN A GROUP
            if($index !== 0 && ( $index % $teamsByGroup === 0) ) {           
                // ADDS THIS GROUP INTO AN ARRAY CONTAINING ALL OF THEM
                $groups[] = array(
                            "grupID"    => $groupID,
                            "grupName"  => $groupName,
                            "teams"     => $group
                        ); 
                $group = null;       // RESETS THIS VARIABLE..IT HOLDS ALL TEAM OF A CURRENT GROUP
            }
            // ADD A TEAM'S INFORMATION INTO ITS GROUP
            $group[] = $teamInfo;

            $groupID    = $teamInfo['GROUP_ID'];
            $groupName  = $teamInfo['GROUP_NAME'];
        }
    
        // ADDS THE LAST GROUP INTO AN ARRAY CONTAINING ALL OF THEM
        $groups[] = array(
            "grupID"    => $groupID,
            "grupName"  => $groupName,
            "teams"     => $group
        ); 
        
        echo json_encode($groups);
    }   

    public function getAllGroups() {
        try{
            $conn = (new DB())->connect();  // STARTS A CONNECTION

            // DEFINES A SQL STATEMENT
            
            if (array_key_exists("showTeams", $this->urlParameters)) 
            {
                $this->selectStmt =  
                    "SELECT 
                    `TOR`.`ID` AS 'tournamentId',
                    `TOR`.`NAME` AS 'tournament',
                    `G`.`ID` AS 'groupId',
                    `G`.`NAME` AS 'group',
                    `TE`.`FULLNAME` AS 'team',
                    `TE`.`SHORTNAME` AS 'shortname',
                    `TE`.`FLAG` AS 'flag'
                        FROM `SG_GROUP_FORMATIONS`AS `F`
                        INNER JOIN `SG_GROUPS` AS `G` ON(`G`.`ID` = `F`.`GROUP_ID`)
                        INNER JOIN `SG_TOURNAMENTS` AS `TOR` ON(`G`.`TOURNAMENT_ID` = `TOR`.`ID`)
                        INNER JOIN `SG_TEAMS` AS `TE` ON(`TE`.`ID` = `F`.`TEAM_ID`)";
            }
            else 
            {
                $this->selectStmt =  
                    "SELECT 
                        G.`ID`,
                        G.`NAME`,
                        T.`NAME` AS `BELONGS TO`
                        FROM `SG_GROUPS` AS G
                        INNER JOIN `SG_TOURNAMENTS` AS T ON G.`TOURNAMENT_ID` = T.`ID`";
            }

            $this->selectStmt .= "WHERE T.`ACTIVE` != '0';"; 

            $prepareSelect = $conn->prepare($this->selectStmt);  // PREPARES THE QUERY
            
            if($prepareSelect->execute() && $prepareSelect->rowCount()) {
                $result = $prepareSelect->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            else  $this->e_noGroupsFound();
        } catch(PDOExeption $error) {
            $stateError = $error->errorInfo[0];
            $codeError = $error->errorInfo[1];
            $specificMessage = $error->errorInfo[2];            
            echo $genericMessage = $error->getMessage();
        }
        exit();
    }

    public function getSpecificGroups() {
        $teamsInfo = json_decode( $this->urlParameters['info'],true );
        $teamsInfo = array_change_key_case($teamsInfo,CASE_UPPER);

        try {
            
            $conn = (new DB())->connect();  // START A CONNECTION
            
            $ids = $teamsInfo['ID'];   // STORE THE ARRAY CONTAINING ALL DESIRED ID'S  
            
            $placeholders = implode(",", array_fill(0, count($ids),"?")); // CREATE THE PLACEHOLDERS TO BE USED WITH ->bindValue()

             // DEFINES A SQL STATEMENT
            if (array_key_exists("showTeams", $this->urlParameters)) 
            {
                $this->selectStmt =  
                    "SELECT 
                    `TOR`.`ID` AS 'tournamentId',
                    `TOR`.`NAME` AS 'tournament',
                    `G`.`ID` AS 'groupId',
                    `G`.`NAME` AS 'group',
                    `TE`.`FULLNAME` AS 'team',
                    `TE`.`SHORTNAME` AS 'shortname',
                    `TE`.`FLAG` AS 'flag'
                        FROM `SG_GROUP_FORMATIONS`AS `F`
                        INNER JOIN `SG_GROUPS` AS `G` ON(`G`.`ID` = `F`.`GROUP_ID`)
                        INNER JOIN `SG_TOURNAMENTS` AS `TOR` ON(`G`.`TOURNAMENT_ID` = `TOR`.`ID`)
                        INNER JOIN `SG_TEAMS` AS `TE` ON(`TE`.`ID` = `F`.`TEAM_ID`)
                        WHERE G.`ID` IN ({$placeholders})";
            }
            else 
            {
                $this->selectStmt =  
                    "SELECT 
                        G.`ID`,
                        G.`NAME`,
                        T.`NAME` AS `BELONGS TO`
                        FROM `SG_GROUPS` AS G
                        INNER JOIN `SG_TOURNAMENTS` AS T ON G.`TOURNAMENT_ID` = T.`ID`
                            WHERE G.`ID` IN ({$placeholders})";
            }
            
            $prepareSelect = $conn->prepare($this->selectStmt);  // PREPARES THE QUERY
       
            // EXECUTE IT AND IF THE QUERY RAN SUCCESSFULLY AND AT LEAST ONE RESULT WAS RETURNED...           
            if($prepareSelect->execute($ids) && $prepareSelect->rowCount()) {

                $result = $prepareSelect->fetchAll(PDO::FETCH_ASSOC);     // FETCH THE RESULT IN AN ASSOCIATIVE ARRAY
       
                echo json_encode($result);  // AND PRINT IT
            }
            else $this->e_noGroupsFound();
        }
        catch(PDOException $error){
            $stateError = $error->errorInfo[0];
            $codeError = $error->errorInfo[1];
            $specificMessage = $error->errorInfo[2];            
            echo $genericMessage = $error->getMessage();
        }
    }

    public function addGroups() {
        $groupsInfo = json_decode( $this->urlParameters['info'],true );
        
        try {
            $conn = (new DB())->connect();

            // CREATE A SELECT STMT, SEARCHING FOR TOURNAMENTS BASED ON THE ID'S GIVEN BY THE USER
            // AND THOSE TOURNAMENTS MUST EXIST (BE ACTIVE) 
            $sql_selectStmt = 
                "SELECT *
                    FROM `SG_TOURNAMENTS` AS `TOR`
                    WHERE 
                        `TOR`.`ACTIVE` != '0' AND
                        `ID` = :tournamentId";
            $sql_selectPreapare = $conn->prepare($sql_selectStmt); // PREPARE THE QUERY

            /**
             * THIS ITERATION WILL SPLIT THE TOURNAMENTS' INFO IN TWO PARTS :
             *  -- A GROUP THAT WAS SUPPOSED TO BE INSERT IN A TOURNAMENT DOESN'T EXIST WON'T BE INSERTED IN THE DATABASE
             *  -- A GROUP THAT WAS SUPPOSED TO BE INSERT IN A TOURNAMENT EXISTS WILL BE INSERTED IN THE DATABASE
            */ 
            foreach ($groupsInfo as $group) {                
                $groupName = $group['groupName'];
                $tournamentId = $group['tournamentId'];
                
                $sql_selectPreapare->bindParam(':tournamentId', $tournamentId); // BIND THE 'TOURNAMENT ID' PARAMETER THAT WILL BE PASSED...SOON             
                
                if($sql_selectPreapare->execute() && $sql_selectPreapare->rowCount()) 
                {
                    $groupsInfo['infoToBeInserted'][] = $group;
                    
                }else $groupsInfo['infoToBeIgnored'][] = $group;
            }

             // CLOSE THIS METHOD IF THERE'S NOTHING VALID TO BE INSERTED   
            if( empty($groupsInfo['infoToBeInserted']) ) $this->e_nonExistingTournament();

            // CREATE A INSERT STMT 
            $sql_insertStmt =    
                "INSERT INTO `SG_GROUPS` 
                        (`NAME`,
                        `TOURNAMENT_ID`)
                    VALUES 
                        (:groupName, 
                        :tournamentId)";            
            $sql_insertPrepare = $conn->prepare($sql_insertStmt);           // PREPARE THE QUERY

            /**
             * THIS ITERATION WILL INSERT GROUPS THAT ARE CAN BE BINDED TO EXISTING TOURNAMENTS
            */
            foreach ($groupsInfo['infoToBeInserted'] as $group) { 
                $groupName = $group['groupName'];
                $tournamentId = $group['tournamentId'];

                $sql_insertPrepare->bindParam(':groupName' , $groupName);         // BIND THE 'GROUP NAME' PARAMETER THAT WILL BE PASSED...SOON  
                $sql_insertPrepare->bindParam(':tournamentId', $tournamentId);    // BIND THE 'TOURNAMENT ID' PARAMETER THAT WILL BE PASSED...SOON  
                $sql_insertPrepare->execute(); 
            }

            $this->s_insert();
        }catch(PDOException $error){
            $stateError = $error->errorInfo[0];
            $codeError = $error->errorInfo[1];
            $specificMessage = $error->errorInfo[2];            
            // echo $genericMessage = $error->getMessage();

            if($codeError == $this->MYSQL_DUPLICATED_KEY) $this->e_alreadyExistingGroup();
            if($codeError == $this->MYSQL_FOREIGN_KEY_FAILS ) $this->e_nonExistingTournament();
        }
    }

    public function updateGroups() {
        $groupsInfo = json_decode( $this->urlParameters['info'], true ); // DECODE THE JSON INTO AN ARRAY
        
        try{        
            $conn = (new DB())->connect();   
        
            foreach($groupsInfo as &$group) {
                /**
                 * CONVERTS ALL KEYS TO UPPERCASE. 
                 * THE REASON IS THAT ALL COLUMN'S NAMES ON DATABASE ARE CURRENTLY IN UPPERCASE
                */   
                $group = array_change_key_case($group,CASE_UPPER);        
                /**
                 * RETURNS ALL PROPERTIES OF THE ARRAY THAT'S GIVEN BY THE USER BUT THE 'ID' PROPERTY. T
                 * THIS NEW ARRAY WILL CONTAIN ALL FIELDS (AND ITS VALUES) THAT SHOULD BE UPDATED. 
                */  
                $dataToUpdate = array_slice($group,1);

                $group["prepareParams"] = array_slice($group,0);
            
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
                $group["placeholders"] = ltrim(implode(",",$temp));  
                /**
                 * - CREATES A STRING TO BE SET AS CONTENT OF A PREPARE STATEMENT
                */ 
                $group["prepareStmt"] = "UPDATE `SG_GROUPS` SET " . $group["placeholders"] . " WHERE (ID = :ID AND ACTIVE != 0)";
                                
                $prepareUpdate = $conn->prepare($group["prepareStmt"]); // SET THE PREPARE STATEMENT
                // EXECUTE IT
                if( $prepareUpdate->execute($group["prepareParams"]) ) 
                {
                    if( !$prepareUpdate->rowCount() ) 
                    {
                        $this->e_updates();
                        exit();
                    }
                    
                }       
                    

            }
            $this->s_update(); // OUTPUT THE SUCCESS RESULT   
        }catch(PDOException $error){
            $stateError = $error->errorInfo[0];
            $codeError = $error->errorInfo[1];
            $specificMessage = $error->errorInfo[2];            
            echo $genericMessage = $error->getMessage();

            if($codeError == $this->MYSQL_DUPLICATED_KEY) $this->e_alreadyExistingGroup();
            // if($codeError == $this->MYSQL_FOREIGN_KEY_FAILS ) $this->e_nonExistingTournament();
        }
    }

    public function removeGroups() {
        $groupsInfo = json_decode( $this->urlParameters['info'], true ); // DECODE THE JSON INTO AN ARRAY
        $groupsInfo = array_change_key_case($groupsInfo,CASE_UPPER);      // CONVERT THE KEYS TO UPPERCASE

        /**
         * BUILDS THE PARAMETERS STRUCUTURE TO BE USED IN A PREPARE STATEMENT
        */
        $ids = $groupsInfo['ID'];                                        // CONVERT THE GIVEN ID'S TO A SQL SYNTAX FORMAT
        $placeholders = implode(",", array_fill(0,count($ids),"?") );   // PREPARE PLACEHOLDERS

        try {
            $conn = (new DB())->connect(); 
            $prepareDelete = $conn->prepare("UPDATE `SG_GROUPS` SET ACTIVE = 0 
                                                                    WHERE ID IN ( {$placeholders} ) ");
            
            if($prepareDelete->execute($ids)) {
                if($prepareDelete->rowCount() ) $this->s_remove();
                else $this->e_noGroupsRemoved();
            }
            

        }catch(PDOException $error) {
            $stateError = $error->errorInfo[0];
            $codeError = $error->errorInfo[1];
            $specificMessage = $error->errorInfo[2];            
            echo $genericMessage = $error->getMessage();

            // if($codeError == $this->MYSQL_DUPLICATED_KEY) $this->e_alreadyExistingGroup();
            // if($codeError == $this->MYSQL_FOREIGN_KEY_FAILS ) $this->e_nonExistingTournament();
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
                'code' => '002'
            )
        ); 
    }

    public function e_noGroupsFound() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "Sorry, could not find any Group on database",
                'code' => '001'
            )
        );
        exit();
    }

    public function e_updates() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "No record(s) updated. Check for ALL GROUPS you want to remove: if the 'identification' that was passed is correct OR if the group was removed OR if the field's content you want to update are the same than the previous ones.",
                'code' => '004',
                'active groups' => $this->getAllGroups()
            )
        );
        exit();
    }

    public function e_noGroupsRemoved() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "Sorry, could not find any Group to be removed from database",
                'code' => '005'
            )
        );
        exit();
    }

    public function e_alreadyExistingGroup() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "This group already exists in this tournament",
                'code' => '006'
            )
        );
        exit();
    }

    public function e_nonExistingTournament() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "failure", 
                "message" => "The tournament that you've tried to add this group(s) do(es) not exist(s) yet. Please create tournament(s) first!",
                'code' => '007'
            )
        );
        exit();
    }

    // MYSQL ERROR LIST
    private $MYSQL_DUPLICATED_KEY = 1062;
    private $MYSQL_FOREIGN_KEY_FAILS = 1452;


    // RESPONSES USERS FEEDBACK
    public function s_insert() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "success", 
                "message" => "Group(s) successfully created. Plis bilivi mi!",
                'code' => '101'
            )
        );
        exit();
    }
    
    public function s_update() {
        echo json_encode(
            array(
                "request type" => $_SERVER['REQUEST_METHOD'], 
                "status" => "success", 
                "message" => "Group(s) successfully updated. You're god damn right!",
                'code' => '101',
                'active groups' => $this->getAllGroups()
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
                "message" => "Group(s) successfully removed. Plis bilivi mi!",
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
            *   TERMINATE THIS FUNCTION IF:        
            *       - MORE THAN ONE PARAMETERS WERE PASSED... 
            *       - AN ARGUMENT NAMED 'info' WASN'T PASSED... 
        */
        if( sizeof($this->urlParameters) > 1) {
            if( 
                !key_exists("info",$this->urlParameters) ||
                !key_exists("showTeams",$this->urlParameters)
            ) $this->e_invalidStructure();
        } 

        /* 
         * WHEN THE GET REQUEST HAS NO PARAMETERS...RETRIEVE ALL TOURNAMENTS
        */
        if( 
            $this->emptyParameters(false) || 
            ( !key_exists("info",$this->urlParameters) && key_exists("showTeams",$this->urlParameters) )
        ) 
        {
            echo json_encode($this->getAllGroups());
            exit();
        }
        /* 
         * WHEN THE GET REQUEST HA PARAMETERS AND THEY'RE VALID... RETRIEVE THE REQUIRED TOURNAMENTS
        */
        echo $this->getSpecificGroups(); 
        exit();
    }

    public function response_POST() {
         /* 
        *  SET THE POSSIBLE GIVEN PARAMETERS INTO THE OBJECT...
        */ 
        $this->setAllParameters();   

        $this->addGroups();
    }

    public function response_PATCH() {
         /* 
        *  SET THE POSSIBLE GIVEN PARAMETERS INTO THE OBJECT...
        */ 
        $this->setAllParameters();   

        $this->updateGroups();
    }

    public function response_DELETE() {
        /* 
       *  SET THE POSSIBLE GIVEN PARAMETERS INTO THE OBJECT...
       */ 
       $this->setUrlParameters();   

       $this->removeGroups();
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