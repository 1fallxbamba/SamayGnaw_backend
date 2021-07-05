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

	public static function notify($type, $code, $message, $data = null)
	{
		if ($type == 'uerr') {
			echo json_encode(array('RESPONSE' => 'Unexpected-Error', 'CODE' => $code, 'DESCRIPTION' => $message));
		} elseif ($type == 'err') {
			echo json_encode(array('RESPONSE' => 'Request-Error', 'CODE' => $code, 'DESCRIPTION' => $message));
		} else {
			if ($data == null) {
				echo json_encode(array('RESPONSE' => 'Success', 'CODE' => $code, 'MESSAGE' => $message));
			} else {
				echo json_encode(array('RESPONSE' => 'Success', 'CODE' => $code, 'MESSAGE' => $message, 'DATA' => $data));
			}
		}
	}

	/* An SGI : SamayGnawIdentifier is a unique identifier used by us to identify the users
	We have 3 types : SGG (SamayGnaw Gnaw) used for Gnaws, SGC (SamayGnaw Client) for Clients and SGS (SamayGnaw Saloon) for Saloons
	*/
	protected function generateSGI($type, $phone = null)
	{
		$sgi = "";

		$table = $type == "SGG" ? "gnaws" : "clients";

		/* SGG and SGC are formed by  : the string 'SGG'/'SGC' + ((id of the latest inserted gnaw) + 1) +  a random number between 1 and 255
		Example : SGG4-54 SGC2-239
		*/
		if ($type == "SGG" || $type == "SGC") {

			$query = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";

			try {

				$stmt = self::$_sqlCon->prepare($query);

				$stmt->execute();

				$result = $stmt->fetch(PDO::FETCH_ASSOC);

				$latestID = $result['id'];
				$sgi = $type . strval(((int)$latestID) + 1) . '-' . strval(rand(1, 255));
			} catch (Exception $e) {
				self::notify("uerr", "UNEX", "Due to an unexpected error, the SGI creation failed");
			}
			/*
			SGS is formed by the string 'SGS' mixed with the phone number of the saloon and a randomly generated number between 1 and 999
			*/
		} else {
			$num = strval($phone);
			$sgi = $num[0] . $num[1] . "$type-" . $num[2] . $num[3] . $num[4] . $num[5] . rand(1, 999) . $num[6] . $num[7] . $num[8];
		}

		return $sgi;
	}

	protected function fetchSaloonNameAndPhone($sgi)
	{
		$query = "SELECT nom, tel FROM salons WHERE sgi = '$sgi'";

		try {

			$stmt = self::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			return $result;
		} catch (Exception $e) {
			self::notify("uerr", "UNEX", "Due to an unexpected error, the operation failded");
		}
	}

	protected function fetchClientName($sgi)
	{
		$query = "SELECT prenom, nom FROM clients WHERE sgi = '$sgi'";

		try {

			$stmt = self::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			return $result['prenom'] . ' ' . $result['nom'];
		} catch (Exception $e) {
			self::notify("uerr", "UNEX", "Due to an unexpected error, the operation failed");
		}
	}
}

class AdminController extends SamayGnawController
{

	public function viewRequests()
	{
		$query = "SELECT * FROM requests";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "RFS", "Requests' data Fetched Successfully", json_encode($result));
			} else {
				parent::notify("s", "NRF", "No Requests Found : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewRequest($id)
	{

		$query = "SELECT * FROM requests WHERE id = '$id'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "RFS", "Request's data Fetched Successfully", json_encode($result));
			} else {
				parent::notify("s", "NRF", "No Request Found for the given id : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewSaloons()
	{
		$query = "SELECT * FROM salons";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "SFS", "Saloons' data Fetched Successfully", json_encode($result));
			} else {
				parent::notify("s", "NSF", "No Saloons Found : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
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
				parent::notify("s", "SFS", "Saloon data Fetched Successfully", json_encode($result));
			} else {
				parent::notify("s", "NSF", "No Saloon Found for the given sgi : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewClients()
	{
		$query = "SELECT id, sgi, nom, prenom, tel, genre FROM clients";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "CFS", "Clients' data Fetched Successfully", json_encode($result));
			} else {
				parent::notify("s", "NCF", "No Clients Found : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
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
				parent::notify("s", "CFS", "Client data Fetched Successfully", json_encode($result));
			} else {
				self::notify("s", "NCF", "No Client Found for the given sgi : The query returned an empty result");
			}
		} catch (Exception $e) {
			self::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	private function addUser($login, $pwd)
	{
		$query = "INSERT INTO users(login, shadow) VALUES('$login','$pwd')";

		$stmt = parent::$_sqlCon->prepare($query);

		if ($stmt->execute() && $stmt->rowCount() != 0) {
			return true;
		} else {
			return false;
		}
	}

	private function addSaloon($sgi, $name, $address, $phone, $email)
	{
		$query = "INSERT INTO salons(sgi, nom, adresse, tel, email) VALUES('$sgi','$name', '$address', $phone, '$email')";

		$stmt = parent::$_sqlCon->prepare($query);

		if ($stmt->execute() && $stmt->rowCount() != 0) {
			return true;
		} else {
			return false;
		}
	}

	private function updateRequestStatus($id)
	{
		$query = "UPDATE requests SET statut = 'Approved' WHERE id = $id";

		$stmt = parent::$_sqlCon->prepare($query);

		if ($stmt->execute() && $stmt->rowCount() != 0) {
			return true;
		} else {
			return false;
		}
	}

	public function approveRequest($requestData)
	{
		$_id = $requestData->id;
		$_name = $requestData->name;
		$_address = $requestData->address;
		$_phone = $requestData->phone;
		$_email = $requestData->email;
		$_shadow = $requestData->shadow;
		$sgi = parent::generateSGI("SGS", $_phone);

		try {
			if ($this->addUser($sgi, $_shadow)) {
				if ($this->addSaloon($sgi, $_name, $_address, $_phone, $_email)) {
					if ($this->updateRequestStatus($_id)) {
						parent::notify("s", "RAS", "The registration Request has been Approved Successfully");
					} else {
						parent::notify("uerr", "UNEX", "An unexpected error has occured, the request status could not be updated to 'Approved' !");
					}
				} else {
					parent::notify("uerr", "UNEX", "An unexpected error has occured, the new saloon could not be added !");
				}
			} else {
				parent::notify("uerr", "UNEX", "An unexpected error has occured, the new saloon's credentials could not be added !");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}
}

class SalonController extends SamayGnawController // thanks to heritage, parent's constructor is implicitly called, connection to db is then automatic
{

	public function requestAccount($accountData)
	{
		//informations
		$_name = $accountData->name;
		$_address = $accountData->address;
		$_phone = $accountData->phone;
		$_email = $accountData->email;
		$password = $accountData->pwd;

		$_shadow = password_hash($password, PASSWORD_DEFAULT);

		$query = "INSERT INTO requests(nom, adresse, tel, email, shadow) 
		VALUES('$_name','$_address', $_phone, '$_email', '$_shadow')";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute() && $stmt->rowCount() != 0) {
				parent::notify("s", "NRSS", "The New Request has been Successfully Sent");
			} else {
				parent::notify("uerr", "UNEX", "An unexpected error has occured, the request could not be sent !");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function verifyIdentify($sgi)
	{
		$query = "SELECT id FROM salons WHERE sgi = '$sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($stmt->rowCount() === 0) {
				parent::notify("err", "SDNE", "Saloon Does Not Exist : the given sgi does not exist in the records.");
			} else {
				parent::notify("s", "SSA", "The Saloon is Successfully Identified");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function login($credentials)
	{

		$_login = $credentials->sgi;
		$_pwd = $credentials->pwd;

		$query = "SELECT id, shadow FROM users WHERE login = '$_login'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC); // retrieves the id and shadow of the given 'login'

			if ($result && $stmt->rowCount() !== 0) {  // if the login exists in the database

				$fetchedPwd = $result['shadow'];

				if (password_verify($_pwd, $fetchedPwd)) { // then check if the password is correct
					parent::notify("s", "USA", "The User is Successfully Authenticated");
				} else {
					parent::notify("err", "WPWD", "Wrong Password : The entered password is incorrect ");
				}
			} else { // if the result is emppty (===> rowCount = 0), then the requested login does not exsit in the database
				parent::notify("err", "UDNE", "User Does Not Exist : the given login does not exist in the records ");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function addClient($clientData) // check if the client exists (i don't know a way of doing that right now, i'll keep thinking :!)
	{

		$_saloonSGI = $clientData->saloon;
		$_lastName = $clientData->lastName;
		$_firstName = $clientData->firstName;
		$_phone = (int)$clientData->phone;
		$_gender = $clientData->gender;

		$sgi = parent::generateSGI("SGC", $_phone);

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
		clients(sgi, salon, nom, prenom, tel, genre, cou, epaule, poitrine, ceinture, tourBras, 
		tourPoignet, longManche, longPant, longTaille, longCaftan, tourCuisse, tourCheville)
		VALUES('$sgi', '$_saloonSGI', '$_lastName', '$_firstName', $_phone, '$_gender', $_cou, $_epaule, $_poitrine, 
		$_ceinture, $_tourBras, $_tourPoignet, $_longManche, $_longPant, $_longTaille, $_longCaftan, $_tourCuisse, $_tourCheville)";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute() && $stmt->rowCount() != 0) {

				parent::notify("s", "NCSA", "The new client has been successfully added", $sgi);
			} else {
				parent::notify("uerr", "UNEX", "An unexpected error has occured, the client could not be added !");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function fetchClient($clientSGI)
	{

		$query = "SELECT  
		nom, prenom, cou, epaule, poitrine, ceinture, tourBras, tourPoignet, longManche, 
		longPant, longTaille, longCaftan, tourCuisse, tourCheville  
		FROM clients WHERE sgi = '$clientSGI'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "CFS", "Client data Fetched Successfully", json_encode($result));
			} else {
				parent::notify("s", "NDF", "No Data Found for this client");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function fetchClients($sgi)
	{

		$query = "SELECT sgi, nom, prenom, tel FROM clients WHERE salon = '$sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "CFS", "Clients Fetched Successfully", json_encode($result));
			} else {
				parent::notify("s", "NCF", "No Client Found for the given sgi : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function addGnaw($gnawData)
	{
		$sgi = parent::generateSGI("SGG");
		$_prop = $gnawData->prop;
		$_saloon = $gnawData->saloon;

		$_dateL = substr(str_replace('T', ' ', $gnawData->dateL), 0, 19); // format the date to a MySql understandable format

		$_price = $gnawData->price;
		$_avance = $gnawData->avance;
		$_type = $gnawData->type;

		$query = "INSERT INTO gnaws(sgi, prop, salon, dateL, prix, avance, type ) 
		VALUES('$sgi','$_prop', '$_saloon', '$_dateL', $_price, $_avance, '$_type')";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute() && $stmt->rowCount() != 0) {
				parent::notify("s", "NGSA", "The New Gnaw has been Successfully Added", $sgi);
			} else {
				parent::notify("uerr", "UNEX", "An unexpected error has occured, the gnaw could not be added !");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function fetchGnaws($sgi, $forFetch = false)
	{

		$query = "SELECT sgi, prop, dateC, dateL, prix, avance, etat, type FROM gnaws WHERE salon = '$sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {

				$clientNames = array();

				foreach ($result as $r) {
					array_push($clientNames, parent::fetchClientName($r['prop']));
				}

				$gnawData = json_encode(
					array(
						'GNAWS' => $result,
						'NAMES' => $clientNames
					)
				);

				if ($forFetch) {
					return $result;
				} else {
					parent::notify("s", "GFS", "Gnaws Fetched Successfully", $gnawData);
				}
			} else {
				if ($forFetch) {
					return array();
				} else {
					parent::notify("s", "NGF", "No Gnaw Found for the given sgi : The query returned an empty result");
				}
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function updateGnaw($newGnawData)
	{

		$_sgi = $newGnawData->sgi;
		$_dateL = $newGnawData->dateL;
		$_avance = $newGnawData->avance;
		$_etat = $newGnawData->etat;

		$query = "UPDATE gnaws SET dateL = '$_dateL', avance = $_avance, etat = '$_etat' WHERE sgi = '$_sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute()) {
				if ($stmt->rowCount() == 0) {
					parent::notify("err", "SDNE", "The given Sgi Does Not Exist !");
				} else {
					parent::notify("s", "GUS", "The Gnaw has been Updated Successfully");
				}
			} else {
				parent::notify("uerr", "UNEX", "An unexpected error has occured, the gnaw could not be updated !");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function fetchInfo($sgi)
	{
		$_name = parent::fetchSaloonNameAndPhone($sgi);
		$name = $_name['nom'];

		$gnaws = $this->fetchGnaws($sgi, true);

		$numberOfGnaws = count($gnaws);
		$ongoingGnaws = 0;
		$finishedGnaws = 0;

		foreach ($gnaws as $g) {
			$ongoingGnaws += $g['etat'] == 'En cours' || $g['etat'] == 'Attente paiement';
			$finishedGnaws += $g['etat'] == 'Terminé';
		}

		$saloonInfo = json_encode(
			array(
				'NAME' => $name,
				'GNAWS' => $numberOfGnaws,
				'ONGOING' => $ongoingGnaws,
				'FINISHED' => $finishedGnaws
			)
		);

		echo $saloonInfo;
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

	public function verifyIdentify()
	{
		$query = "SELECT id FROM clients WHERE sgi = '$this->_sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($stmt->rowCount() === 0) {
				parent::notify("err", "CDNE", "Client Does Not Exist : the given sgi does not exist in the records.");
			} else {
				parent::notify("s", "CSA", "The Client is Successfully Identified");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewMeasurements()
	{

		$query = "SELECT  
		cou, epaule, poitrine, ceinture, tourBras, tourPoignet, longManche, 
		longPant, longTaille, longCaftan, tourCuisse, tourCheville  
		FROM clients WHERE sgi = '$this->_sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "MFS", "Measurements Fetched Successfully", $result);
			} else {
				parent::notify("s", "NMF", "No measurements found for this client");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function fetchGnaws()
	{
		$query = "SELECT sgi, dateC, dateL, prix, avance, etat, type FROM gnaws WHERE prop = '$this->_sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query); // to fix !!

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				parent::notify("s", "GFS", "Gnaws Fetched Successfully", $result);
			} else {
				parent::notify("s", "NGF", "No gnaws found for this client");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function fetchInfo()
	{
		$query = "SELECT salon FROM clients WHERE sgi = '$this->_sgi'";

		$stmt = self::$_sqlCon->prepare($query);

		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		$_saloon = parent::fetchSaloonNameAndPhone($result['salon']);

		$name = parent::fetchClientName($this->_sgi);
		$saloonName = $_saloon['nom'];
		$saloonPhone = $_saloon['tel'];

		$clientInfo = json_encode(
			array(
				'NAME' => $name,
				'SALOONNAME' => $saloonName,
				'SALOONPHONE' => $saloonPhone
			)
		);

		echo $clientInfo;
	}

	// OLD
	// public function viewGnaws()
	// {

	// 	$query = "SELECT sgi, salon, dateC, dateL, prix, avance, etat, type FROM gnaws WHERE prop = '$this->_sgi'";

	// 	try {

	// 		$stmt = parent::$_sqlCon->prepare($query);

	// 		$stmt->execute();

	// 		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// 		if ($result && $result !== null) {

	// 			$gnawsSGIs = array();
	// 			$saloonNames = array();
	// 			$datesC = array();
	// 			$datesL = array();
	// 			$prices = array();
	// 			$avances = array();
	// 			$etats = array();
	// 			$types = array();

	// 			foreach ($result as $r) { // since we don't want to return the sgi of the saloons to the user
	// 				array_push($saloonNames, parent::getSaloonName($r['salon'])); // we get their names first

	// 				// Then we separately get each attribut of gnaws, cause they can be different
	// 				array_push($gnawsSGIs, $r['sgi']);
	// 				array_push($datesC, $r['dateC']);
	// 				array_push($datesL, $r['dateL']);
	// 				array_push($prices, $r['prix']);
	// 				array_push($avances, $r['avance']);
	// 				array_push($etats, $r['etat']);
	// 				array_push($types, $r['type']);
	// 			}

	// 			// then build a new array to return to the user
	// 			$gnawsData = json_encode(
	// 				array(
	// 					"sgis" => $gnawsSGIs,
	// 					'salons' => $saloonNames,
	// 					'datesC' => $datesC,
	// 					'datesL' => $datesL,
	// 					'prix' => $prices,
	// 					'avances' => $avances,
	// 					'etats' => $etats,
	// 					'types' => $types
	// 				)
	// 			);

	// 			parent::notify("s", "GFS", "Gnaws Fetched Successfully", $gnawsData);
	// 		} else {
	// 			parent::notify("s", "NGF", "No Gnaw Found for the given client : The query returned an empty result");
	// 		}
	// 	} catch (Exception $e) {
	// 		parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
	// 	}
	// }
}
