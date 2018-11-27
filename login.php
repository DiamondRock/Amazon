<!DOCTYPE html>
<?php
    function userPassFoundInDBAndSetSessionVars($conn, $username, $password, &$loginError)
    {
        $query = "select id, password, userType from users where username=:username";
        $params = [$username];
        $paramsNamesInQuery = [":username"];
        try
        {
            $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
            if($result->rowCount() == 0)
            {
                $loginError = "username and/or password are wrong";
                return false;
            }
            else
            {
                $result = $result->fetch(PDO::FETCH_BOTH);
                $hashedPassword = $result['password'];
                echo password_hash($password, PASSWORD_BCRYPT)."\n";
                if(password_hash($password, PASSWORD_BCRYPT) == $hashedPassword)
                {
                    echo "It's the same!";
                }

                if(!password_verify($password, $hashedPassword))
                {
                    echo "Password not verified\n$hashedPassword";
                    echo "\n$2y$10\$DXq3XazHeMIypPp8Gy6Uxu7brKcE9.6Sd";
                    $loginError = "username and/or password are wrong";
                    return false;
                }
                else
                {
                    $_SESSION['username'] = $username;
                    $_SESSION['userType'] = $result['userType'];
                    $_SESSION['id'] = $result['id'];
                    return true;
                }
            }
        }
        catch (Exception $e)
        {
            $loginError = "There was an error with our database. Please try again.";
            return false;
        }
    }

	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}

	if(isset($_SESSION['userType']))
	{
		if($_SESSION['userType'] == 'customer')
		{
			header ("location: dashboardCustomers.php");
		}
		if($_SESSION['userType'] == 'admin')
		{
			header ("location: dashboardAdmins.php");
		}
	}
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $loginError = "";
        if (!empty($_POST['username']) || !empty($_POST['password']))
        {
            if (empty($_POST['username']))
            {
                $loginError = "Username field is empty";
            }
            else if (empty($_POST['password']))
            {
                $loginError = "Password field is empty";
            }
            else
            {
                $username = $_POST['username'];
                $password = $_POST['password'];
                require "connectDB.php";
                if (isset($dbConnError))
                {
                    $loginError = "There was an error connecting to our database. Please try again.";
                }
                else
                {
                    $found = userPassFoundInDBAndSetSessionVars($conn, $username, $password, $loginError);
                    if ($found)
                    {
                        header("location: dashboardCustomers.php");
                    }
                }
            }
        } else
        {
            $loginError = "Username and password fields are empty.";
        }
    }
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amazon</title>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
    <link rel="stylesheet" href="styles/login.css"/>

</head>

<body>
<? include "header.php"; ?>
<? include "sideBar.php"; ?>
<div id = "content">
    <form action="login.php" method="post">
        <div id="loginFrame">
            <label for="username"><b>Username:</b></label>
            <input type="text" name="username" id="username" required><br/>
            <label for="password"><b>Password:</b></label>
            <input type="password" name="password" id="password" required><br/>
            <button type="submit">Login</button>
            <input type="checkbox" checked="checked"> Remember me
            <div id="loginErrorDiv" style="color:red"><?php echo $loginError; ?></div>
        </div>
    </form>
</div>
<? include "footer.php"; ?>
</body>
</html>