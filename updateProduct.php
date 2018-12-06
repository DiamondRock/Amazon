<!DOCTYPE html>

<?php
include "master.php";
include "authentication.php";
if(!authenticateUser("admin"))
{
    die('Access denied!');
}
require_once "connectDB.php";
$productId = $_GET['id'];
if (!isset($dbConnError))
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        try
        {
            $updateError = "";
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $supplies = $_POST['supplies'];
            $category = $_POST['category'];
            $imageFileName = $_FILES['image']['name'];
            $deleteCurrentImage = $_POST['deleteCurrentImage'];
            $conn->exec("SET autocommit=0;");
            $conn->beginTransaction();
            if (!empty($imageFileName))
            {
                $query = "select max(id) from images";
                $result = executeQuery($conn, $query, [], [], false);
                $row = $result->fetch(PDO::FETCH_BOTH);
                $pictureId = $row[0] + 1;
                $target_dir = $GLOBALS['productImagesPath'];
                $path = pathinfo($imageFileName);
                $filename = $path['filename'];
                $ext = $path['extension'];
                $tempName = $_FILES['image']['tmp_name'];
                $pathFilenameExt = $target_dir . ($pictureId) . "." . $ext;
                while (true)
                {
                    if (!file_exists($pathFilenameExt))
                    {
                        break;
                    }
                    $pictureId += 1;
                    $pathFilenameExt = $target_dir . ($pictureId) . "." . $ext;
                }
                try
                {
                    move_uploaded_file($tempName, $pathFilenameExt);
                }
                catch (Exception $e)
                {
                    $updateError = "Image could not be stored. Please try again";
                    throw new Exception($error);
                }
                $query = "insert into images (id, path) values($pictureId, '$pathFilenameExt')";
                $result = executeQuery($conn, $query, [], [], true);
            }
            else
            {
                $pictureId = null;
            }
            $query = "select id as categoryId from categories where name=:category";
            $params = [$category];
            $paramsNamesInQuery = [':category'];
            $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
            if($result->rowCount() == 1)
            {
                $categoryId = $result->fetch(PDO::FETCH_BOTH)[0];
            }
            else
            {
                $categoryId = null;
            }
            if(!isset($deleteCurrentImage) && $pictureId == null)
            {
                $query = "update products set name=:name, description=:description, price=:price, supplies=:supplies, categoryId=:categoryId where id=:productId";
                $params = [$name, $description, $price, $supplies, $categoryId, $productId];
                $paramsNamesInQuery = [":name", ":description", ":price", ":supplies", ":categoryId", ":productId"];
            }
            else
            {
                $query = "update products set name=:name, description=:description, pictureId=:pictureId, price=:price, supplies=:supplies, categoryId=:categoryId where id=:productId";
                $params = [$name, $description, $pictureId, $price, $supplies, $categoryId, $productId];
                $paramsNamesInQuery = [":name", ":description", ":pictureId", ":price", ":supplies", ":categoryId", ":productId"];
            }
            executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
            $conn->commit();
        }
        catch (Exception $e)
        {
            if (empty($updateError))
            {
                $updateError = "Error in database. Please try again.";
            }
            $conn->rollBack();
        }
    }

}
if (!isset($dbConnError))
{
    try
    {
        $query = "select path as picturePath, p.name as name, price, description, supplies, c.name as category from  products as p left join images as i on  p.pictureId=i.id join categories as c on p.categoryId=c.id where p.id=:productId and p.deleted=0";
        $params = [$productId];
        $paramsNamesInQuery = [":productId"];
        $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        if($result->rowCount() == 0)
        {
            $error = 'Item does not exist';
        }
        else
        {
            $row = $result->fetch(PDO::FETCH_BOTH);
            $picturePath = $row['picturePath'];
            $name = $row['name'];
            $price = $row['price'];
            $description = $row['description'];
            $supplies = $row['supplies'];
            $category = $row['category'];
            if (empty($picturePath))
            {
                $picturePath = $GLOBALS['noImageAvailablePath'];
            }
        }
        $query="select name from categories order by name asc";
        $categories = executeQuery($conn, $query, [], [], false);
    }
    catch (Exception $e)
    {
        $error = 'There was an error in database. Please try again.';
    }
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
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
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
        else
        {
            if(isset($updateError))
            {
                if(empty($updateError))
                {
                    echo "The item was successfully updated.";
                }
                else
                {
                    echo $updateError;
                }
            }
            if(!empty($error))
            {
                echo $error;
            }
            else
            {
        ?>
		<div class="form">
			<div id="form_head">
				<h1 id="heading">Update Product</h1>
			</div>
			<div id = "form_body">
				<form name="form" method="post" action="" enctype="multipart/form-data" class="container">
                    <div class="row">
                        <div class="col-md" id = "form_body_left">
                            <input type="hidden" name="new" value="1" />
                            <input name="id" type="hidden" value="<?php echo $id;?>" />
                            <label>Product Name</label>
                            <p><input type="text" name="name" placeholder="Enter Product Name" required value="<?php echo $name;?>" /></p>
                            <label>Product Description</label>
                            <p><input type="text" ID= "desc" name="description" placeholder="Enter Product Description" required value="<?php echo $description;?>" /></p>
                            <label>Price</label>
                            <p><input type="text" name="price" placeholder="Enter Price" required value="<?php echo $price;?>" /></p>
                            <label>Supplies</label>
                            <p><input type="text" name="supplies" placeholder="Enter Supplies" required value="<?php echo $supplies?>" /></p>
                            <label>Category</label>
                            <?php
                            if($categories->rowCount() > 0)
                            {
                                echo '<select name="category" id="category">';
                                echo "<option>Select a category...</option>";
                                while($row = $categories->fetch(PDO::FETCH_BOTH))
                                {
                                    if($row[0] == $category)
                                    {
                                        echo "<option selected=\"selected\">". $row[0]. "</option>";
                                    }
                                    else
                                    {
                                        echo "<option>". $row[0]. "</option>";
                                    }
                                }
                                echo '</select>';
                            }
                            ?>
                            <p><input name="submit" type="submit" value="Update" class="btn btn-primary"/></p>
                        </div>

                        <div class="col-md" id = "form_body_right">
                            <div class="form-group">
                                <label>Update Image</label>
                                <?
                                if($picturePath != $GLOBALS['noImageAvailablePath'])
                                {
                                    ?>
                                    <br/>
                                    <label>Delete Current Image</label>
                                    <input type="checkbox" name="deleteCurrentImage"/>
                                    <?
                                }
                                ?>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Browse… <input type="file" id="imgInp" name="image">
                                        </span>
                                    </span>
                                </div>
                                <img id='img-upload' src="<?php echo $picturePath;?>"/>
                            </div>
                        </div>
                    </div>
				</form>
			</div>
		</div>
        <? }}?>
	</div>
	
<? include "footer.php"; ?>
</body>
</html>
