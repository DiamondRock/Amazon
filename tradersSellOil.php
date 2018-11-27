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
	$sellError = "";
	if(!empty($_POST['barrelsNum']) || !empty($_POST['commissionType']))
    {
		if(empty($_POST['barrelsNum']))
		{
			$sellError = "Barrels field is empty";
		}
		else if(empty($_POST['commissionType']))
		{
			$sellError = "You should specify the commission type";
		}
		else
		{
			include ("tradersSellTransaction.php");
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
				<form action="tradersSellOil.php" method="post">
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
						<label><b>Number of Barrels that the customer wants to sell:</b></label>
						<input type="text" name="barrelsNum" required><br>
						<label><b>How the customer wants to pay the commission:</b></label>
						<input type="radio" name="commissionType" value="O">By Oil
						<input type="radio" name="commissionType" value="C">By Payment (Cash)
						<br>
						<label><b>Enter the customer's username:</b></label>
						<input type="text" name="customersUserName" required><br>
						<br><button type="submit">Sell!</button>
						<div style="color:red">
							<?php
								if(isset($_POST['barrelsNum']))
								{
									if($sellError == "")
									{
										echo "The order was placed successfully.";
									}
									else
									{
										echo $sellError;
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
