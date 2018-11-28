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
        $command = "SET autocommit=0;";
        $conn->exec($command);
        $command = "START TRANSACTION";
        $conn->exec($command);
        $query = "select quantity from shoppingCart where productId=:productId and userId=:userId";
        $params = [$productId, $userId];
        $paramsNamesInQuery = [":productId", ":userId"];
        $queryResult = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        $queryResult = $queryResult->fetch(PDO::FETCH_BOTH);
        $query = "delete from shoppingCart where productId=:productId and userId=:userId";
        $params = [$productId, $userId];
        $paramsNamesInQuery = [":productId", ":userId"];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        $command = "COMMIT";
        $conn->exec($command);
        $result['successful'] = true;
        $result['quantity'] = $queryResult['quantity'];
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