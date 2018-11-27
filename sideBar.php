<?php
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	if(!isset($_SESSION['username']))
	{
		include('sideBarNotLoggedIn.php');
	}
	if ($_SESSION['userType'] == "customer")
	{
		include('sideBarCustomers.php');
	}
	else if ($_SESSION['userType'] == "admin")
	{
		include('sideBarAdmins.php');
	}
?>