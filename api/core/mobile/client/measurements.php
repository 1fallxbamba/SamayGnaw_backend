<?php

// will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


include '../../../config/controller.php';

// Get the sgi passed in the url 
$sgi = isset($_GET['sgi']) ? $_GET['sgi'] : die(json_encode(array('ERROR' => 'No Client SGI provided')));


try {

	$client = new ClientController($sgi);

	$client->measurements();

} catch (Exception $e) {
	SamayGnawController::notify("uerr", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}


?>