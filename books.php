<?php
	session_start();
	if(isset($_SESSION['username']))
	{
		$user = 'root';
		$password = 'root';
		$db = 'bookstore';
		$host = 'localhost';
		$port = 3306;

		$conn = mysqli_connect(
		   $host, 
		   $user, 
		   $password, 
		   $db,
		   $port
		);


		if (!$conn){

			echo "Connection failed!";
			exit;

		}
		?>
		<!DOCTYPE html>
		<html>
			<head>
				<link rel="stylesheet" href="style/books.php.css">
			</head>
			<body>
				<div id ="mainContent">
		<?php
		
		$sql = "SELECT * FROM Book";

		$result = mysqli_query($conn, $sql);
		
		if($result->num_rows > 0)
		{
			
			echo "<table class='table table-striped'><tr><td>Book Title</td><td>List Price</td></tr>";

			while($row = mysqli_fetch_array($result))
			{
				echo "<tr><td>". $row["BookTitle"] ."</td><td>". $row["ListPrice"]."</td><td><a href='cart.php?BookID=".$row['BookID']."'>Add to Cart</a></td></tr>";

			}
			echo "</table>";
		}
		mysqli_close();
		?>
					<a href = "logout.php">Logout</a>
				</div>
			</body>
		</html>
		<?php
	}
	else
	{
		header('Location: login.html');
		die();
	}
?>