<?php

// will serve after deployment
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


include '../config/controller.php';


try {

	$admin = new AdminController();

	$admin->viewSaloons();

} catch (Exception $e) {
	SamayGnawController::notify("err", "UNEX", "Due to an unexpected error the requested operation can not be processed");
}

?>