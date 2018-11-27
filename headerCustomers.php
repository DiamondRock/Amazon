<?php
if(!isset($_SESSION['userType']) || strcmp($_SESSION['userType'], "customer")!=0)
{
    die("Access denied!");
}
?>
<div id="header">
    <ul>
        <li>
            <a href="#home">Home</a>
        </li>
        <li>
            <a href="#about">About</a>
        </li>
        <li>
            <a href="#contact">Contact</a>
        </li>
    </ul>
    <ul>
        <li>
            <a href="logout.php">Log out</a>
        </li>
    </ul>
</div>
