<?php
// Include config file
require_once "config/database.php";
require_once "config/session.php";
include "index.php";
// Define variables and initialize with empty values
$name = $email = $phone = $address= $city = $province = $zip =$a_email =$wphone= "";
$name_err = $address_err = $email_err = $phone_err = $city_err = $province_err = $zip_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate alternate_email address
    $input_aemail = trim($_POST["a_email"]);
    if(empty($input_aemail)){
        $address_err = "Please enter alternate email address.";
    } else{
        $a_email = $input_aemail;
    }

    // Validate email address
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $address_err = "Please enter email address.";
    } else{
        $email = $input_email;
    }

    // Validate mobile
    $input_mobile = trim($_POST["mobile"]);
    if(empty($input_mobile)){
      $phone_err = "Please enter mobile number.";
    } else{
        $phone = $input_mobile;
    }

    // Validate work phone
    $input_work = trim($_POST["wphone"]);
    if(empty($input_work)){
        $phone_err = "Please enter work phone number.";
    } else{
        $wphone = $input_work;
    }


    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";
    } else{
        $address = $input_address;
    }

    // Validate city
    $input_city = trim($_POST["city"]);
    if(empty($input_city)){
        $city_err = "Please enter a city.";
    } else{
        $city = $input_city;
    }

    // Validate province
    $input_province = trim($_POST["province"]);
    if(empty($input_province)){
        $province_err = "Please enter a province.";
    } else{
        $province = $input_province;
    }

    // Validate zip
    $input_zip = trim($_POST["zip"]);
    if(empty($input_zip)){
        $zip_err = "Please enter a Postal code.";
    } else{
        $zip = $input_zip;
    }


    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($email_err) && empty($phone_err)  && empty($city_err)
        && empty($province_err)  && empty($zip_err)){
        // Prepare an update statement
        $sql = "UPDATE customers SET name=:name, email=:email,a_email=:a_email, phone=:phone,wphone=:wphone,
 address=:address , city=:city, province=:province, zip=:zip WHERE id=:id";
        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":a_email", $a_email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":wphone", $wphone);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":city", $city);
            $stmt->bindParam(":province", $province);
            $stmt->bindParam(":zip", $zip);



            $stmt->bindParam(":id", $id);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: viewclients.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($pdo);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty($_GET["id"])){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM customers WHERE id = :id";
        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Retrieve individual field value
                    $name = $row["name"];
                    $email = $row["email"];
                    $a_email = $row["a_email"];
                    $mobile = $row["phone"];
                    $wphone = $row["wphone"];
                    $address = $row["address"];
                    $city = $row["city"];
                    $province = $row["province"];
                    $zip = $row["zip"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);

        // Close connection
        unset($pdo);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Update Record</h2>
                </div>
                <p>Please edit the input values and submit to update the record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                        <span class="help-block"><?php echo $email_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Alternate Email</label>
                        <input type="text" name="a_email" class="form-control" value="<?php echo $a_email; ?>">
                        <span class="help-block"><?php echo $email_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                        <label>Mobile</label>
                        <input type="number" name="mobile" class="form-control" value="<?php echo $mobile; ?>">
                        <span class="help-block"><?php echo $phone_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                        <label>Work Phone</label>
                        <input type="number" name="wphone" class="form-control" value="<?php echo $wphone; ?>">
                        <span class="help-block"><?php echo $phone_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                        <label>Address</label>
                        <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                        <span class="help-block"><?php echo $address_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
                        <span class="help-block"><?php echo $city_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($province_err)) ? 'has-error' : ''; ?>">
                        <label>Province</label>
                        <input type="text" name="province" class="form-control" value="<?php echo $province; ?>">
                        <span class="help-block"><?php echo $province_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($zip_err)) ? 'has-error' : ''; ?>">
                        <label>Postal Code</label>
                        <input type="text" name="zip" class="form-control" value="<?php echo $zip; ?>">
                        <span class="help-block"><?php echo $zip_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="viewclients.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>