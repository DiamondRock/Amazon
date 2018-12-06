<?php
function authenticateUser($userType)
{
    if (session_status() == PHP_SESSION_NONE)
    {
        session_start();
    }
    if(!isset($_SESSION['userType']) || $_SESSION['userType'] != $userType)
    {
        return false;
    }
    return true;
}
?>