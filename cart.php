<!DOCTYPE html>
<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION['userType']) || $_SESSION['userType'] != 'customer')
{
    die('Access denied!');
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
    <script src="scripts/cart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="stylesheet" href="styles/cart.css"/>
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
        die("Error in database. Please try again.");
    }
    $userId = $_SESSION['id'];
    $query = "select distinct path as picturePath, name, price, quantity, p.id as id from shoppingCart as s, products as p, images as i  where s.userId=:userId and s.productId=p.id and i.id=p.pictureId";
    $params = [$userId];
    $paramsNamesInQuery = [":userId"];
    $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
    if($result->rowCount() == 0)
    {
        echo 'You have not added anything to your shopping cart yet';
    }
    else
    {
        $totalPrice = 0;
        echo '<table id="items">';
        echo "<tr><th></th><th>Name</th><th>Price</th><th>Quantity</th><th></th><th></th></tr>";
        while ($row = $result->fetch(PDO::FETCH_BOTH))
        {
            $id = $row['id'];
            $picturePath = $row['picturePath'];
            $name = $row['name'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $totalPrice += floatval($price) * floatval($quantity);
            echo "<tr id='$id'>";
            echo "<td>" . "<img src=\"{$picturePath}\"/" ."</td>";
            echo "<td>" . $name ."</td>";
            echo "<td>" . $price ."</td>";

            echo "<td><select>";
            echo "<option value='$quantity' selected='selected'>$quantity</option>";
            for($i=1; $i < 20;$i++)
            {
                if ($i == $quantity)
                    continue;
                echo "<option value='$i'>$i</option>";
            }
            echo "</select></td>";
            echo "<td><button class='btn btn-update'>Update</button></td>";
            echo "<td><button class='btn btn-delete fa fa-trash'></button></td>";
            echo "</tr>";
        }
        echo "<tr><td style='font-weight: bold' id='totalPrice'>Total price: $totalPrice</td></tr>";
        echo "</table>";
        ?>
        <a href="placeOrder.php" class="btn btn-primary" id="placeOrder">Place the order!</a>
        <?
    }
    ?>
</div>
<? include "footer.php"; ?>
</body>
</html>


