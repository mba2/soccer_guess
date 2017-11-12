<?php
echo "<pre>";
require_once("../../classes/Tournaments.php");

$tournaments = new Tournaments();
$tournaments->response();


print_r($tournaments);
