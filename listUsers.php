<?php
require_once "config/session.php";
include_once "index.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSCI - Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style type="text/css">
        .wrapper{
            width: 700px;
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
                    <h2 class="pull-left">Users</h2>
                    <a href="signup.php" class="btn btn-success pull-right">Create New User</a>
                </div>
                <?php
                // Include config file
                require_once "config/database.php";

                // Attempt select query execution
                $sql = "SELECT * FROM users";
                if($result = $db->query($sql)){
                    if($result->rowCount() > 0){
                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                        echo "<tr align=\"center\" style=\"background-color: #5175C0 ;color: whitesmoke;border-radius: 10px;
  overflow: hidden; border: 1px;\">";
                        echo "<th>#</th>";
                        echo "<th>User Name</th>";
                        echo "<th>Email</th>";
                        echo "<th>Join Date</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch()){
                            echo "<tr style=\"background-color: floralwhite;\">";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . ucwords($row['username']) . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . date($row['join_date']) . "</td>";
                            echo "<td align='center'>";
                            echo "<a href='deleteUser.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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