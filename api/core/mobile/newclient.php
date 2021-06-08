<?php

// This will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

// Include the main controller
include '../../config/controller.php';

// Get the posted data
$_data = file_get_contents("php://input");

// Decoding it into an object
$clientData = json_decode($_data);

try {

	$salon = new SalonController();

	$salon->addClient($clientData);

} catch (Exception $e) {

	echo json_encode(array('STATUS' => 'Unexpected-Error' , 'CODE' => 'UNEX', 'DESCRIPTION' => 'Due to an unexpected error the requested operation can not be processed'));
}


?>