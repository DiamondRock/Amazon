<?php
header("Content-Type: application/json; charset=UTF-8");
$result =[];
$result['successful'] = false;
include "authentication.php";
if(!authenticateUser("admin"))
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
        $query = "update products set deleted=1 where id=:productId";
        $params = [$productId];
        $paramsNamesInQuery = [":productId"];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        $result['successful'] = true;
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
    catch (Exception $e)
    {
        $result["error"] = "There was an error with the database. Please try again.";
        $result["error"] = $e->getMessage();
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
}
?>