<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(isset($_SESSION['userType']))
{
    if(strcmp($_SESSION['userType'], "admin") == 0)
    {
        header("location: dashboardAdmins.php");
    }
    else
    {
        header("location: dashboardCustomers.php");
    }
}
?>
<!DOCTYPE html>
<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    require_once "connectDB.php";
    if (isset($dbConnError))
    {
        $errorInDb = true;
    }
    else
    {
        $errorInDb = false;
    }
    $noErrorFound = false;
    $usernameError = "";
    $firstNameError = "";
    $lastNameError = "";
    $emailsErrors = [""];
    $passwordError = "";
    $passwordsDoNotMatchError = "";
    $phoneNosErrors = [""];
    $addressesErrors = [""];
    $termsError = "";
    $username = $_POST['username'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emails = [$_POST['email0']];
    while(true)
    {
        if(isset($_POST['email{$i}']))
        {
            $emails[] = $_POST['email{$i}'];
            $emailsErrors[] = "";
        }
        else
        {
            break;
        }
    }
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['passwordConfirmation'];
    $phoneNos = [$_POST['phoneNo0']];
    while(true)
    {
        if(isset($_POST['phoneNo{$i}']))
        {
            $phoneNos[] = $_POST['phoneNo{$i}'];
            $phoneNosErrors[] = "";
        }
        else
        {
            break;
        }
    }
//    $addresses = [$_POST['address0']];
    $addresses =[];
    $termsChecked = $_POST['terms'];
    if(strcmp($termsChecked, "on") == 0)
    {
        $termsChecked = true;
    }
    else
    {
        $termsChecked = false;
    }
    $noErrorFound = validate($firstName, $lastName, $emails, $password, $passwordConfirmation, $phoneNos, $termsChecked,
        $firstNameError, $lastNameError, $emailsErrors, $passwordError,
        $passwordsDoNotMatchError, $phoneNosErrors, $termsError);
    if(!$errorInDb)
    {
        $usernameIsValid = validateUsername($conn, $username, $usernameError);
        if(strcmp($usernameError, "db error") == 0)
        {
            $errorInDb = true;
        }
        else
        {
            $noErrorFound = $usernameIsValid && $noErrorFound;
        }
    }
    if(!$errorInDb &&  $noErrorFound)
    {
        $userId = -1;
        $errorInDb = !writeToDB($conn, $firstName, $lastName, $emails, $password, $phoneNos, $addresses, $username, $userId);
    }
}
function writeToDB($conn, $firstName, $lastName, $emails, $password, $phoneNos, $addresses, $username, &$userId)
{
    try
    {
        $conn->exec("SET autocommit=0;");
        $conn->beginTransaction();


        $query = "insert into users(username, password, firstName, lastName, userType) values (:username, :password,
              :firstName, :lastName, 'customer')";
        $hashedPassword = PASSWORD_HASH($password, PASSWORD_BCRYPT);
        $params = [$username, $hashedPassword, $firstName, $lastName];
        $paramsNamesInQuery = [":username", ":password", ":firstName", ":lastName"];
        executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
        $userId = $conn->lastInsertId();

        $query = "insert into emails(userId, email) values ($userId, :email)";
        $paramsNamesInQuery = [":email"];
        for($i = 0; $i < count($emails); $i++)
        {
            $params = [$emails[$i]];
            executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
        }

        $query = "insert into phones(userId, phoneNo) values ($userId, :phoneNo)";
        $paramsNamesInQuery = [":phoneNo"];
        for($i = 0; $i < count($phoneNos); $i++)
        {
            $params = [$phoneNos[$i]];
            executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
        }

//        $query = "insert into addresses(address, city, state, country, zipCode) values (':address, :city,
//:state, :country, :zipCode)";
//        $paramsNamesInQuery = [":address", ":city", ":state", ":country", ":zipCode"];
//        for($i = 0; $i < count($addresses); $i++)
//        {
//            $params = [$addresses[$i]->getAddress(), $addresses[$i]->getCity(), $addresses[$i]->getState(),
//                $addresses[$i]->getCountry(), $addresses[$i]->getZipCode()];
//            executeQuery($conn, $query, $params, $paramsNamesInQuery, true);
//            $addressId = getLastInsertId($conn);
//
//            $user_addressesQuery = "insert into users_addresses (userId, addressId) values ('{$userId}','{$addressId}')";
//            $user_addressesParams = [];
//            $user_addressesParamsNamesInQuery = [];
//            executeQuery($conn, $user_addressesQuery, $user_addressesParams, $user_addressesParamsNamesInQuery,
//                true);
//        }

        $conn->commit();
        return true;
    }
    catch (Exception $e)
    {
        $conn->rollBack();
        echo $e->getMessage();
        return false;
    }
}

function validate($firstName, $lastName, $emails, $password, $passwordConfirmation, $phoneNos, $termsChecked,
                  &$firstNameError="", &$lastNameError="", &$emailsErrors=[], &$passError="",
                  &$passwordsDoNotMatchError="", &$phoneNosErrors=[], &$termsError="")
{
    $flag = validateFirstName($firstName, $firstNameError);
    $flag = validateLastName($lastName, $lastNameError) && $flag;
    $emailsErrors = array_fill(0, count($emails), "");
    for ($i = 0; $i < count($emails); $i++)
    {
        $flag = validateEmail($emails[$i], $emailsErrors[$i]) && $flag;
    }
    $flag = validatePassword($password, $passError) && $flag;
    $flag = passwordsMatch($password, $passwordConfirmation, $passwordsDoNotMatchError) && $flag;
    $phoneNosError = array_fill(0, count($emails), "");
    for ($i = 0; $i < count($phoneNos); $i++)
    {
        $flag = validatePhoneNo($phoneNos[$i], $phoneNosErrors[$i]) && $flag;
    }
    if(!$termsChecked)
    {
        $termsError = "You should agree to the terms and conditions in order to sign up.";
        $flag = false;
    }
    return $flag;
}


function validateUsername($conn, $username, &$error = "")
{
    if(empty($username))
    {
        $error = "User name cannot be empty.";
        return false;
    }
    try
    {
        $query = "select username from users where username=:username";
        $params = [$username];
        $paramsNamesInQuery = [":username"];
        $result = executeQuery($conn, $query, $params, $paramsNamesInQuery, false);
        if($result->rowCount() == 0)
        {
            return true;
        }
        else
        {
            $error = "Username has already been taken. Please choose another one.";
            return false;
        }
    }
    catch (Exception $e)
    {
        $error = "db error";
        return false;
    }
}


function validateFirstName($name, &$error = "")
{
    if(empty($name))
    {
        $error = "First name cannot be empty.";
        return false;
    }
    return true;
}


function validateLastName($name, &$error = "")
{
    if(empty($name))
    {
        $error = "Last name cannot be empty.";
        return false;
    }
    return true;
}


function validateEmail($email, &$error = "")
{
    if(!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,63}$/", $email))
    {
        $error = "Email is Invalid";
        return false;
    }
    return true;
}


function validatePhoneNo($phoneNo, &$error ="")
{
    if(!preg_match("/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/", $phoneNo))
    {
        $error = "Phone No is invalid";
        return false;
    }
    return true;
}


function validatePassword($password, &$error = "")
{
    $errors =[];
    if (strlen($password) < 8)
    {
        array_push($errors, "8 characters");
    }
    if(!preg_match('/[A-Z]/', $password))
    {
        array_push($errors, "one capital letter");
    }
    if(!preg_match('/[a-z]/', $password))
    {
        array_push($errors, "one small letter");
    }
    if(!preg_match('/[0-9]/', $password))
    {
        array_push($errors, "one number");
    }
    if(!preg_match('/\!|@|#|\$|%|\^|&|\*|~|`|\(|\)|\-|_|\+|=|\\\\|\||\/|\{|\}|\[|\]|\?|\.|,|;|\:|<|>/', $password))
    {
        array_push($errors, "one special character");
    }
    if(empty($errors))
    {
        return true;
    }
    $error = "Password should contain at least ". $errors[0];
    if(count($errors) == 1)
    {
        $error .= ".";
        return false;
    }
    if(count($errors) == 2)
    {
        $error .= "and " . $errors[1] . ".";
        return false;
    }
    else
    {
        for($i = 1; $i < count($errors) - 1; $i++)
        {
            $error .= ", " .  $errors[$i];
        }
        $error .= ", and " . $errors[count($errors) - 1] . ".";
    }
}
function passwordsMatch($pass1, $pass2, &$error="")
{
    if(strcmp($pass1, $pass2) != 0)
    {
        $error = "Passwords do not match";
        return false;
    }
    return true;
}
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amazon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="scripts/register.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="styles/master.css"/>
    <link rel="icon" type="image/png" href="images/diamond.jpg"/>
    <link rel="stylesheet" href="styles/register.css"/>


</head>

<body>
    <? include "header.php"; ?>
    <? include "sideBar.php";?>
    <div id = "content">

<?php



if($_SERVER["REQUEST_METHOD"] != "POST")
{
    ?>
    <h1>Sign up</h1>
    Please fill in this form to create an account
    <hr/>
    <div id="error"></div>
    <form id="form" action="register.php" method="post">
        <div id="signupFrame">
            <label for="username"><b>Username</b></label><br/>
            <input type="text" id="username" name="username" placeholder="Username" required="required"/>
            <span id="usernameErrorSpan"></span><br/>
            <label for="firstName"><b>First Name</b></label><br/>
            <input type="text" id="firstName" name="firstName" placeholder="First Name" required="required"/>
            <span id="firstNameErrorSpan"></span><br/>
            <label for="lastName"><b>Last Name</b></label><br/>
            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required="required"/>
            <span id="lastNameErrorSpan"></span><br/>
            <label for="email0"><b>Email</b></label><br/>
            <input type="text" id="email0" name="email0" placeholder="Email" required="required"/>
            <span id="email0ErrorSpan"></span><br/>
            <label for="password"><b>Password</b></label><br/>
            <input type="password" id="password" name="password" placeholder="Password" required="required"/>
            <span id="passwordErrorSpan"></span><br/>
            <label for="passwordConfirmation"><b>Password Confirmation</b></label><br/>
            <input type="password" id="passwordConfirmation" name="passwordConfirmation"
                   placeholder="Confirm Password" required="required"/>
            <span id="passwordConfirmationErrorSpan"></span><br/>
            <label for="phoneNo0"><b>Phone Number</b></label><br/>
            <input type="text" id="phoneNo0" name="phoneNo0" placeholder="Phone Number" required="required"/>
            <span id="phoneNo0ErrorSpan"></span><br/>
            <input type="checkbox" id="terms" name="terms" required="required"/>
            <span id="termsText">I accept the <a href="terms.html">Terms of Use</a> &amp; <a href="privacy.html">Privacy Policy</a></span>
             <span id="termsErrorSpan"></span><br/>
            <input type="submit" id="submit" class="btn btn-primary" value="Sign Up"/>
            </div>
    </form>
    <?php
}
else
{
    $error = "";
    if($errorInDb)
    {
        $error = "There was a problem with our database. Please try again.";
        if(!$noErrorFound)
        {
            $error .= "Please also resolve the highlighted errreors.";
        }
    }
    else
    {
        if($noErrorFound)
        {
            $_SESSION['username'] = $username;
            $_SESSION['userType'] = 'customer';
            $_SESSION['id'] =  $userId;
            header("location:dashboardCustomers.php");
        }
        else
        {
            $error = "Please resolve the highlighted errors";
        }
    }
    ?>
    <div id="signupFrame">
        <h1>Sign up</h1>
        Please fill in this form to create an account
        <hr/>
        <div id="error"><? echo $error; ?></div>
        <form id="form" action="register.php" method="post">
            <label for="username"><b>Username</b></label><br/>
            <input type="text" id="username" name="username" placeholder="User name" required="required" value="<?if(!empty($username)){echo $username;}?>"/>
            <span id="usernameErrorSpan"><?if(!empty($usernameError)){echo $usernameError;}?></span><br/>
            <label for="lastName"><b>Last Name</b></label><br/>
            <input type="text" id="firstName" name="firstName" placeholder="First Name"/ required="required" value="<?if(!empty($firstName)){echo $firstName;}?>">
            <span id="firstNameErrorSpan"><?if(!empty($firstNameError)){echo $firstNameError;}?></span><br/>
            <input type="text" id="lastName" name="lastName" placeholder="Last Name"/ required="required" value="<?if(!empty($lastName)){echo $lastName;}?>">
            <span id="lastNameErrorSpan"><?if(!empty($lastNameError)){echo $lastNameError;}?></span><br/>
            <label for="email0"><b>Email</b></label><br/>
            <input type="text" id="email0" name="email0" placeholder="Email" required="required" value="<?if(!empty($emails[0])){echo $emails[0];}?>"/>
            <span id="email0ErrorSpan"><?if(!empty($emailsErrors[0])){echo $emailsErrors[0];}?></span><br/>
            <label for="password"><b>Password</b></label><br/>
            <input type="password" id="password" name="password" placeholder="Password" required="required"/>
            <span id="passwordErrorSpan"><?if(!empty($passwordError)){echo $passwordError;}?></span><br/>
            <label for="passwordConfirmation"><b>Password Confirmation</b></label><br/>
            <input type="password" id="passwordConfirmation" name="passwordConfirmation"
               placeholder="Confirm Password" required="required"/>
            <span id="passwordConfirmationErrorSpan"><?if(!empty($passwordsDoNotMatchError)){echo $passwordsDoNotMatchError;}?></span><br/>
            <label for="phoneNo0"><b>Phone Number</b></label><br/>
            <input type="text" id="phoneNo0" name="phoneNo0" placeholder="Phone Number" required="required" value="<?if(!empty($phoneNos[0])){echo $phoneNos[0];}?>"/>
            <span id="phoneNo0ErrorSpan"><?if(!empty($phoneNosErrors[0])){echo $phoneNosErrors[0];}?></span><br/>
            <input type="checkbox" id="terms" name="terms" <?if($termsChecked){echo 'checked="checked"';}?> required="required"/>
            <span id="termsText">I accept the <a href="terms.html">Terms of Use</a> &amp; <a href="privacy.html">Privacy Policy</a></span>
            <span id="termsErrorSpan"><?if(!empty($termsError)){echo $termsError;}?></span><br/>
            <input type="submit" id="submit" class="btn btn-primary" value="Sign Up"/>
        </form>
    </div>
    <?php
}
?>
    </div>
    <? include "footer.php"; ?>
</body>
</html>