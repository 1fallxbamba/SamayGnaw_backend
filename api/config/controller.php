<?php

/**
 * SamayGnaw Controller v1.0, @authors : 1fallxbamba, bamba2001, cupsdareal
 */

class SamayGnawController
{
	protected static $_sqlCon;

	public function __construct()
	{
		$this->_connect();  // so that i automatically connects to the db
	}

	private function _connect() 
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

class SalonController extends SamayGnawController // thanks to heritage, parent's constructor is implicitly called, the connection to db is automatic
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
<<<<<<< HEAD
		clients(sgi, nom, prenom, tel, genre, cou, epaule, poitrine, ceinture, tourBras, 
		tourPoignet, longManche, longPant, longTaille, longCaftan, tourCuisse, tourCheville)
		VALUES('$_sgi', '$_lastName', '$_firstName', $_phone, '$_gender', $_cou, $_epaule, $_poitrine, 
		$_ceinture, $_tourBras, $_tourPoignet, $_longManche, $_longPant, $_longTaille, $_longCaftan, $_tourCuisse, $_tourCheville)";

=======
		clients(sgi, nom, prenom, tel, genre, cou, epaule, poitrine, ceinture, tourBras, tourPoignet, longManche, longPant, longTaille, longCaftan, tourCuisse, tourCheville)
		VALUES('$_sgi', '$_lastName', '$_firstName', $_phone, '$_gender', $_cou, $_epaule, $_poitrine, $_ceinture, $_tourBras, $_tourPoignet, $_longManche, $_longPant, $_longTaille, $_longCaftan, $_tourCuisse, $_tourCheville)";
              
>>>>>>> 91269f8963800630f528f962ffeb1ea464657900
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

<<<<<<< HEAD
			//function addgnaw
=======
	//function addgnaw
>>>>>>> 91269f8963800630f528f962ffeb1ea464657900

	public function addgnaw($gnawData) 
	{
		//informations

		$_sgi = $gnawData->sgi;
		$_prop = $gnawData->prop;

		//détails 
		$_salon = $gnawData->salon;
		$_dateL = $gnawData->dateL;
		$_prix = $gnawData->prix;
		$_avance = $gnawData->avance;
		$_type = $gnawData->type;


		
		$query = "INSERT INTO 
		gnaws(sgi, prop, salon, dateL, prix, avance, type )
<<<<<<< HEAD
		VALUES('$_sgi','$_prop', '$_salon', '$_dateL', $_prix, $_avance, '$_type')";
=======
		VALUES('$_sgi','$prop', '$_salon', '$_dateL', $_prix, $_avance, $_type)";
>>>>>>> 91269f8963800630f528f962ffeb1ea464657900

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute()) {

				parent::notify("ok", "welcome", "The new gnaw has been successfully added");
			} else {
				parent::notify("error", "unknown", "An unknown error has occured !");
			}

		} catch (Exception $e) {

			parent::notify("error", "problem", "Due to an unexpected error, the operation can not proceed");
		}
        
	}

<<<<<<< HEAD
}			//function  update gnaw

	public function updategnaw() {

				$sql = "UPDATE gnaws . $this->_sgi .  SET sgi = :sgi, dateL = :dateL, avance = :avance, etat = :etat WHERE sgi = :sgi";
			
				
				// On sécurise les données
				$this->sgi=htmlspecialchars(strip_tags($this->sgi));
				$this->dateL=htmlspecialchars(strip_tags($this->dateL));
				$this->avance=htmlspecialchars(strip_tags($this->avance));
				$this->etat=htmlspecialchars(strip_tags($this->etat));

				// On attache les variables
				$query->bindParam(':dateL', $this->dateL);
				$query->bindParam(':prix', $this->avance);
				$query->bindParam(':etat', $this->etat);;
				$query->bindParam(':sgi', $this->sgi);
				
				// On exécute
					
				
				try {
		
					$stmt = parent::$_sqlCon->prepare($query);
		
					if ($stmt->execute()) {
		
						parent::notify("ok", "welcome","you have updated ");
					} else {
						parent::notify("error", "unknown", "An unknown error has occured !");
					}
		
					} catch (Exception $e) {
		
						parent::notify("error", "problem", "Due to an unexpected error, the operation can not proceed");
					}
				
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

			if ($result) {
				echo json_encode($result);
			} else {
				parent::notify("s", "NMF", "No measurements found for this client");
			}
		} catch (Exception $e) {

		}
	}

	
	//function consult gnaw

	public function consultgnaw() {


		
		$query = "SELECT sgi, prop, salon, dateL, avance  FROM gnaws WHERE sgi = '$this->_sgi'";

		try {

			$stmt = parent::$_sqlCon->prepare($query); // to fix !!

			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($result) {
				echo json_encode($result);
			} else {
				parent::notify("s", "NMF", "No measurements found for this client");
			}
		} catch (Exception $e) {

		}
    
	}



=======
	//function consult gnaw

	public function consultgnaw($consult) {


		$_sgi = $consult->sgi;
		$_prop = $consult->prop;

		$_salon = $consult->salon;
		$_dateL = $consult->dateL;
		$_avance = $consult->avance;


		
		$query = "SELECT (sgi, prop, salon, dateL, avance ) FROM gnaws
		VALUES('$_sgi','$prop', '$_salon', '$_dateL', $_avance,)";

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute()) {

				parent::notify("ok", "welcome");
			} else {
				parent::notify("error", "unknown", "An unknown error has occured !");
			}

		} catch (Exception $e) {

			parent::notify("error", "problem", "Due to an unexpected error, the operation can not proceed");
		}
        
	}

		//function  update gnaw

	public function updategnaw($updatgnaw) {

		$_id = $updatgnaw->id;
		$_sgi = $updatgnaw->sgi;
		$_prop = $updatgnaw->prop;

		$_salon = $updatgnaw->salon;
		$_dateL = $updatgnaw->dateL;
		$_avance = $updatgnaw->avance;
		$_etat = $updatgnaw->etat;

				
		$sql = "UPDATE gnaws SET  sgi = ?, prop = ?, dateL = ?, avance = ?, etat = ? WHERE id = ?");
       

		$query->bindValue(':sgi', $_sgi, PDO::PARAM_STR);
		$query->bindValue(':prop', $_prop, PDO::PARAM_STR);
		$query->bindValue(':dateL', $_dateL, PDO::PARAM_INT);
		$query->bindValue(':avance', $_avance, PDO::PARAM_INT);
		$query->bindValue(':etat', $_etat, PDO::PARAM_INT);

		$query->execute();

		try {

			$stmt = parent::$_sqlCon->prepare($query);

			if ($stmt->execute()) {

				parent::notify("ok", "welcome","you have updated ");
			} else {
				parent::notify("error", "unknown", "An unknown error has occured !");
			}

			} catch (Exception $e) {

				parent::notify("error", "problem", "Due to an unexpected error, the operation can not proceed");
			}
        

		
	}

	
>>>>>>> 91269f8963800630f528f962ffeb1ea464657900

}
