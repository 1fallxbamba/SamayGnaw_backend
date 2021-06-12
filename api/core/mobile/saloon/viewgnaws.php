<?php

// will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


include '../../../config/controller.php';

$saloonSGI = isset($_GET['ssgi']) ? $_GET['ssgi'] : die(json_encode(array('ERROR' => 'No Saloon SGI provided')));

try {
	$salon = new SalonController();
	$salon->viewGnaws($saloonSGI);
} catch (Exception $e) {
	SamayGnawController::notify("uerr", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}

?>