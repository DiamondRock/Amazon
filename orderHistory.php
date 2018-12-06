<!DOCTYPE html>
<?php
include "authentication.php";
if(!authenticateUser("customer"))
{
    die("Access denied!");
}

?>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amazon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="scripts/orderHistory.js"></script>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
    <link rel="stylesheet" href="styles/orderHistory.css"/>

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
    else
    {
        try
        {
            $userId = $_SESSION['id'];
            $query = "select orderId, dateTime, totalPriceBeforeTax, tax from orders where userId=$userId";
            $result = executeQuery($conn, $query, [], [], false);
            if($result->rowCount() == 0)
            {
                echo 'You have no order history';
            }
            else
            {
                echo '<table id="orders" class="table table-striped">';
                echo '<thead class="">';
                echo "<tr><th>Order Id</th><th>Date & Time</th><th>Total price before tax</th><th>Tax</th><th>Total Price</th><th></th></tr>";
                echo "</thead>";
                while ($row = $result->fetch(PDO::FETCH_BOTH))
                {
                    $id = $row['orderId'];
                    $dateTime = $row['dateTime'];
                    $totalPriceBeforeTax = $row['totalPriceBeforeTax'];
                    $tax = $row['tax'];
                    echo "<tr id=\"$id\">";
                    echo "<td>" . $id ."</td>";
                    echo "<td>" . $dateTime ."</td>";
                    echo "<td>" . $totalPriceBeforeTax ."</td>";
                    echo "<td>" . $tax ."</td>";
                    echo "<td>" . ($totalPriceBeforeTax + $tax) ."</td>";
                    ?> <td><button class="btn btn-primary"/><a href="showOrder.php?id=<?echo $id;?>">Show Order</a></td> <?
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
        catch (Exception $e)
        {
            echo "Error in database. Please try again.";
        }
    }

    ?>
</div>
<? include "footer.php"; ?>
</body>
</html>