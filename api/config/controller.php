<?php

/**
 * SamayGnaw Controller v1.0, @authors : 1fallxbamba, bamba2001, cupsdareal
 */

class SamayGnawController
{
	protected static $_sqlCon;

	public function __construct()
	{

		self::_connect();
	}

	private static function _connect() // so that i automatically connects to the db
	{

		$host = "localhost";
		$db_name = "samaygnaw";
		$username = "root";
		$password = "";

		try {

			self::$_sqlCon = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}

		// show error
		catch (PDOException $e) {
			echo "Connection error: " . $e->getMessage();
		}
	}

	protected function notify($type, $code, $message)
	{
		if ($type == 'err') {
			echo json_encode(array('STATUS' => 'Unexpected-Error', 'CODE' => $code, 'DESCRIPTION' => $message));
		} else {
			echo json_encode(array('STATUS' => 'Success', 'CODE' => $code, 'MESSAGE' => $message));
		}
	}
}

class SalonController extends SamayGnawController
{

	public function addClient($clientData)
	{

		$_sgi = $clientData->sgi;
		$_lastName = $clientData->lastName;
		$_firstName = $clientData->firstName;
		$_phone = $clientData->phone;
		$_gender = $clientData->gender;

		// Measurements
		$_cou = $clientData->cou;
		$_epaule = $clientData->epaule;
		$_poitrine = $clientData->poitrine;
		$_ceinture = $clientData->ceinture;
		$_tourBras = $clientData->tourBras;
		$_tourPoignet = $clientData->tourPoignet;
		$_longManche = $clientData->longManche;
		$_longPant = $clientData->longPant;
		$_longTaille = $clientData->longTaille;
		$_longCaftan = $clientData->longCaftan;
		$_tourCuisse = $clientData->tourCuisse;
		$_tourCheville = $clientData->tourCheville;

		$query = "INSERT INTO 
		clients(sgi, nom, prenom, tel, genre, cou, epaule, poitrine, ceinture, tourBras, tourPoignet, longManche, longPant, longTaille, longCaftan, tourCuisse, tourCheville)
		VALUES('$_sgi', '$_lastName', '$_firstName', $_phone, '$_gender', $_cou, $_epaule, $_poitrine, $_ceinture, $_tourBras, $_tourPoignet, $_longManche, $_longPant, $_longTaille, $_longCaftan, $_tourCuisse, $_tourCheville)";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute()) {

				parent::notify("s", "NCSA", "The new user has been successfully added");
			} else {
				parent::notify("err", "UKN", "An unknown error has occured !");
			}

		} catch (Exception $e) {

			parent::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

}
