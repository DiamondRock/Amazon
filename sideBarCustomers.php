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
			<a href="aboutUs.php">About Us</a>
		</li>
		<li>
			<a href="contactUs.php">Contact Us</a>
		</li>
	</ul>
</div>
