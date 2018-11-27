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
	$paymentError = "";
	if(!empty($_POST['customersUserName']) || !empty($_POST['customersPaymentAmount']))
    {
		if(empty($_POST['customersUserName']))
		{
			$paymentError = "Customor's username field is empty";
		}
		else if(empty($_POST['customersPaymentAmount']))
		{
			$paymentError = "You should specify the amount";
		}
		else
		{
			include ("tradersPaymentTransaction.php");
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
				<form action="tradersPayment.php" method="post">
					<div class="container">
						<label><b>Enter the customer's username:</b></label>
						<input type="text" name="customersUserName" required><br>
						<label><b>Enter the amount they want to pay:</b></label>
						<input type="text" name="customersPaymentAmount" required><br>
						<br><button type="submit">Confirm!</button>
						<div style="color:red">
							<?php
								if(isset($_POST['customersUserName']))
								{
									if($paymentError == "")
									{
										echo "The order was placed successfully.";
									}
									else
									{
										echo $paymentError;
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