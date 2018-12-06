<!DOCTYPE html>

<?php
include "master.php";
include "authentication.php";
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!authenticateUser('customer'))
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
if(!isset($_GET['id']))
{
    header('location: dashboardCustomers.php');
}
$productId = $_GET['id'];

try
{
    $query = "select path as picturePath, name, price, description, supplies from  products as p left join images as i on  p.pictureId=i.id where p.id=:productId and p.deleted=0";
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
        if(empty($picturePath))
        {
            $picturePath = $GLOBALS['noImageAvailablePath'];
        }
    }
}
catch (Exception $e)
{
    $error = 'There was an error in database. Please try again.';
}

?>


<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>Amazon</title>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
    <link rel="stylesheet" href="styles/addProduct.css" />
</head>

<body>
<? include "header.php"; ?>
<? include "sideBar.php"; ?>

<div id="content">
    <?
    if(isset($error))
    {
        echo $error;
    }
    else
    {

        ?>
        <div class="form">
            <div id="form_head">
                <h1 id="heading">Item Details</h1>
            </div>
            <div id="form_body" class="container">
                <form name="form" method="post" action="cart.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md" id="form_body_left">
                            <input type="hidden" name="id" value="<?echo $productId;?>"/>
                            <label>Product Name</label>
                            <p>
                                <? echo $name;?>
                            </p>
                            <label>Product Description</label>
                            <p>
                                <? echo $description;?>
                            </p>
                            <label>Price</label>
                            <p>
                                <? echo $price;?>
                            </p>
                            <label>Quantity</label>
                            <p>
                                <select name = "quantity" class="form-control">
                                    <?
                                    for($i=1; $i < 20;$i++)
                                    {
                                        if($i > $supplies)
                                        {
                                            break;
                                        }
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </p>
                            <p><input class="btn btn-primary" name="addToCart" type="submit" value="Add to Cart"/></p>
                        </div>

                        <div class="col-md" id="form_body_right">
                            <div class="form-group">
                                <img id='img-upload' src="<? echo $picturePath;?>"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="form_footer">
                <p style="color:#FF0000; font-size: 26px;"><?php echo $status; ?></p>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<? include "footer.php"; ?>
</body>
</html>



