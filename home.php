<?php
require_once 'config/session.php';
require_once 'config/database.php';
include_once "index.php";

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
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css"><!--css file link in bootstrap folder-->
    <link rel="stylesheet" href="css/datatables.min.css">
    <style>
        /* Style the buttons */
        .btn {
            border: none;
            border-radius: 20px;
            outline: none;
            padding: 5px 10px;
            color:#ffffff;
            background-color: #5175C0;
            cursor: pointer;
        }

        /* Style the active class (and buttons on mouse-over) */
        .active, .btn:hover {
            transition: ease-out 0.3s;
            background-color: #666;
            color: white;
            border: 3px solid #5175C0;
        }
    </style>
</head>

<body style="background-color:whitesmoke;">

<div>
    <br>
    <h1 align=center style="color:	#185586;font-size:35px;font-family: 'Montserrat', sans-serif"><b>Customers List</b></h1>

           <table  id="table" class="table table-bordered table-hover">
            <thead>

            <tr align="center" style="background-color: #5175C0 ;color: whitesmoke;border-radius: 10px;
  overflow: hidden; border: 1px;">
                <th style="white-space: nowrap;">ID</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>City</th>
                <th>Province</th>
                <th>Zip</th>
                <th>Quotation</th>
            </tr>
            </thead>

            <?php
            include("config/database.php");
            $view_users_query="select * from customers";//select query for viewing users.
            $statement = $db->prepare($view_users_query);
            $statement->execute();
            while($row=$statement->fetch())//while look to fetch the result and store in a array $row.
            {
                $client_id=$row[0];
                $client_name=$row[1];
                $client_email=$row[2];
                $client_mobile=$row[4];
                $client_addr=$row[6];
                $client_city=$row[7];
                $client_province=$row[8];
                $client_zip=$row[9];



                ?>

                <tr style="background-color: floralwhite;">
                    <!--here showing results in the table -->
                    <td align="center"><?php echo $client_id;  ?></td>
                    <td align="center"><?php echo ucwords($client_name);  ?></td>
                    <td align="center"><?php echo $client_email;  ?></td>
                    <td align="center" style="white-space: nowrap;"><?php echo phone_number_format($client_mobile);  ?></td>
                    <td align="center"><?php echo ucwords($client_addr);  ?></td>
                    <td align="center"><?php echo ucfirst($client_city);  ?></td>
                    <td align="center"><?php echo ucfirst($client_province);  ?></td>
                    <td align="center" style="white-space: nowrap"><?php echo strtoupper($client_zip);  ?></td>

                    <td align="center"><a href="createCustomerSession.php?id=<?php echo $client_id ?>"><button class="btn">Create</button></a></td>
                </tr>

            <?php } ?>

        </table>
    </div>


</body>
<script src="js/jquery-3.3.1.min.js" ></script>
<script src="js/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table').DataTable( {

        } );
    } )
</script>
</html>




