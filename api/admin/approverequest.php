<?php

// This will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

include '../config/controller.php';

$_data = file_get_contents("php://input");

// Decoding it into an object
$requestData = json_decode($_data);

try {

	$admin = new AdminController();

	$admin->approveRequest($requestData);

} catch (Exception $e) {
	SamayGnawController::notify("err", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}


?>