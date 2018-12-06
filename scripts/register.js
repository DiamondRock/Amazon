let inputsHaveBeenChanged = false;
let usernameIsValid = false;
let firstNameIsValid = false;
let lastNameIsValid = false;
let email0IsValid = false;
let passwordIsValid = false;
let passwordConfirmationIsValid = false;
let termsIsValid = false;
let phoneNo0IsValid = false;
$(document).ready(() =>
{
    $("#form").submit(SubmitIfValid);
    $("#username").on("input", () => {validateUsername()});
    $("#firstName").on("input", () => {validateFirstName()});
    $("#lastName").on("input", () => {validateLastName()});
    $("#email0").on("input", () => {validateEmail()});
    $("#password").on("input", () => {validatePassword()});
    $("#passwordConfirmation").on("input", () => {validatePasswordConfirmation()});
    $("#phoneNo0").on("input", () => {validatePhoneNo()});
    $("#usernameErrorSpan").addClass("error");
    $("#firstNameErrorSpan").addClass("error");
    $("#lastNameErrorSpan").addClass("error");
    $("#email0ErrorSpan").addClass("error");
    $("#passwordErrorSpan").addClass("error");
    $("#passwordConfirmationErrorSpan").addClass("error");
    $("#termsErrorSpan").addClass("error");
    $("#phoneNo0ErrorSpan").addClass("error");
});

function SubmitIfValid()
{
    let valid = validate();
    if(!valid)
    {
        $("#error").html("Please resolve the highlighted errors");
        for(i = 0;i < 2; i++)
        {
            $("#error").fadeTo('slow', 0.5).fadeTo('slow', 1.0);
        }
    }
    else
    {
        if(!inputsHaveBeenChanged)
        {
            inputsHaveBeenChanged = true;
            callAllValidateFunctions();
            return SubmitIfValid();
        }
    }
    return valid;
}

function validate()
{
    let valid = usernameIsValid && firstNameIsValid && lastNameIsValid && email0IsValid && passwordIsValid
    && passwordConfirmationIsValid && phoneNo0IsValid;
    if(valid)
    {
        $("#error").html("");
    }
    return valid;
}

function callAllValidateFunctions()
{
    validateUsername();
    validateFirstName();
    validateLastName();
    validateEmail();
    validatePassword();
    validatePasswordConfirmation();
    validateTerms();
    validatePhoneNo();
}

function validateUsername()
{
    usernameIsValid = false;
    let error = "";
    let username = $("#username").val();
    if(username == '')
    {
        error = "Username cannot be empty";
    }
    else
    {
        console.log("something");
        let request = $.ajax({
            async: false,
            url: "existsInDB.php",
            type: "GET",
            data: {username: username}

        });
        request.done((data) => {
            if(data.successful == true && data.exists == true)
            {
                error = "Username has already been taken. Please use another one!";
            }
        });
    }
    $("#usernameErrorSpan").html(error);
    if(error == "")
    {
        usernameIsValid = true;
        validate();
    }
    return usernameIsValid;
}
function validateFirstName()
{
    firstNameIsValid = false;
    let error = "";
    let firstName = $("#firstName").val();
    if( firstName == '')
    {
        error = "First name cannot be empty";
    }
    $("#firstNameErrorSpan").html(error);
    if(error == "")
    {
        firstNameIsValid = true;
        validate();
    }
	if(!firstNameIsValid)
	alert('last first ');
    return firstNameIsValid;
}

function validateLastName()
{
    lastNameIsValid = false;
    let error = "";
    let lastName = $("#lastName").val();
    if( lastName == '')
    {
        error = "Last name cannot be empty";
    }
    $("#lastNameErrorSpan").html(error);
    if(error == "")
    {
        lastNameIsValid = true;
        validate();
    }
    return lastNameIsValid;
}

function validateEmail()
{
    email0IsValid = false;
    let error = "";
    let email = $("#email0").val();
    if(email == '')
    {
        error = "Email cannot be empty";
    }
    else if(new RegExp('^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,63}$').exec(email) == null)
    {
        error = "Email is invalid";
    }
    else
    {
        let request = $.ajax({
            async:false,
            url: "existsInDB.php",
            type: "GET",
            data: {email: email}
        });
        request.done((data) => {
            if(data.successful == true && data.exists == true)
            {
                error = "This email has already been used for another account!";
            }
        });
    }
    $("#email0ErrorSpan").html(error);
    if(error == "")
    {
        email0IsValid = true;
        validate();
    }
    return email0IsValid;
}

function validatePassword()
{
    passwordIsValid = false;
    let password = $("#password").val();
    let error = "";
    if(password == "")
    {
        error = "Password should be provided";
    }
    else
    {
        let errors =[];
        if (password.length < 8)
        {
            errors.push('8 characters');
        }
        if(new RegExp('[A-Z]').exec(password) == null)
        {
            errors.push('one capital letter');
        }
        if(new RegExp('[a-z]').exec(password) == null)
        {
            errors.push('one small letter');
        }
        if(new RegExp('[0-9]').exec(password) == null)
        {
            errors.push('one number');
        }
        let result = new RegExp('\!|@|#|\$|%|\\^|&|\\*|~|`|\\(|\\)|-|_|\\+|=|\\|\\||\/|\\{|\\}|\\[|\\]|\\?|\\.|,|;|\\:|<|>').exec(password);
        if(result == null || result == "")
        {
            errors.push('one special character');
        }
        if(errors.length != 0)
        {
            error = "Password should contain at least " + errors[0];
            if(errors.length == 1)
            {
                error += ".";
            }
            else if(errors.length == 2)
            {
                error += " and " + errors[1] + ".";
            }
            else
            {
                for(var i = 1; i < errors.length - 1; i++)
                {
                    error += ", " +  errors[i];
                }
                error += ", and " + errors[errors.length - 1] + ".";
            }
        }
    }
    $("#passwordErrorSpan").html(error);
    if(!passwordsMatch())
    {
        $("#passwordConfirmationErrorSpan").html("Passwords do not match.");
    }
    else
    {
        $("#passwordConfirmationErrorSpan").html("");
    }
    if(error == "")
    {
        passwordIsValid = true;
        validate();
    }
    return passwordIsValid;
}

function validatePasswordConfirmation()
{
    passwordConfirmationIsValid = false;
    let error = "";
    if(!passwordsMatch())
    {
        error = "Passwords do not match."
    }
    $("#passwordConfirmationErrorSpan").html(error);
    if(error == "")
    {
        passwordConfirmationIsValid = true;
        validate();
    }
    return passwordConfirmationIsValid;
}

function passwordsMatch()
{
    return $("#password").val() ==  $("#passwordConfirmation").val();
}

function validateTerms()
{
    termsIsValid = false;
    let error = "";
    if (!$('#terms').is(':checked'))
    {
        error = "You should agree to the terms and conditions in order to sign up.";
    }
    $("#termsErrorSpan").html(error);
    if(error == "")
    {
        termsIsValid = true;
        validate();
    }
    return termsIsValid;
}

function validatePhoneNo()
{
    phoneNo0IsValid = false;
    let error = "";
    let phoneNo = $("#phoneNo0").val();
    if(phoneNo == "")
    {
        error = "Phone number cannot be empty";
    }
    else
    {
        let result = new RegExp("^(\\+\\d{1,2}\\s)?\\(?\\d{3}\\)?[\\s.-]?\\d{3}[\\s.-]?\\d{4}$").exec(phoneNo);
        if (result == null || result == "") {
            error = "Invalid phone No.";
        }
    }
    $("#phoneNo0ErrorSpan").html(error);
    if(error == "")
    {
        phoneNo0IsValid = true;
        validate();
    }
    return phoneNo0IsValid;
}
