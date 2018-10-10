<?php
require_once "config/session.php";
include_once "index.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services</title>
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
                    <h2 class="pull-left">Services</h2>
                    <a href="createServices.php" class="btn btn-success pull-right">Create New Service</a>
                </div>
                <?php
                // Include config file
                require_once "config/database.php";

                // Attempt select query execution
                $sql = "SELECT * FROM services";
                if($result = $db->query($sql)){
                    if($result->rowCount() > 0){
                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                        echo "<tr align=\"center\" style=\"background-color: #5175C0 ;color: whitesmoke;border-radius: 10px;
  overflow: hidden; border: 1px;\">";
                        echo "<th>#</th>";
                        echo "<th>Service Type</th>";
                        echo "<th>Description</th>";
                        echo "<th>Unit Price</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch()){
                            echo "<tr style=\"background-color: floralwhite;\">";
                            echo "<td>" . $row['serv_id'] . "</td>";
                            echo "<td width='25%'>" . ucwords($row['serv_name']) . "</td>";
                            echo "<td width='40%'>" . $row['description'] . "</td>";
                            echo "<td>$" . number_format($row['unit'],2) . "</td>";
                            echo "<td>";
                            echo "<a href='readService.php?id=". $row['serv_id'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                            echo "<a href='view&editService.php?id=". $row['serv_id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                            echo "<a href='deleteService.php?id=". $row['serv_id'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
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