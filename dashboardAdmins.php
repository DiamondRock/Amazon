<!DOCTYPE html>
<?php
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
	if(!isset($_SESSION['userType']) || $_SESSION['userType'] != 'admin')
	{
		die('Access denied!');
	}
?>
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
	$reportError = "";
	if(!empty($_POST['startDate']) || !empty($_POST['reportType']))
    {
		if(empty($_POST['startDate']))
		{
			$reportError = "The start date field is empty";
		}
		else if(empty($_POST['reportType']))
		{
			$reportError = "You should specify the report type";
		}
		else
		{
			include ("report.php");
		}
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

</head>

<body>

    <div id="wrapper">

        <?php include("sidebar.php"); ?>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Toggle Menu</a>
				<form action="dashboardAdmins.php" method="post">
					<div class="container">
						<?php 
							require "connectDB.php";
							$command = "select PrBarrelPrice from PRICE where PrDate = '". date("Y-m-d"). "'";
							$result = $conn->query($command);
							if($result->rowCount() == 1)
							{
								?> Today's Price: <?php echo $result->fetch(PDO::FETCH_BOTH)[0]."<br>\n";
							}
							else
							{
								//echo $result->rowCount();
							}
						?>
						<label><b>Enter the start date:</b></label>
						<input type="text" name="startDate" required><br>
						<label><b>Enter the report type:</b></label>
						<input type="radio" name="reportType" value="d">Daily
						<input type="radio" name="reportType" value="w">Weekly
						<input type="radio" name="reportType" value="m">Monthly
						<br><button type="submit">Show the report!</button>
						<div style="color:blue">
							<?php
								if(isset($_POST['startDate']))
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
