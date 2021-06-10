<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

// Include the main controller
include '../../config/controller.php';

// Get the posted data
$_data = file_get_contents("php://input");



// Decoding it into an object
$gnawData = json_decode($_data);

try {

	$salon = new SalonController();

	$salon->updategnaw();

} catch (Exception $e) {

	echo json_encode(array('STATUS' => 'Unexpected-Error' , 'CODE' => 'UNEX', 'DESCRIPTION' => 'Due to an unexpected error the requested operation can not be processed'));
}

if (isset($_GET['sgi'])) {
    if (!empty($_POST)) {
        // This part is similar to the newgnaw.php, but instead we update a record and not insert

        $_dateL = isset($_POST['dateL']) ? $_POST['dateL'] : '';
        $_avance = isset($_POST['avance']) ? $_POST['avance'] : '';
        $_etat = isset($_POST['etat']) ? $_POST['etat'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE gnaws SET   dateL = ?, avance = ?, etat = ? WHERE sgi = ?');
        $stmt->execute([  $_dateL, $_avance, $_etat,  $_GET['sgi']]);
        $msg = 'Updated Successfully!';
    }
    // Get the details from the gnaws table
    $stmt = $pdo->prepare('SELECT * FROM gnaws WHERE sgi = ?');
    $stmt->execute([$_GET['sgi']]);
    $gnaws = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$gnaws) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}


?>