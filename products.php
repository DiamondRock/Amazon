<!DOCTYPE html>
<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION['userType']))
{
    die('Access denied!');
}

$requiredPage = "showProducts";
if(isset($_GET['page']))
{
    $pages = array("showProducts");

    if(in_array($_GET['page'], $pages))
    {
        $_page=$_GET['page'];
    }
    else
    {
        $_page="showProducts";
    }
}
else
{
    $_page="showProducts";
}
include "pagination.php";
if(isset($_GET["page"]))
{
    $page_number = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if(!is_numeric($page_number))
    {
        die('Invalid page number!');
    }
}
else
{
    $page_number = 1; //if there's no page number, set it to 1
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
				  
				<?php require($_page.".php" ); ?>
	  
			</div><!--end of main--> 
		</div><!--end container--> 
	</div>
<? include "footer.php"; ?>
</body> 
</html>
