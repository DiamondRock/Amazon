<?php
	$servername = "localhost";
	$dbusername = "root";
	$dbpassword = "NEWPASSWORD";
	//$dbname = "OilComp";
	//$dbname = "mydbpdo";
	try 
	{
		require "connectDB.php";
		try
		{
			$command = file_get_contents("CreateOilCompany-MySQL.txt");
			$conn->exec($command);
			echo "The database and its tables were created successfully!";
		}
		catch (Exception $e)
		{
			echo $e.getMessage();
		}
	}
	catch(PDOException $e)
	{
		$dbConnError = $e->getMessage;
	}
?>