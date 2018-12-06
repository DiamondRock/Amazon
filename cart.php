<!DOCTYPE html>
<?php
include "authentication.php";
if(!authenticateUser("customer"))
{
    die('Access denied!');
}
include "master.php";
$userId = $_SESSION['id'];
require_once "connectDB.php";
if(!isset($dbConnError) && $_SERVER['REQUEST_METHOD'] == 'POST')
{
    $insertError = "";
    try
    {
        $conn->exec("set autocommit=0");
        $conn->beginTransaction();
        $productId = $_POST['id'];
        $quantity = $_POST['quantity'];
        $query = "select supplies from products where id=:productId and deleted=0";
        $params = [$productId];
        $paramsNamesInQuery = [":productId"];
        $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        $result = $result->fetch(PDO::FETCH_NUM);
        if(empty($result))
        {
            $insertError = "Item not found.";
            throw new Exception($insertError);
        }
        if($result[0] < $quantity)
        {
            $insertError = "Not enough supplies in stock.";
            throw new Exception($insertError);
        }
        $query = "insert into shoppingCart (userId, productId, quantity) values($userId, :productId, :quantity)";
        $params = [$productId, $quantity];
        $paramsNamesInQuery = [":productId", ":quantity"];
        $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
        $conn->commit();
    }
    catch(Exception $e)
    {
        if(empty($insertError))
        {
            $insertError = "database error. Please try again.";
        }
        echo $e->getMessage();
        $conn->rollBack();
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
    if (isset($dbConnError))
    {
        echo "Error in database. Please try again.";
    }
    else
    {
        if(isset($insertError))
        {
            echo '<div id="insertMessage">';
            if(empty($insertError))
            {
                echo "The item was successfully added to your cart.";
            }
            else
            {
                echo $insertError;
            }
            echo "</div>";
        }
        try
        {
            $query = "select quantity, p.id as id, name, price, supplies, path as picturePath from shoppingCart as s left join products as p on s.productId=p.id left join images as i on i.id=p.pictureId where s.userId=$userId";
            $result = executeQuery($conn, $query, [], [], false);
            if($result->rowCount() == 0)
            {
                echo 'You have not added anything to your shopping cart yet';
            }
            else
            {
                $totalPrice = 0;
                echo '<table id="items">';
                echo "<tr><th></th><th>Name</th><th>Price</th><th>Quantity</th><th></th><th></th></tr>";
                while ($row = $result->fetch(PDO::FETCH_BOTH)) {
                    $quantity = $row['quantity'];
                    $id = $row['id'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $supplies = $row['supplies'];
                    $picturePath = $row['picturePath'];
                    if (empty($picturePath))
                    {
                        $picturePath = $GLOBALS["noImageAvailablePath"];
                    }
                    $totalPrice += floatval($price) * floatval($quantity);
                    echo "<tr id='$id'>";
                    echo "<td class='image'>" . "<img src=\"{$picturePath}\"/" . "</td>";
                    echo "<td title=\"$name\">" . $name . "</td>";
                    echo "<td>" . $price . "</td>";
                    echo "<td><select>";
                    echo "<option value='$quantity' selected='selected'>$quantity</option>";
                    for ($i = 1; $i <= min(20, $supplies); $i++)
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
        }
        catch(Exception $e)
        {
            echo "Error in database. Please try again.";
        }
    }
    ?>
</div>
<? include "footer.php"; ?>
</body>
</html>
