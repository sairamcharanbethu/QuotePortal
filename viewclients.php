<?php
require_once "config/session.php";
include_once 'index.php';
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
    <title>Clients</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style type="text/css">
        .wrapper{
            width: 100%;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body style="background-color: whitesmoke">
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header clearfix">
                    <h2 class="pull-left">Registered Clients</h2>
                    <a href="createClient.php" class="btn btn-success pull-right">Create New Client</a>
                </div>
                <?php
                // Include config file
                require_once "config/database.php";

                // Attempt select query execution
                $sql = "SELECT * FROM customers";
                if($result = $db->query($sql)){
                    if($result->rowCount() > 0){
                        echo "<table class='table table-bordered table-striped table-responsive' 
style='width: 100%;align-content: center;'>";
                        echo "<thead>";
                        echo "<tr align=\"center\" style=\"background-color: #5175C0 ;color: whitesmoke;border-radius: 10px;
  overflow: hidden; border: 1px;\">";
                        echo "<th>#</th>";
                        echo "<th >Client Name</th>";
                        echo "<th>Email</th>";
//                        echo "<th>Alternate Email</th>";
                        echo "<th>Mobile</th>";
//                        echo "<th >Work Phone</th>";
                        echo "<th>Address</th>";
                        echo "<th>City</th>";
                        echo "<th>Province</th>";
//                        echo "<th >Postal Code</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch()){
                            echo "<tr style=\"background-color: floralwhite;\">";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td style='font-weight: bold;'>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
//                            echo "<td>" . $row['a_email'] . "</td>";
                            echo "<td>" . phone_number_format($row['phone']) . "</td>";
//                            echo "<td>" . $row['wphone'] . "</td>";
                            echo "<td>" . ucwords($row['address']) . "</td>";
                            echo "<td>" . ucwords($row['city']) . "</td>";
                            echo "<td>" . strtoupper($row['province']) . "</td>";
//                            echo "<td>" . $row['zip'] . "</td>";
                            echo "<td style='white-space: nowrap;'>";
                            echo "<a href='readClient.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                            echo "<a href='updateClients.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                            echo "<a href='deleteClient.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        unset($result);
                    } else{
                        echo "<p class='lead'><em>No records were found.</em></p>";
                    }
                } else{
                    echo "ERROR: Could not able to execute $sql. " . $db->error;
                }

                // Close connection
                unset($db);
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>