<?php
header("Content-Type: application/json; charset=UTF-8");
$result =[];
$result['successful'] = false;
include "authentication.php";
if(!authenticateUser("customer"))
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
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    $result["error"] = "only works with post method";
    die(json_encode($result, JSON_FORCE_OBJECT));
}
if(isset($_POST['productId']))
{
    try
    {
        $userId = $_SESSION['id'];
        $productId = $_POST['productId'];
        $conn->exec("SET autocommit=0;");
        $conn->beginTransaction();
        $query = "select quantity from shoppingCart where productId=:productId and userId=:userId";
        $params = [$productId, $userId];
        $paramsNamesInQuery = [":productId", ":userId"];
        $queryResult = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        $queryResult = $queryResult->fetch(PDO::FETCH_BOTH);
        $query = "delete from shoppingCart where productId=:productId and userId=$userId";
        $params = [$productId];
        $paramsNamesInQuery = [":productId"];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        $conn->commit();
        $result['successful'] = true;
        $result['quantity'] = $queryResult['quantity'];
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        $result["error"] = "There was an error with the database. Please try again.";
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
}
?>