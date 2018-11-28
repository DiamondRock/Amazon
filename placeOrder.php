<!DOCTYPE html>
<?php
$taxPercentage = 8.25;
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION['userType']) || $_SESSION['userType'] != 'customer')
{
    die('Access denied!');
}
try
{
    require_once "connectDB.php";
    $userId = $_SESSION['id'];
    $command = "SET autocommit=0;";
    $conn->exec($command);
    $command = "START TRANSACTION";
    $conn->exec($command);
    //orderId	productId	quantity	totalPrice;
    $date = date('Y-m-d H:i:s');
    $query = "insert into orders(userId , dateTime, totalPriceBeforeTax, tax) values($userId, '$date', 0, 0)";
    $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
    $orderId = getLastInsertId($conn);

    $query = "select name, productId, quantity, price, supplies from shoppingCart, products where userId=:userId and productId=products.id";
    $params = [$userId];
    $paramsNamesInQuery = [":userId"];
    $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
    $totalOrderPrice = 0;
    while ($row = $result->fetch(PDO::FETCH_BOTH))
    {
        $name = $row['name'];
        $productId = $row['productId'];
        $quantity = $row['quantity'];
        $supplies = $row['supplies'];
        if($supplies < $quantity)
        {
            $notEnoughSupplies = [];
            $notEnoughSupplies['productId'] = $productId;
            $notEnoughSupplies['productName'] = $name;
            $notEnoughSupplies['supplies'] = $supplies;
            $notEnoughSupplies['quantity'] = $quantity;
            throw new Exception("There is only {$supplies} items are left in our stock for product $name");
        }
        $totalItemPrice = $row['price'] * $quantity;
        $totalOrderPrice += $totalItemPrice;


        $query = "insert into orders_products(orderId, productId, quantity, totalPrice) values($orderId, $productId, $quantity, $totalItemPrice)";
        $params = [];
        $paramsNamesInQuery = [];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, true);

        $query = "delete from shoppingCart where userId=:userId and productId=$productId";
        $params = [$userId];
        $paramsNamesInQuery = [":userId"];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, false);

        $supplies -= $quantity;
        $query = "update products set supplies=$supplies where id=$productId";
        $params = [];
        $paramsNamesInQuery = [];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
    }

    $tax = $totalOrderPrice * $taxPercentage / 100;
    $query = "update orders set totalPriceBeforeTax=$totalOrderPrice, tax=$tax where orderId=$orderId";
    $params = [];
    $paramsNamesInQuery = [];
    executeQuery($conn, $query, $params, $paramsNamesInQuery, true);



    $command = "COMMIT";
    $conn->exec($command);
}
catch (Exception $e)
{
    $command = "ROLLBACK";
    $conn->exec($command);
    echo $e->getMessage();
    $error = "There was an error placing your order. Please try again.";
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="stylesheet" href="styles/placeOrder.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
</head>

<body>
<? include "header.php"; ?>
<? include "sideBar.php"; ?>
<div id = "content">
    <?php
    if(isset($notEnoughSupplies))
    {
        echo "There was a problem placing your order.<br/>";
        echo "You requested {$notEnoughSupplies['supplies']} items for product {$notEnoughSupplies['productName']}, but there are only {$notEnoughSupplies['quantity']} items left in our stock.";
    }
    else if(isset($error))
    {
        echo "There was an error placing your order. Please try again.";
    }
    else
    {
        echo "Your order has successfully been placed";
    }
    ?>
</div>
<? include "footer.php"; ?>
</body>
</html>


