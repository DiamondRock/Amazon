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
	
	require_once "connectDB.php";
	
	if (isset($dbConnError))
    {
        die("Couldn't connect to database! Please try again.");
    }
    else
    {
        $errorInDb = false;
    }
	try
	{
		$id=$_REQUEST['id'];
		
		$query="SELECT p.id as id, name, description, price, supplies, i.path as picturePath FROM products as p, images as i where p.deleted = 0 and p.id=$id and p.pictureId=i.id";
		$params = [];
		$paramsNamesInQuery = [];
		$result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
		$row = $result->fetch(PDO::FETCH_BOTH);
		
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
	
?>


<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Amazon</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
	<link rel="stylesheet" href="styles/addProduct.css" />
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="scripts/addProduct.js"></script>
</head>

<body>
<? include "header.php"; ?>
<? include "sideBar.php"; ?>

	<div id="content">
		<div class="form">
			<div id="form_head">
				<h1 id="heading">Update Product</h1>
			</div>
			
			<?php
				$status = "";
				if(isset($_POST['new']) && $_POST['new']==1)
				{
					$id=$_REQUEST['id'];
					echo "id is ". $id;
					$name =$_REQUEST['name'];
					$description = $_REQUEST['description'];
					//$pictureId = $_REQUEST['pictureId']; Need to add code for pictureid here and in the query
					$price = $_REQUEST['price']; 
					$supplies = $_REQUEST['supplies']; 
					
					$query="SELECT MAX(id) from images";
					$params = [];
					$paramsNamesInQuery = [];
					$result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
					$row = $result->fetch(PDO::FETCH_BOTH);
					
					$pictureId = $row[0] + 1;

					if (($_FILES['my_file']['name']!="")){
						$target_dir = "images/products/";
						
						$file = $_FILES['my_file']['name'];
						$path = pathinfo($file);
						$filename = $path['filename'];
						$ext = $path['extension'];
						$temp_name = $_FILES['my_file']['tmp_name'];
						$path_filename_ext = $target_dir. ($pictureId).".".$ext;
					 
						// Check if file already exists
						if (file_exists($path_filename_ext)) {
							echo "Sorry, file already exists.";
						}
						else
						{
							move_uploaded_file($temp_name,$path_filename_ext);
							
							try{
								$query="insert into images (id, path) values (:pictureId, :path_filename_ext)";
								$params = [$pictureId, $path_filename_ext];
								$paramsNamesInQuery = [":pictureId", ":path_filename_ext"];
								$result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
							}
							catch(Exception $e){
								echo $e->getMessage();
							}		
						}
					}
					
					$query="update products set name='".$name."',description='".$description."', pictureId='".$pictureId."', price='".$price."', supplies='".$supplies."' where id='".$id."'"; 
					$params = [];
					$paramsNamesInQuery = [];
					$result = executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
					$status = "Record Updated Successfully. </br></br>
					<a href='view.php'>View Updated Record</a>";
					echo '<p style="color:#FF0000;">'.$status.'</p>';
				}
				else 
				{
			?>
			
			<div id = "form_body">
				<form name="form" method="post" action="" enctype="multipart/form-data"> 
					<div class="col-md-6" id = "form_body_left">
						<input type="hidden" name="new" value="1" />
						<input name="id" type="hidden" value="<?php echo $row['id'];?>" />
						<label>Product Name</label>
						<p><input type="text" name="name" placeholder="Enter Product Name" required value="<?php echo $row['name'];?>" /></p>
						<label>Product Description</label>
						<p><input type="text" ID= "desc" name="description" placeholder="Enter Product Description" required value="<?php echo $row['description'];?>" /></p>
						<label>Price</label>
						<p><input type="text" name="price" placeholder="Enter Price" required value="<?php echo $row['price'];?>" /></p>
						<label>Supplies</label>
						<p><input type="text" name="supplies" placeholder="Enter Supplies" required value="<?php echo $row['supplies'];?>" /></p>
						<p><input name="submit" type="submit" value="Update" /></p>
					</div>	
					
					<div class="col-md-6" id = "form_body_right">
						<div class="form-group">
							<label>Update Image</label>
							<div class="input-group">
								<span class="input-group-btn">
									<span class="btn btn-default btn-file">
										Browse… <input type="file" id="imgInp" name="my_file" required>
									</span>
								</span>
<!--								<span>-->
<!--									<input type="text" id = "imgText" class="form-control" readonly>-->
<!--								</span>-->
								
							</div>
							<img id='img-upload' src="<?php echo $row[5];?>"/>
						</div>
					</div>
				</form>
			</div>
				<?php }?>
			<div class= "form_footer">
				<p style="color:#FF0000; font-size: 26px;"><?php echo $status; ?></p>
			</div>		
		</div>
	</div>
	
<? include "footer.php"; ?>
</body>
</html>
