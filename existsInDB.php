<?php
header("Content-Type: application/json; charset=UTF-8");
$result =[];
$result['successful'] = false;
require "connectDB.php";
if(isset($dbConnError))
{
    $result["error"] = "database error";
    die(json_encode($result, JSON_FORCE_OBJECT));
}
if($_SERVER['REQUEST_METHOD'] != 'GET')
{
    $result["error"] = "only works with get method";
    die(json_encode($result, JSON_FORCE_OBJECT));
}
if(isset($_GET['email']))
{
    $query = "select userId from emails where email=:email";
    $params = [$_GET['email']];
    $paramsNamesInQuery = [":email"];
    $queryResult = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
    if($queryResult->rowCount() == 0)
    {
        $result['successful'] = true;
        $result['exists'] = false;
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
    else
    {
        $result['successful'] = true;
        $result['exists'] = true;
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
}
else if(isset($_GET['username']))
{
    $query = "select username from users where username=:username";
    $params = [$_GET['username']];
    $paramsNamesInQuery = [":username"];
    $queryResult = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
    if($queryResult->rowCount() == 0)
    {
        $result['successful'] = true;
        $result['exists'] = false;
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
    else
    {
        $result['successful'] = true;
        $result['exists'] = true;
        die(json_encode($result, JSON_FORCE_OBJECT));
    }
}
else
{
    $result['error'] = "only works with infoType = email or user";
    die(json_encode($result, JSON_FORCE_OBJECT));
}
?>