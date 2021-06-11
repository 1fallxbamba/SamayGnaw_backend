<?php

// will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


include '../config/controller.php';


try {

	$admin = new AdminController();

	$admin->viewRequests();

} catch (Exception $e) {
	SamayGnawController::notify("uerr", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}

?>