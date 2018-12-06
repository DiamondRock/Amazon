<!DOCTYPE html>
<?php
    include "master.php";
    include "authentication.php";
    if(!authenticateUser("admin"))
    {
        die('Access denied!');
    }
    require_once "connectDB.php";
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
        require_once "connectDB.php";
        if (!isset($dbConnError))
        {
            try
            {
                $error = "";
                $name =$_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $supplies = $_POST['supplies'];
                $category = $_POST['category'];
                $imageFileName = $_FILES['image']['name'];
                $deleted = 0;
                $conn->exec("SET autocommit=0;");
                $conn->beginTransaction();
                if (!empty($imageFileName))
                {
                    $query="select max(id) from images";
                    $params = [];
                    $paramsNamesInQuery = [];
                    $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
                    $row = $result->fetch(PDO::FETCH_BOTH);
                    $pictureId = $row[0] + 1;
                    $target_dir = $GLOBALS['productImagesPath'];
                    $path = pathinfo($imageFileName);
                    $filename = $path['filename'];
                    $ext = $path['extension'];
                    $tempName = $_FILES['image']['tmp_name'];
                    $pathFilenameExt = $target_dir. ($pictureId).".".$ext;
                    while(true)
                    {
                        if(!file_exists($pathFilenameExt))
                        {
                            break;
                        }
                        $pictureId += 1;
                        $pathFilenameExt = $target_dir. ($pictureId).".".$ext;
                    }
                    try
                    {
                        move_uploaded_file($tempName, $pathFilenameExt);
                    }
                    catch (Exception $e)
                    {
                        $error = "Image could not be stored. Please try again";
                        throw new Exception($error);
                    }
                    $query="insert into images (id, path) values ($pictureId, '$pathFilenameExt')";
                    $result = executeQuery($conn, $query, [], [], true);
                }
                else
                {
                    $pictureId = null;
                }
                $query = "select id as categoryId from categories where name=:category";
                $params = [$category];
                $paramsNamesInQuery = [":category"];
                $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
                if($result->rowCount() == 1)
                {
                    $categoryId = $result->fetch(PDO::FETCH_BOTH)[0];
                }
                else
                {
                    $categoryId = null;
                }
                $query = "insert into products (name, description, pictureId, price, supplies, categoryId, deleted) values  (:name, :description, :pictureId, :price, :supplies, :categoryId, :deleted)";
                $params = [$name, $description, $pictureId, $price, $supplies, $categoryId, $deleted];
                $paramsNamesInQuery = [":name", ":description", ":pictureId", ":price", ":supplies", ":categoryId", ":deleted"];
                executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
                $conn->commit();
            }
            catch (Exception $e)
            {
                if(empty($error))
                {
                    $error = "Error in database. Please try again";
                }
                echo $e->getMessage();
                $conn->rollBack();
            }
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="scripts/addProduct.js"></script>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
    <link rel="stylesheet" href="styles/addProduct.css" />
</head>

<body>
<? include "header.php"; ?>
<? include "sideBar.php"; ?>

	<div id="content">
        <?
        if(isset($dbConnError))
        {
            echo "There was a problem connecting to the database. Please try again.";
        }
        else if(isset($error))
        {
            if(empty($error))
            {
                echo "The item was successfully added.";
            }
            else
            {
                echo $error;
            }
        }
        ?>
		<div class="form">
			<div id="form_head">
				<h1 id="heading">Add New Product</h1>
			</div>
			<div id = "form_body" class="container">

				<form name="form" method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md" id = "form_body_left">
                            <input type="hidden" name="new" value="1" />
                            <label>Product Name</label>
                            <p><input type="text" name="name" placeholder="Enter Product Name" required /></p>
                            <label>Product Description</label>
                            <p><input type="text" ID= "desc" name="description" placeholder="Enter Product Description" required /></p>
                            <label>Price</label>
                            <p><input type="text" name="price" placeholder="Enter Price" required /></p>
                            <label>Supplies</label>
                            <p><input type="text" name="supplies" placeholder="Enter Supplies" required /></p>
                            <?php
                            try
                            {
                                if(isset($dbConnError))
                                {
                                    throw new Exception();
                                }
                                $query="select name from categories order by name asc";
                                $categories = executeQuery($conn, $query, [], [], false);
                                if($categories->rowCount() > 0)
                                {
                                    echo "<label>Category</label>";
                                    echo '<p>';
                                    echo '<select name="category" id="category">';
                                    echo "<option>Select a category...</option>";
                                    while($row = $categories->fetch(PDO::FETCH_BOTH))
                                    {
                                        if($row[0] == "2")
                                        {
                                            echo "<option selected=\"selected\">". $row[0]. "</option>";
                                        }
                                        else
                                        {
                                            echo "<option>". $row[0]. "</option>";
                                        }
                                    }
                                    echo '</select>';
                                    echo '</p>';
                                }
                            }
                            catch (Exception $e)
                            {
                                echo "came here";
                                echo $e->getMessage();
                            }

                            ?>
                            <p><input name="submit" type="submit" value="Submit" class="btn btn-primary"/></p>
                        </div>

                        <div class="col-md" id = "form_body_right">
                            <div class="form-group">
                                <label>Upload Image</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Browse… <input type="file" id="imgInp" name="image"/>
                                        </span>
                                    </span>

                                </div>
                                <img id='img-upload'/>
                            </div>
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



