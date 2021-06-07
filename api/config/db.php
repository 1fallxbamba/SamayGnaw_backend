<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');


// local database

$host = "localhost";
$db_name = "pharmacydb";
$username = "root";
$password = ""; 

try {
$con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

}

// show error
catch(PDOException $exception){
echo "Connection error: " . $exception->getMessage();
}
?>