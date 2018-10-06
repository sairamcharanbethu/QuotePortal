<?php
//add our database connection script
include_once 'config/database.php';
include_once 'config/utilities.php';
require 'config/session.php';
include 'index.php';
//process the form
if(isset($_POST['signupBtn'])){
    //initialize an array to store any error message from the form
    $form_errors = array();

    //Form validation
    $required_fields = array('email', 'username', 'password');

    //call the function to check empty field and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    //Fields that requires checking for minimum length
    $fields_to_check_length = array('username' => 4, 'password' => 4);

    //call the function to check minimum required length and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    //email validation / merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_email($_POST));

    //check if error array is empty, if yes process form data and insert record
    if(empty($form_errors)){
        //collect form data and store in variables
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        //hashing the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try{

            //create SQL insert statement
            $sqlInsert = "INSERT INTO users (username, email, password, join_date)
              VALUES (:username, :email, :password, now())";

            //use PDO prepared to sanitize data
            $statement = $db->prepare($sqlInsert);

            //add the data into the database
            $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password));

            //check if one new row was created
            if($statement->rowCount() == 1){
                $result = "<p style='padding:20px; border: 1px solid gray; color: green;'> Registration Successful</p>";
            }
        }catch (PDOException $ex){
            $result = "<p style='padding:20px; border: 1px solid gray; color: red;'> An error occurred: ".$ex->getMessage()."</p>";
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
                                                                                                                                     

<?php if(isset($result))
echo $result;
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <meta content="noindex, nofollow" name="robots">
    <!-- Include CSS File Here -->
    <link href="css/form2.css" rel="stylesheet">
    <style>
        /* Style the buttons */
        .btn {
            border: none;
            border-radius: 30px;
            outline: none;
            padding: 10px 16px;
            color:#ffffff;
            background-color: #5175C0;
            cursor: pointer;
        }

        /* Style the active class (and buttons on mouse-over) */
        .active, .btn:hover {
            transition: ease-out 0.3s;
            background-color: #666;
            color: white;
        }
    </style>
</head>
<body>
<div id="first">
    <form action="" method="post">
        <h1>Create User</h1><a class="btn" style="float: right; text-decoration: none;font-weight: 700;" href="listUsers.php">View Users</a>
        <h4>Please fill all fields.</h4>
        <label class="one">
            <span>Username :</span>&nbsp;
            <input class="name" name="username" type="text">
        </label>
        <label class="two">
            <span>Email :</span>&nbsp;
            <input class="email" name="email" type="email">
        </label>
        <label class="three">
            <span>Password :</span>&nbsp;
            <input class="email" name="password" type="password">
        </label>
        <input class="submit" name="signupBtn" type="submit" value="Sign Up">
        <a href="home.php"><input type="button" name="" value="Back">

    </form>
</div>
</body>
</html>

