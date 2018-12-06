<!DOCTYPE html>
<?php
include "authentication.php";
if(!authenticateUser("customer"))
{
    die('Access denied!');
}
include "master.php";
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amazon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="stylesheet" href="styles/showOrder.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
</head>

<body>
<? include "header.php"; ?>
<? include "sideBar.php"; ?>
<div id = "content">
    <?
    require_once "connectDB.php";
    if (isset($dbConnError))
    {
        echo "Error in database. Please try again.";
    }
    else if(isset($_GET['id']))
    {
        try
        {
            $userId = $_SESSION['id'];
            $orderId = $_GET['id'];
            $query = "select dateTime, totalPriceBeforeTax, tax, o_p.quantity as quantity, totalPrice, name, p.id as productId, path as picturePath from orders as o join orders_products as o_p on o.orderId=o_p.orderId join products as p on p.id=o_p.productId join images as i on i.id=p.pictureId where userId=$userId and o.orderId=:orderId";
            $params = [$orderId];
            $paramsNamesInQuery = [":orderId"];
            $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
            if ($result->rowCount() == 0)
            {
                echo 'Order ID does not exist or you do not have permission to see it';
            }
            else
            {
                echo '<div id="items" class="table">';
                echo "<a><div class='row'><div class='cell'>Name</div><div class='cell'>Price</div><div class='cell'>Quantity</div></div></a>";
                while ($row = $result->fetch(PDO::FETCH_BOTH))
                {
                    $dateTime = $row["dateTime"];
                    $totalPriceBeforeTax = $row["totalPriceBeforeTax"];
                    $tax = $row["tax"];
                    $quantity = $row["quantity"];
                    $totalPrice = $row["totalPrice"];
                    $name = $row['name'];
                    $productId = $row['productId'];
                    $picturePath = $row['picturePath'];
                    if(empty($picturePath))
                    {
                        $picturePath = $GLOBALS["noImageAvailablePath"];
                    }
                    echo "<a href='showProduct.php?id=$productId'>";
                    echo "<div class='row' id='$productId'>";
                    echo "<div class = 'cell image'>" . "<img style='display:inline-block' src=\"{$picturePath}\">" . "</div>";
                    echo "<div class = 'cell name' title=\"$name\">" . $name . "</div>";
                    echo "<div class = 'cell'>" . ($totalPrice/$quantity) . "</div>";
                    echo "<div class = 'cell'>" . $quantity . "</div>";
                    echo "<div class = 'cell'><button class='btn btn-update'>Buy Again</button></div>";
                    echo "</div></a>";
                }
                echo "<div class='row' id='totalPrice'>";
                echo "<div class = 'cell'>Total price before tax: $totalPriceBeforeTax</div>";
                echo "<div class = 'cell'>Tax: $tax</div>";
                echo "<div class = 'cell'>Total price: ". ($totalPriceBeforeTax + $tax) ."</div>";
                echo "</div>";
                echo "</div>";
            }
        }
        catch (Exception $e)
        {
            echo "Error in database. Please try again!";
            echo $e->getMessage();
        }
    }
    ?>
</div>
<? include "footer.php"; ?>
</body>
</html>


