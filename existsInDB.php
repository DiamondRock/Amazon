<?php
require "connectDB.php";
if(isset($dbConnError))
{
    die("database error");
}
if($_SERVER['REQUEST_METHOD'] != 'GET')
{
    die("only works with get method");
}
if(isset($_GET['email']))
{
    $query = "select userId from emails where email=:email";
    $params = [$_GET['email']];
    $paramsNamesInQuery = [":email"];
    $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
    if($result->rowCount() == 0)
    {
        die("false");
    }
    else
    {
        die("true");
    }
}
else if(isset($_GET['username']))
{
    $query = "select username from users where username=:username";
    $params = [$_GET['username']];
    $paramsNamesInQuery = [":username"];
    $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
    if($result->rowCount() == 0)
    {
        die("false");
    }
    else
    {
        die("true");
    }
}
else
{
    die("only works with infoType = email or user");
}
?>