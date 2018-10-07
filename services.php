<?php
// include database configuration file
include 'config/db.php';
include "config/session.php";
include "index.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Shopping Cart Tutorial</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="js/datatables.min.js"></script>
    <style>
        .container{padding: 50px;}
        .cart-link{width: 100%;text-align: right;display: block;font-size: 22px;}
    </style>
</head>
<body style="background-color: whitesmoke;">
<div class="container">

    <a href="viewCart.php" class="cart-link" title="View Cart"><i class="glyphicon glyphicon-shopping-cart"></i><span style="font-size: medium">View Cart</span></a>
    <div id="products" class="row list-group">
        <div class="table-scroll">
            <h1 align="center">Services</h1>

            <div><!--this is used for responsive display in mobile and other devices-->



                <table id="table" class="table table-bordered table-hover table-striped table-responsive" style="table-layout: auto;">
                    <thead>

                    <tr align="center" style="background-color: #5175C0 ;color: whitesmoke;border-radius: 10px;
  overflow: hidden; border: 1px;">
                        <th>Service ID</th>
                        <th>Service Type</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <?php
                    $query=$db->query("SELECT * FROM services ORDER BY serv_id");
                    if($query->num_rows > 0){
                        while($row = $query->fetch_assoc()){

                            ?>
                        <tr align="center" style="background-color: floralwhite;">
                            <td><?php echo $row["serv_id"]?></td>
                            <td><?php echo $row["serv_name"]?></td>
                            <td><?php echo $row["description"]?></td>
                            <td>$<?php echo $row["unit"]?></td>
                            <td><a href="cartAction.php?action=addToCart&id=<?php echo $row["serv_id"] ?>"><button class="btn btn-success" style="border-radius: 15px;">Add to cart</button></a></td>
                        </tr>
                        <?php } }else{ ?>
                        <p>Services(s) not found.....</p>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready( function () {
        $('#table').DataTable();
    } );
</script>
</html>