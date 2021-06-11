<?php

// will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


include '../config/controller.php';

$sgi = isset($_GET['sgi']) ? $_GET['sgi'] : die('ERROR: No SGI provided');


try {

	$admin = new AdminController();

	$admin->viewSaloon($sgi);

} catch (Exception $e) {
	SamayGnawController::notify("uerr", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}

?>