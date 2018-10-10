<?php
include_once 'config/database.php';
include_once 'config/utilities.php';


if(isset($_POST['loginBtn'])){
    //array to hold errors
    $form_errors = array();

//validate
    $required_fields = array('username', 'password');
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    if(empty($form_errors)){

        //collect form data
        $user = $_POST['username'];
        $password = $_POST['password'];

        //check if user exist in the database
        $sqlQuery = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':username' => $user));

        while($row = $statement->fetch()){
            $id = $row['id'];
            $hashed_password = $row['password'];
            $username = $row['username'];

            if(password_verify($password, $hashed_password)){
                session_start();
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['authenticated']=true;
                $_SESSION['last_time']=time();
                header("Location: home.php");}

            else{
                $result = "<p style='padding: 20px; color: red; border: 1px solid gray;'> Invalid username or password</p>";
            }
        }

    }else{
        if(count($form_errors) == 1){
            $result = "<p style='color: red;'>There was one error in the form </p>";
        }else{
            $result = "<p style='color: red;'>There were " .count($form_errors). " error in the form </p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styleLogin.css">
    <link href="https://fonts.googleapis.com/css?family=Mukta+Malar" rel="stylesheet">
    <img src="img/logo.png" style="margin-top: 10px;">

    <h1 align="center" style="color: #0B4F7B;margin:0px auto;font-size:35px;font-family: 'Mukta Malar', sans-serif;">Quote Management System</h1>
    <hr>
    <title>SSCI - Login</title>

</head>
<?php if(isset($result)) echo $result; ?>
<?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
<body id="LoginForm">
<h1 align="center" style="color:black;margin:0px auto;font-size:35px;font-family: 'Mukta Malar', sans-serif;">Welcome</h1>

<div class="container">
    <h1 class="form-heading">login Form</h1>
    <div class="login-form">
        <div class="main-div">
            <div class="panel">
                <h2>User Login</h2>
                <p>Please enter your email and password</p>
            </div>
            <form method="post" action="">
                <div class="form-group">
                   Username:  <input title="username" type="text" value="" name="username">
                </div>
                <div class="form-group">
                    Password: <input title="password" type="password"  value="" name="password">
                </div>
                <div class="forgot">
                    <a href="forgot_password.php">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary"  name="loginBtn" value="Signin">LOGIN</button><br>
            </form>
        </div>
   </div></div>
</body>
</html>