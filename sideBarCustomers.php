<?php
    if(!isset($_SESSION['userType']) || strcmp($_SESSION['userType'], "customer")!=0)
    {
        die("Access denied!");
    }
?>
<div id="sideBar">
	<ul class="sidebar-nav">
		<li class="sidebar-brand">
			<a href="#">
				Amazon
			</a>
		</li>
		<li>
			<a href="dashboardCustomers.php">Dashboard</a>
		</li>
		<li>
			<a href="customersBuyOil.php">Buy Oil</a>
		</li>
		<li>
			<a href="customersSellOil.php">Sell Oil</a>
		</li>
		<li>
			<a href="aboutUs.html">About Us</a>
		</li>
		<li>
			<a href="contactUs.html">Contact Us</a>
		</li>
		<li>
			<a href="logOut.php">Log Out</a>
		</li>
	</ul>
</div>
