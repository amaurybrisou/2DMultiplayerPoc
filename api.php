<?php

session_start();


error_reporting(E_ALL);
ini_set("display_errors", 1); 

require 'server/Program.php';

$program = new Program();

if(isset($_GET['method']) && in_array($_GET['method'], ['refresh', 'delPlayer', 'delSet', 'getSet', 'newSet'])){

	$program->$_GET['method']();

} else if (isset($_GET['method']) && $_GET['method'] == 'addPlayer' && isset($_POST['name'])){

	$program->addPlayer($_POST['name'], $_POST['img']);
} else if (isset($_GET['method']) && $_GET['method'] == 'movePlayer' && isset($_POST['x']) && isset($_POST['y'])){

	$program->movePlayer([ "x" => $_POST['x'], "y" => $_POST['y']]);
}

echo json_encode($program);


?>