<!DOCTYPE html>
<?php
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	if(!isset($_SESSION['userType']) || $_SESSION['userType'] != 'customer')
	{
		die('Access denied!');
	}
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amazon</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>

</head>

<body>
<? include "header.php"; ?>
<? include "sideBar.php"; ?>
<div id = "content">

</div>
<? include "footer.php"; ?>
</body>
</html>
