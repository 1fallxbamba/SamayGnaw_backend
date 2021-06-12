<?php

// will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


include '../config/controller.php';

$id = isset($_GET['id']) ? $_GET['id'] : die(json_encode(array('ERROR' => 'No request id provided')));


try {

	$admin = new AdminController();

	$admin->viewRequest($id);

} catch (Exception $e) {
	SamayGnawController::notify("uerr", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}

?>