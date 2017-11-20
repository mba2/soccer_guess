<?php

header("Access-Control-Allow-Origin: *");        
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PATCH, PUT");


require_once("../../classes/Tournaments.php");



$tournaments = new Tournaments();
$tournaments->response();