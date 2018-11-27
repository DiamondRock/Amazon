<?php
session_start();
if(!isset($_SESSION['username']))
{
	header("refresh:0;url=login.html");
	die();
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') 
{
	$bookId = test_input($_GET['BookID']);	
	$username = $_SESSION['username'];
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
	$query = "SELECT * FROM shoppingCart WHERE BookID=$bookId AND UserName='$username'";

	$result = mysqli_query($conn, $query);
	
	if(mysqli_num_rows($result) > 0)
	{
		?>
		<p> You have already added this book to your cart!</p>
		<?php	
	}
	else
	{
		$query = "INSERT INTO shoppingCart VALUES('$username',$bookId)";
		$result = mysqli_query($conn, $query);
		if(!$result)
		{
			?>
				<p>There was an error, we couldn't add the book to your cart. Please try again.</p>
			<?php	
		}
	}
	
	$query = "SELECT DISTINCT BookTitle, ListPrice FROM BOOK, shoppingCart WHERE username='$username' and Book.BookID=shoppingCart.BookID";
	$result = mysqli_query($conn, $query);
	$totalPrice = 0;
	echo "<table class='table table-striped'><tr><td>Book Title</td><td>List Price</td></tr>";
	while($row = mysqli_fetch_array($result))
	{
		echo "<tr><td>". $row["BookTitle"] ."</td><td>". $row["ListPrice"]."</td></tr>";
		$totalPrice += $row["ListPrice"];
	}
	mysqli_close();
	?>
	<tr><td colspan="100%">Total: <?php echo $totalPrice; ?></td></tr>
	</table>
				<a href = "books.php">Continue Shopping</a>
				<br/>
				<a href = "logout.php">Logout</a>
			</div>
		</body>
	</html>
<?php
}

function test_input($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>