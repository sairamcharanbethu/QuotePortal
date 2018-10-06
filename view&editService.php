<?php
// Include config file
require_once "config/database.php";

// Define variables and initialize with empty values
$serv_name = $serv_desc = $serv_unit = "";
$serv_name_err = $serv_desc_err = $serv_unit_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $serv_name_err = "Please enter a service name.";
    } else{
        $serv_name = $input_name;
    }

    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $serv_desc_err = "Please enter service description.";
    } else{
        $serv_desc = $input_address;
    }

    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $serv_unit_err = "Please enter the unit cost.";
    } elseif(!ctype_digit($input_salary)){
        $serv_unit_err = "Please enter a positive integer value.";
    } else{
        $serv_unit = $input_salary;
    }

    // Check input errors before inserting in database
    if(empty($serv_name_err) && empty($serv_desc_err) && empty($serv_unit_err)){
        // Prepare an update statement
        $sql = "UPDATE services SET serv_name=:name, description=:address, unit=:salary WHERE serv_id=:id";

        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":address", $param_address);
            $stmt->bindParam(":salary", $param_salary);
            $stmt->bindParam(":id", $param_id);

            // Set parameters
            $param_name = $serv_name;
            $param_address = $serv_desc;
            $param_salary = $serv_unit;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: editServices.php");
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
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM services WHERE serv_id = :id";
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
                    $serv_name = $row["serv_name"];
                    $serv_desc = $row["description"];
                    $serv_unit = $row["unit"];
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
<?php
require_once "config/session.php";
include_once "index.php";
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
                    <h2 align="center">Update Service</h2>
                </div>
                <p>Please edit the input values and submit to update the service.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($serv_name_err)) ? 'has-error' : ''; ?>">
                        <label>Service Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $serv_name; ?>">
                        <span class="help-block"><?php echo $serv_name_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($serv_desc_err)) ? 'has-error' : ''; ?>">
                        <label>Service Description</label>
                        <textarea name="address" class="form-control"><?php echo $serv_desc; ?></textarea>
                        <span class="help-block"><?php echo $serv_desc_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($serv_unit_err)) ? 'has-error' : ''; ?>">
                        <label>Unit Price</label>
                        <input type="text" name="salary" class="form-control" value="<?php echo $serv_unit; ?>">
                        <span class="help-block"><?php echo $serv_unit_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="editServices.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>