<?php
include "index.php";
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Include config file
    require_once "config/database.php";

    // Prepare a select statement
    $sql = "SELECT * FROM customers WHERE id = :id";

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
                $id=$row["id"];
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
function phone_number_format($number) {
    // Allow only Digits, remove all other characters.
    $number = preg_replace("/[^\d]/","",$number);

    // get number length.
    $length = strlen($number);

    // if number = 10
    if($length == 10) {
        $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
    }

    return $number;

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Customer</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
    <body>
<div class="container bootstrap snippet">
    <div class="panel-body inf-content">
        <div class="row">
            <div class="col-centered" >
                <strong style="font-size: 20px;" >Client Information</strong><br>
                <div class="table-responsive">
                    <table class="table table-condensed table-responsive table-user-information">
                        <tbody>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-asterisk textsuccess"></span>
                                    ID
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo $id?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-user  textsuccess"></span>
                                    Name
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo $name?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-envelope textsuccess"></span>
                                    Email
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo $row["email"]; ?>
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-envelope textsuccess"></span>
                                    Alternate Email
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo $row["a_email"]; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-phone textsuccess"></span>
                                    Mobile
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo phone_number_format($row["phone"]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-phone-alt textsuccess"></span>
                                    Work Phone
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo phone_number_format($row["wphone"]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-flag textsuccess"></span>
                                    Address
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo ucwords($row["address"]); ?>, <?php echo ucwords($row["city"]);?>, <?php echo strtoupper($row["province"]);?>, <?php echo strtoupper($row["zip"]);?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-calendar textsuccess"></span>
                                    Created On
                                </strong>
                            </td>
                            <td class="textsuccess">
                                <?php echo date($row["created"]);?>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <p><a href="viewclients.php" class="btn btn-success">Back</a></p>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
<style>
    .inf-content{
        margin-top: 20px;
        font-size: 16px;
            }
    .col-centered{
        float: none;
        margin: 0 auto;
    }
    .textsuccess{
        color: #5175C0;
    }
</style>
</html>