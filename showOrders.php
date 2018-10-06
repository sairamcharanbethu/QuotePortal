<?php
require_once 'config/session.php';
require_once 'config/database.php';
include_once "index.php";

?>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css"><!--css file link in bootstrap folder-->
    <link rel="stylesheet" href="css/datatables.min.css">
</head>

<body style="background-color:whitesmoke;">

<div>
    <br>
    <h1 align=center style="color:	#185586;font-size:35px;font-family: 'Montserrat', sans-serif"><b>Quote Info</b></h1>

    <table  id="table" class="table table-bordered table-condensed">
        <thead>

        <tr align="center" style="background-color: #5175C0 ;color: whitesmoke;border-radius: 10px;
  overflow: hidden; border: 1px;">
            <th scope="col" style="width:10%">Quote #</th>
            <th scope="col" style="width: 15%">Customer ID</th>
            <th scope="col" >Company Name</th>
            <th scope="col" >Quote Price</th>
            <th scope="col" >Created Date</th>
        </tr>
        </thead>

        <?php
        include("config/database.php");
        $view_orders_query="select * from orders";//select query for viewing users.
        $statement = $db->prepare($view_orders_query);
        $statement->execute();

        while($row=$statement->fetch())//while look to fetch the result and store in a array $row.
        {
            $order_id=$row[0];
            $customer_id=$row[1];
            $total_price=$row[2];
            $created=$row[4];
            ?>
            <?php
            $sql = "SELECT * FROM customers WHERE id = :id";

            if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);

            // Set parameters
            $param_id =$customer_id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
            if($stmt->rowCount() == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Retrieve individual field value
                $name = $row["name"];
               }}}

            ?>
            <tr style="background-color: floralwhite;">
                <!--here showing results in the table -->
                <td align="center"><?php echo $order_id;  ?></td>
                <td align="center"><?php echo $customer_id;  ?></td>
                <td align="center"><?php echo $name;  ?></td>
                <td align="center">$<?php echo number_format($total_price,2);  ?></td>
                <td align="center"><?php echo $created;  ?></td>
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




