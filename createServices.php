<?php
require_once 'config/session.php';
include "index.php";
include 'config/database.php';
if (isset($_POST['createBtn'])){
    $sname = $_POST['s_name'];
    $sdesc = $_POST['s_desc'];
    $unit = $_POST['s_price'];

    $sqlInsert = "INSERT INTO `services`(`serv_name`, `description`, `unit`)
VALUES (:sname,:sdesc,:unit)";

    $statement = $db->prepare($sqlInsert);

    //add the data into the database
    $statement->execute(array(':sname' => $sname, ':sdesc' => $sdesc, ':unit' => $unit));
    if($statement){
        $smsg = "Service Created Successfully.";
    }else{
        $fmsg ="Service Registration Failed";
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
    <title>Service Registration</title>
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
        }
        .form-control:focus{
            border-color: #5cb85c;
        }
        .form-control, .btn{
            border-radius: 3px;
        }
        .signup-form{
            width: 400px;
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
            height: 5px;
            width: 20%;
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
        <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
        <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2>Create Service</h2>
        <hr>
        <div class="form-group">
            <input type="text" class="form-control" name="s_name" placeholder="Service Name" required="required">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="s_desc" placeholder="Description" required="required">
        </div>
        <div class="form-group">
            <input type="number" class="form-control" name="s_price" placeholder="Unit Price" required="required">
        </div>
        <hr>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-lg btn-block" name="createBtn" >Create Service</button>
        </div>
    </form>

</div>
</body>
</html>