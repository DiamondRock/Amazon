<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION['username']))
{
    include('headerNotLoggedIn.php');
}
if ($_SESSION['userType'] == "customer")
{
    include('headerCustomers.php');
}
else if ($_SESSION['userType'] == "admin")
{
    include('headerAdmins.php');
}
?>