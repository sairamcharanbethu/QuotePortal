<?php
include "index.php";
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Include config file
    require_once "config/database.php";

    // Prepare a select statement
    $sql = "SELECT * FROM services WHERE serv_id = :id";

    if($stmt = $db->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve individual field value
                $name = $row["serv_name"];
                $address = $row["description"];
                $salary = $row["unit"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Service</title>
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
                    <h1 align="center">View Service</h1>
                </div>
                <div class="form-group">
                    <label>Service Name</label>
                    <p class="form-control-static"><?php echo $row["serv_name"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <p class="form-control-static"><?php echo $row["description"]; ?></p>
                </div>
                <div class="form-group">
                    <label>Unit Price</label>
                    <p class="form-control-static">$<?php echo $row["unit"]; ?></p>
                </div>
                <p><a href="editServices.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>