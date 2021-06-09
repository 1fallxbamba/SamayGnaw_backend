<?php

/**
 * SamayGnaw Controller v1.0, @authors : 1fallxbamba, bamba2001, cupsdareal
 */

class SamayGnawController
{
	protected static $_sqlCon;

	public function __construct()
	{
		$this->_connect();  // so that it automatically connects to the db wehn the controller is inherited
	}

	private static function _connect()
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

	public static function notify($type, $code, $message)
	{
		if ($type == 'err') {
			echo json_encode(array('STATUS' => 'Unexpected-Error', 'CODE' => $code, 'DESCRIPTION' => $message));
		} else {
			echo json_encode(array('STATUS' => 'Success', 'CODE' => $code, 'MESSAGE' => $message));
		}
	}
}

class AdminController extends SamayGnawController
{

	public function viewSaloons()
	{
		$query = "SELECT * FROM salons";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($results && $results !== null) {
				echo json_encode($results);
			} else {
				parent::notify("s", "NSF", "No Saloons Found : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewSaloon($sgi)
	{

		$query = "SELECT * FROM salons WHERE sgi = '$sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				echo json_encode($result);
			} else {
				parent::notify("s", "NSF", "No Saloon Found for the given sgi : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewClients()
	{
		$query = "SELECT id, sgi, nom, prenom, tel, genre FROM clients";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($results && $results !== null) {
				echo json_encode($results);
			} else {
				parent::notify("s", "NCF", "No Clients Found : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewClient($sgi)
	{
		$query = "SELECT id, sgi, nom, prenom, tel, genre FROM clients WHERE sgi = '$sgi'";

		try {

			$stmt = self::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				echo json_encode($result);
			} else {
				self::notify("s", "NCF", "No Client Found for the given sgi : The query returned an empty result");
			}
		} catch (Exception $e) {
			self::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}
}

class SalonController extends SamayGnawController // thanks to heritage, parent's constructor is implicitly called, connection to db is then automatic
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
		clients(sgi, nom, prenom, tel, genre, cou, epaule, poitrine, ceinture, tourBras, 
		tourPoignet, longManche, longPant, longTaille, longCaftan, tourCuisse, tourCheville)
		VALUES('$_sgi', '$_lastName', '$_firstName', $_phone, '$_gender', $_cou, $_epaule, $_poitrine, 
		$_ceinture, $_tourBras, $_tourPoignet, $_longManche, $_longPant, $_longTaille, $_longCaftan, $_tourCuisse, $_tourCheville)";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute()) {

				parent::notify("s", "NCSA", "The new client has been successfully added");
			} else {
				parent::notify("err", "UKN", "An unknown error has occured !");
			}
		} catch (Exception $e) {

			parent::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewClient($sgi)
	{

		$query = "SELECT  
		nom, prenom, cou, epaule, poitrine, ceinture, tourBras, tourPoignet, longManche, 
		longPant, longTaille, longCaftan, tourCuisse, tourCheville  
		FROM clients WHERE sgi = '$sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query); // to fix !!

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				echo json_encode($result);
			} else {
				parent::notify("s", "NMF", "No measurements found for this client");
			}
		} catch (Exception $e) {
			parent::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}
}

class ClientController extends SamayGnawController
{

	private $_sgi;

	public function __construct($sgi)
	{
		parent::__construct(); // here we need to explicitly called the parent constructor cause we declared a constructor for the child
		$this->_sgi = $sgi;
	}

	public function measurements()
	{

		$query = "SELECT  
		cou, epaule, poitrine, ceinture, tourBras, tourPoignet, longManche, 
		longPant, longTaille, longCaftan, tourCuisse, tourCheville  
		FROM clients WHERE sgi = '$this->_sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query); // to fix !!

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				echo json_encode($result);
			} else {
				parent::notify("s", "NMF", "No measurements found for this client");
			}
		} catch (Exception $e) {
			parent::notify("err", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}
}
