<?php
    require "db.php";
	$host = 'localhost';
	$port = 3306;
	$dbUsername = "root";
	$dbPassword = "root";
	$dbName = "Amazon";
	try
    {
        $conn = connectToDB($host, $port, $dbUsername, $dbPassword, $dbName);
    }
	catch(PDOException $e)
    {
        $dbConnError = "Connection failed: ". $e->getMessage();
    }
?>