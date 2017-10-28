<?php

require_once("App.php");

class Groups extends App {
    
    public function __construct() { }

    function retrieveAllTeams() {
    
        $allTeamsInGroupsQuery = "SELECT  *
                                    FROM sg_group_formation
                                    LEFT JOIN sg_teams USING(TEAM_ID)
                                    LEFT JOIN sg_groups USING(GROUP_ID)";

        $conn           = DB::connect("remote");
        $query          = $conn->query($allTeamsInGroupsQuery);
        $allTeamsInfo   = $query->fetchAll(PDO::FETCH_ASSOC);

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

    function response () {
        switch($_SERVER["REQUEST_METHOD"]) {
            case 'GET':
                // IF NO PARAMETER IS PASSED...RETURNS FULL INFORMATION ABOUT ALL GROUPS
                if( !sizeof($_GET) ) { 
                    $this->retrieveAllTeams();               
                    exit();
                }
                break;
                case 'POST':
                
                if( !sizeof($_POST) ) { 
                    $this->retrieveAllTeams();               
                    exit();
                }
                break;
            default:
                break;
        }
    }

}