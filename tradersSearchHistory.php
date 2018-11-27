<!DOCTYPE html>
<?php
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	if(!isset($_SESSION['userName']))
	{
		header ('location: index.php');
	}
	if($_SESSION['userType'] == 'customer')
	{
		header ("location: dashboardCustomers.php");
	}
	if($_SESSION['userType'] == 'manager')
	{
		header ("location: dashboardAdmins.php");
	}
	$reportError = "";
	if(empty($_POST['zipCode']) && empty($_POST['userName']))
    {
		$reportError = "You should specify either the zip code or the user name.";
    }
	else
	{
		include ("TradersSearchHistoryReport.php");
	}
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Oil Company</title>

    <!-- Bootstrap core CSS -->
    <link href="startbootstrap-simple-sidebar-gh-pages/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="startbootstrap-simple-sidebar-gh-pages/css/simple-sidebar.css" rel="stylesheet">
	<style>
		<!--#tableview 
		{
			width: 1000px;
			overflow: scroll;
		}-->
	</style>
</head>

<body>

    <div id="wrapper">

        <?php include("sidebar.php"); ?>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Toggle Menu</a>
				<form action="tradersSearchHistory.php" method="post">
					<div class="container">
						<label><b>Enter the customer's zip code:</b></label>
						<input type="text" name="zipCode"><br>
						<b>Or</b><br>
						<label><b>Enter the customer's user name:</b></label>
						<input type="text" name="userName"><br>
						<br><button type="submit">Show the report!</button>
						<div style="color:blue">
							<?php
								if(isset($_POST['zipCode']))
								{
									if($reportError == "")
									{
										echo $report;
									}
									else
									{
										echo $reportError;
									}
								}
							?>
						</div>
						<!-- <input type="checkbox" checked="checked"> Remember me-->
					</div>
				</form>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="startbootstrap-simple-sidebar-gh-pages/vendor/jquery/jquery.min.js"></script>
    <script src="startbootstrap-simple-sidebar-gh-pages/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>








</body>
</html>
