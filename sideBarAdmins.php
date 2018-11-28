<?php
    if(!isset($_SESSION['userType']) || strcmp($_SESSION['userType'], "admin")!=0)
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
			<a href="dashboardAdmins.php">Dashboard</a>
		</li>
		<li>
			<a href="addProduct.php">Add New Product</a>
		</li>
        <li>
            <a href="orderHistory.php">Order History</a>
        </li>
		<li>
			<a href="contactUs.html">Contact Us</a>
		</li>
		<li>
			<a href="logOut.php">Log Out</a>
		</li>
	</ul>
</div>
