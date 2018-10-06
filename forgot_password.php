<?php
//add our database connection script
include_once 'config/Database.php';
include_once 'config/utilities.php';

//process the form if the reset password button is clicked
if(isset($_POST['passwordResetBtn'])){
    //initialize an array to store any error message from the form
    $form_errors = array();

    //Form validation
    $required_fields = array('email', 'new_password', 'confirm_password');

    //call the function to check empty field and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    //Fields that requires checking for minimum length
    $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);

    //call the function to check minimum required length and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    //email validation / merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_email($_POST));

    //check if error array is empty, if yes process form data and insert record
    if(empty($form_errors)){
        //collect form data and store in variables
        $email = $_POST['email'];
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];

        //check if new password and confirm password is same
        if($password1 != $password2){
            $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> New password and confirm password does not match</p>";
        }else{
            try{
                //create SQL select statement to verify if email address input exist in the database
                $sqlQuery = "SELECT email FROM users WHERE email =:email";

                //use PDO prepared to sanitize data
                $statement = $db->prepare($sqlQuery);

                //execute the query
                $statement->execute(array(':email' => $email));

                //check if record exist
                if($statement->rowCount() == 1){
                    //hash the password
                    $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

                    //SQL statement to update password
                    $sqlUpdate = "UPDATE users SET password =:password WHERE email=:email";

                    //use PDO prepared to sanitize SQL statement
                    $statement = $db->prepare($sqlUpdate);

                    //execute the statement
                    $statement->execute(array(':password' => $hashed_password, ':email' => $email));

                    $result = "<p style='padding:20px; border: 1px solid gray; color: green;'> Password Reset Successful</p>";
                }
                else{
                    $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> The email address provided
                                does not exist in our database, please try again</p>";
                }
            }catch (PDOException $ex){
                $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> An error occurred: ".$ex->getMessage()."</p>";
            }
        }
    }
    else{
        if(count($form_errors) == 1){
            $result = "<p style='color: red;'> There was 1 error in the form<br>";
        }else{
            $result = "<p style='color: red;'> There were " .count($form_errors). " errors in the form <br>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Password Reset Page</title>
    <img src="img/logo.png" style="margin-top: 10px;">
    <link href="https://fonts.googleapis.com/css?family=Mukta+Malar" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <h1 align="center" style="color: #0B4F7B;margin:0px auto;font-size:35px;font-family: 'Mukta Malar', sans-serif;">Quote Management System</h1>
    <hr>
</head>
<body>
<div style="margin: 0 auto;width: 400px;">

    <h3 align="center">Password Reset Form</h3>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
<form method="post" action="" ">
    <table align="center">
        <tr><td style="padding-top: 30px;">Email:</td> <td style="padding-top: 30px;"><input type="text" value="" name="email"></td></tr>
        <tr><td style="padding-top: 30px;">New Password:</td> <td style="padding-top: 30px;"><input type="password" value="" name="new_password"></td></tr>
        <tr><td style="padding-top: 30px;">Confirm Password:</td> <td style="padding-top: 30px;"><input type="password" value="" name="confirm_password"></td></tr>
        <tr><td style="padding-top: 40px;"><input style="float: right;" class="btn btn-primary" type="submit" name="passwordResetBtn" value="Reset Password"></td>
            <td style="padding-top: 40px;"><a href="login.php"><input style="float: right;" class="btn btn-dark" type="button" name="" value="Cancel"></td></a>
        </tr>
    </table>
</form>
</div>
</body>
</html>