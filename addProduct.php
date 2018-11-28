<!DOCTYPE html>

<?php

// Munia did

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
	
	
	$status = "";
	
	if(isset($_POST['new']) && $_POST['new']==1)
	{
		$name =$_REQUEST['name'];
		$description = $_REQUEST['description'];
		$price = $_REQUEST['price']; 
		$supplies = $_REQUEST['supplies']; 
		$deleted = 0;
		
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
		
		try
		{
		
		$query = "insert into products (name, description, pictureId, price, supplies, deleted) values  (:name, :description, :pictureId, :price, :supplies, :deleted)";
        $params = [$name, $description, $pictureId, $price, $supplies, $deleted];
        $paramsNamesInQuery = [":name", ":description", ":pictureId", ":price", ":supplies", ":deleted"];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
		$status = "Product Inserted Successfully.</br></br><a href='updateProducts.php'>View Inserted Record</a>";
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}

	}
?> 


<html lang="en">
<head>
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
				<h1 id="heading">Add New Product</h1>
			</div>
			<div id = "form_body">
				<form name="form" method="post" action="" enctype="multipart/form-data"> 
					<div class="col-md-6" id = "form_body_left">
						<input type="hidden" name="new" value="1" />
						<label>Product Name</label>
						<p><input type="text" name="name" placeholder="Enter Product Name" required /></p>
						<label>Product Description</label>
						<p><input type="text" ID= "desc" name="description" placeholder="Enter Product Description" required /></p>
						<label>Price</label>
						<p><input type="text" name="price" placeholder="Enter Price" required /></p>
						<label>Supplies</label>
						<p><input type="text" name="supplies" placeholder="Enter Supplies" required /></p>
						<p><input name="submit" type="submit" value="Submit" /></p>
					</div>	
					
					<div class="col-md-6" id = "form_body_right">
						<div class="form-group">
							<label>Upload Image</label>
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
							<img id='img-upload'/>
						</div>
					</div>
				</form>
			</div>
			<div class= "form_footer">
				<p style="color:#FF0000; font-size: 26px;"><?php echo $status; ?></p>
			</div>		
		</div>
	</div>
<? include "footer.php"; ?>
</body>
</html>



