<!DOCTYPE html>
<?php
include "authentication.php";
if(!authenticateUser("customer"))
{
    die('Access denied!');
}
$taxPercentage = 8.25;
try
{
    require_once "connectDB.php";
    $userId = $_SESSION['id'];
    $conn->exec("SET autocommit=0;");
    $conn->beginTransaction();
    $date = date('Y-m-d H:i:s');
    $query = "insert into orders(userId , dateTime, totalPriceBeforeTax, tax) values($userId, '$date', 0, 0)";
    $result = executeQuery($conn, $query, [], [], true);
    $orderId = $conn->lastInsertId();

    $query = "select name, productId, quantity, price, supplies from shoppingCart, products where userId=$userId and productId=products.id";
    $result = executeQuery($conn, $query, [], [], false);
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
        executeQuery($conn, $query, [], [], true);

        $query = "delete from shoppingCart where userId=$userId and productId=$productId";
        executeQuery($conn, $query, [], [], false);

        $supplies -= $quantity;
        $query = "update products set supplies=$supplies where id=$productId";
        executeQuery($conn, $query, [], [], true);
    }

    $tax = $totalOrderPrice * $taxPercentage / 100;
    $query = "update orders set totalPriceBeforeTax=$totalOrderPrice, tax=$tax where orderId=$orderId";
    executeQuery($conn, $query, [], [], true);

    $conn->commit();
}
catch (Exception $e)
{
    $conn->rollBack();
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


