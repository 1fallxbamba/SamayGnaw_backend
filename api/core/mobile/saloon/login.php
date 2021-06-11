<?php

// This will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

include '../../../config/controller.php';

$_data = file_get_contents("php://input");

$credentials = json_decode($_data);

try {

	$salon = new SalonController();

	$salon->login($credentials);

} catch (Exception $e) {
	SamayGnawController::notify("uerr", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}


?>