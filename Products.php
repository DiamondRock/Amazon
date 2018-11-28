<!DOCTYPE html>
<?php

// Munia did
 
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	if(!isset($_SESSION['userType']) || $_SESSION['userType'] != 'admin')
	{
		die('Access denied!');
	} 
	
    require_once "connectDB.php";
	if (isset($dbConnError))
    {
        die("Couldn't connect to database! Please try again.");
    }
    else
    {
        $errorInDb = false;
    }
	
    if(isset($_GET['page'])){ 
          
        $pages=array("showProducts"); 
          
        if(in_array($_GET['page'], $pages)) { 
              
            $_page=$_GET['page']; 
              
        }else{ 
              
            $_page="showProducts"; 
              
        } 
          
    }else{ 
          
        $_page="showProducts"; 
          
    } 
  
?> 


<html lang="en">
<head> 
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amazon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="scripts/showProducts.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
    <link rel="stylesheet" href="styles/showProducts.css"/>
  
</head> 
  
<body> 
<? include "header.php"; ?>
<? include "sideBar.php"; ?>
	<div id = "content">  
		<div id="container"> 
	  
			<div id="main"> 
				  
				<?php require($_page.".php"); ?> 
	  
			</div><!--end of main--> 
		</div><!--end container--> 
	</div>
<? include "footer.php"; ?>
</body> 
</html>
