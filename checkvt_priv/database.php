<?php

//general error handling file
require_once('error_handling.php');

//assign variables for connection
$dsn = 'mysql:host=localhost;dbname=checkvt'; //data source name and database name
$username = 'root';
$password = '';

//try connecting
try {
    $db = new PDO($dsn, $username, $password); //the @ suppresses any errors for security purposes
    }
    //error handling with pdoexception
    catch (PDOException $e) {
    echo 'DSN Connection Failed: ' . $e->getMessage(); //the error caught is sent to the error log file 
    exit;
    }
?>