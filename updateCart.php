<?php
header("Content-Type: application/json; charset=UTF-8");
$result =[];
$result['successful'] = false;
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION['userType']) || $_SESSION['userType'] != 'customer')
{
    $result["error"] = "Access denied";
    die(json_encode($result, JSON_FORCE_OBJECT));
}
require "connectDB.php";
if(isset($dbConnError))
{
    $result["error"] = "There was an error connecting to our database. Please try again";
    die(json_encode($result, JSON_FORCE_OBJECT));
}
if($_SERVER['REQUEST_METHOD'] != 'GET')
{
    $result["error"] = "only works with get method";
    die(json_encode($result, JSON_FORCE_OBJECT));
}
if(isset($_GET['productId']))
{
    try
    {
        $userId = $_SESSION['id'];
        $productId = $_GET['productId'];
        $selectedQuantity = $_GET['selectedQuantity'];
        $command = "SET autocommit=0;";
        $conn->exec($command);
        $command = "START TRANSACTION";
        $conn->exec($command);
        $query = "select supplies from products where id=:id and deleted=0";
        $params = [$productId];
        $paramsNamesInQuery = [":id"];
        $queryResult = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        if($queryResult->rowCount() == 0)
        {
            $queryResult["error"] = "The item does not exist anymore.";
            die(json_encode($queryResult, JSON_FORCE_OBJECT));
        }
        $queryResult = $queryResult->fetch(PDO::FETCH_BOTH);
        if($selectedQuantity > $queryResult[0])
        {
            $result["error"] = "Not enough supplies";
            $result["supplies"] = $queryResult[0];
            die(json_encode($result, JSON_FORCE_OBJECT));
        }

        $query = "update shoppingCart set quantity=$selectedQuantity where productId=:productId";
        $params = [$productId];
        $paramsNamesInQuery = [":productId"];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
        $command = "COMMIT";
        $conn->exec($command);
        $result['successful'] = true;
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
    catch (Exception $e)
    {
        $command = "ROLLBACK";
        $conn->exec($command);
        $result["error"] = "There was an error with the database. Please try again.";
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
}
?>