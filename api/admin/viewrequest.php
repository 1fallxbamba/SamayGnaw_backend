<?php

// will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


include '../config/controller.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: No SGI provided');


try {

	$admin = new AdminController();

	$admin->viewRequest($id);

} catch (Exception $e) {
	SamayGnawController::notify("err", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}

?>