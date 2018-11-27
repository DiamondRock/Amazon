<?php
if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
session_unset();
session_destroy();
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amazon</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>

</head>

<body>
    <? include "header.php"; ?>
    <? include "sideBar.php"; ?>
    <div id = "content">
        <?
        $time = 2;
        echo "Successfully logged out.<br/>You will be automatically redirected to the login page after $time seconds";
        header("refresh:{$time};url=index.php");
        ?>
    </div>
    <? include "footer.php"; ?>
</body>
</html>
