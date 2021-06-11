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
			echo json_encode(array('STATUS' => 'Unexpected-Error', 'CODE' => $code, 'DESCRIPTION' => $message));
		} elseif ($type == 'err') {
			echo json_encode(array('STATUS' => 'Request-Error', 'CODE' => $code, 'DESCRIPTION' => $message));
		} else {
			if ($data == null) {
				echo json_encode(array('STATUS' => 'Success', 'CODE' => $code, 'MESSAGE' => $message));
			} else {
				echo json_encode(array('STATUS' => 'Success', 'CODE' => $code, 'MESSAGE' => $message, 'DATA' => $data));
			}
		}
	}

	// An SGI : SamayGnawIdentifier is a unique identifier used by us to identify the users
	protected function generateSGI($type, $phone = null)
	{
		$sgi = "";

		if ($type == "SGG") { // SGG = SamayGnawGnaw, used for a Gnaw. This SGI is formed by the string SGG + (the id of the latest inserted gnaw + 1)

			$query = "SELECT id FROM gnaws ORDER BY ID DESC LIMIT 1";

			try {

				$stmt = self::$_sqlCon->prepare($query);

				$stmt->execute();

				$result = $stmt->fetch(PDO::FETCH_ASSOC);

				$latestID = ($result['id']);
				$sgi = "SGG" . strval(((int)$latestID) + 1);
			} catch (Exception $e) {
				self::notify("uerr", "UNEX", "Due to an unexpected error, the SGI creation failed");
			}
		} else { // Other SGIs : SGS/SGC (SamayGnawSaloon/SamayGnawClient) are formed by the string defining them mixed with their phone number and a randomly generated number
			$num = strval($phone);
			$sgi = $num[0] . $num[1] . "$type-" . $num[2] . $num[3] . $num[4] . $num[5] . rand(1, 999) . $num[6] . $num[7] . $num[8];
		}

		return $sgi;
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

			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($results && $results !== null) {
				echo json_encode($results);
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
				echo json_encode($result);
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

			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($results && $results !== null) {
				echo json_encode($results);
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
				echo json_encode($result);
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

			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($results && $results !== null) {
				echo json_encode($results);
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
				echo json_encode($result);
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

	public function login($credentials)
	{
		/// aythia aythia
	}

	public function addClient($clientData)
	{

		$_lastName = $clientData->lastName;
		$_firstName = $clientData->firstName;
		$_phone = $clientData->phone;
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
		clients(sgi, nom, prenom, tel, genre, cou, epaule, poitrine, ceinture, tourBras, 
		tourPoignet, longManche, longPant, longTaille, longCaftan, tourCuisse, tourCheville)
		VALUES('$sgi', '$_lastName', '$_firstName', $_phone, '$_gender', $_cou, $_epaule, $_poitrine, 
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
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function viewGnaws($sgi)
	{

		$query = "SELECT prop, dateC, dateL, prix, avance, etat, type FROM gnaws WHERE salon = '$sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			$stmt->execute();

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($result && $result !== null) {
				echo json_encode($result);
			} else {
				parent::notify("s", "NGF", "No Gnaw Found for the given sgi : The query returned an empty result");
			}
		} catch (Exception $e) {
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}

	public function addGnaw($gnawData)
	{
		//informations
		$sgi = parent::generateSGI("SGG");
		$_prop = $gnawData->prop;
		$_saloon = $gnawData->saloon;
		$_dateL = $gnawData->dateL;
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
			parent::notify("uerr", "UNEX", "Due to an unexpected error, the operation can not proceed");
		}
	}
}
