<?php
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	if(isset($_SESSION['userType']))
	{
		if($_SESSION['userType'] == 'customer')
		{
			header ("location: dashboardCustomers.php");
		}
		if($_SESSION['userType'] == 'admin')
		{
			header ("location: dashboardAdmins.php");
		}
	}
	else
    {
        header("location: login.php");
    }
?>