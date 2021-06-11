<?php

// This will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

// Include the main controller
include '../../../config/controller.php';

// Get the posted data
$_data = file_get_contents("php://input");

// Decoding it into an object
$gnawData = json_decode($_data);

try {

	$salon = new SalonController();

	$salon->addGnaw($gnawData);

} catch (Exception $e) {
	SamayGnawController::notify("uerr", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}


?>