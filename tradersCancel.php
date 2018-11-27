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
	$cancelError = "";
	if(!empty($_POST['transactionId']))
    {
		if(empty($_POST['transactionId']))
		{
			$cancelError = "Transcation's id field is empty";
		}
		else
		{
			include ("tradersCancelTransaction.php");
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
				<form action="tradersCancel.php" method="post">
					<div class="container">
						<label><b>Enter the transcation's id:</b></label>
						<input type="text" name="transactionId" required><br>
						<br><button type="submit">Confirm!</button>
						<div style="color:red">
							<?php
								if(isset($_POST['transactionId']))
								{
									if($cancelError == "")
									{
										echo "The transaction was canceled successfully.";
									}
									else
									{
										echo $cancelError;
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
