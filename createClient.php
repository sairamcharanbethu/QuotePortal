<?php
require_once 'config/session.php';
if($_SESSION['username']=='mujeeb'){
    include 'index.php';
}
else
    include "indexUser.php";
include 'config/database.php';
if (isset($_POST['createBtn'])){
    $name = $_POST["c_name"];
    $email = $_POST["c_email"];
    $a_email=$_POST["a_email"];
    $mobile = $_POST["c_mobile"];
    $wphone = $_POST["wmobile"];
    $address = $_POST["c_address"];
    $city = $_POST["city"];
    $province = $_POST["province"];
    $zip = $_POST["zip"];


$sqlInsert = "INSERT INTO customers(name,email,a_email,phone,wphone,address,city,province,zip,
created,modified,status)
VALUES (:name,:email,:a_email,:mobile,:wphone,:address,:city,:province,:zip,:created,:modified,:status)";

    $statement = $db->prepare($sqlInsert);

    //add the data into the database
    $statement->execute(array(':name' => $name,':email'=> $email,':a_email'=> $a_email,':mobile'=> $mobile
    ,':wphone'=> $wphone,':address'=> $address, ':city'=> $city,':province'=> $province,':zip'=> $zip,
        ':created'=> date("Y-m-d H:i:s"),':modified'=>date("Y-m-d H:i:s"),
        ':status'=>1));
    if($statement){
        header("location: home.php");

    }else{
        header("location: error.php");

    }


}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
<title>Client Registration</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
    body{
    color: #fff;
    background: #fff;
    font-family: 'Roboto', sans-serif;
	}
    .form-control{
    height: 40px;
		box-shadow: none;
		color: #969fa4;
	}
	.form-control:focus{
    border-color: #5cb85c;
	}
    .form-control, .btn{
    border-radius: 3px;
    }
	.signup-form{
    width: 500px;
		margin: 0 auto;
		padding: 30px 0;
	}
	.signup-form h2{
    color: #636363;
    margin: 0 0 15px;
		position: relative;
		text-align: center;
    }
	.signup-form h2:before, .signup-form h2:after{
    content: "";
    height: 2px;
		width: 28%;
		background: #d4d4d4;
		position: absolute;
		top: 50%;
		z-index: 2;
	}
	.signup-form h2:before{
    left: 0;
}
	.signup-form h2:after{
    right: 0;
}
    .signup-form form{
    color: #999;
    border-radius: 3px;
    	margin-bottom: 15px;
        background: #f2f3f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }
	.signup-form .form-group{
    margin-bottom: 20px;
	}
	.signup-form input[type="checkbox"]{
    margin-top: 3px;
	}
	.signup-form .btn{
    font-size: 16px;
        font-weight: bold;
		min-width: 140px;
        outline: none !important;
    }
	.signup-form .row div:first-child{
    padding-right: 10px;
	}
	.signup-form .row div:last-child{
    padding-left: 10px;
	}
    .signup-form a{
    color: #fff;
    text-decoration: underline;
	}
    .signup-form a:hover{
    text-decoration: none;
	}
	.signup-form form a{
    color: #5cb85c;
    text-decoration: none;
	}
	.signup-form form a:hover{
    text-decoration: underline;
	}
</style>
</head>
<body>
<div class="signup-form">
    <form action="" method="post">
		<h2>Create Client</h2>
		<hr>
        <div class="form-group">
            <input type="text" class="form-control" name="c_name" placeholder="Client Name" required="required">
        </div>
        <div class="form-group">
        	<input type="email" class="form-control" name="c_email" placeholder="Email" required="required"><br>
            <input type="email" class="form-control" name="a_email" placeholder="Alternate Email" >
        </div>
         <div class="form-group">
        	<input type="number" class="form-control" name="c_mobile" placeholder="Mobile" required="required">
<br>
             <input type="number" class="form-control" name="wmobile" placeholder="Work Phone">
        </div>
		<div class="form-group">
            <textarea class="form-control" rows="4" cols="50" name="c_address" placeholder="Enter Address" required></textarea>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="city" placeholder="City" required>
            <br>
            <select class="form-control" id="province" name="province">
                <option value="">Province</option>
                <option value="AB">Alberta</option>
                <option value="BC">British Columbia</option>
                <option value="MB">Manitoba</option>
                <option value="NB">New Brunswick</option>
                <option value="NF">Newfoundland</option>
                <option value="NT">Northwest Territories</option>
                <option value="NS">Nova Scotia</option>
                <option value="NU">Nunavut</option>
                <option value="ON">Ontario</option>
                <option value="PE">Prince Edward Island</option>
                <option value="QC">Quebec</option>
                <option value="SK">Saskatchewan</option>
                <option value="YT">Yukon Territory</option></select>

        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="zip" placeholder="Postal Code" required>
        </div>
        <hr>


		<div class="form-group">
            <button type="submit" class="btn btn-success btn-lg btn-block" name="createBtn" >Create</button>
        </div>
    </form>

</div>
</body>
</html>