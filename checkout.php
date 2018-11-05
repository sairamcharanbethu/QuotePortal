<?php
// include database configuration file
include 'config/db.php';

// initializ shopping cart class
include 'Cart.php';
$cart = new Cart;
include_once "index.php";
// redirect to home if cart is empty
if($cart->total_items() <= 0){
    header("Location: services.php");
}

// set customer ID in session
$_SESSION['sessCustomerID'] = $_SESSION['customerid'];

// get customer details by session customer ID
$query = $db->query("SELECT * FROM customers WHERE id = ".$_SESSION['sessCustomerID']);
$custRow = $query->fetch_assoc();

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
    <title>Checkout</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .container{width: 100%;padding: 50px;}
        .table{width: 65%;float: left;}
        .shipAddr{width: 30%;float: left;margin-left: 30px;}
        .footBtn{width: 95%;float: left;}
        .orderBtn {float: right;}
    </style>
</head>
<body>
<div class="container">
    <h1 style="text-decoration: underline;">Quote Preview</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Product</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($cart->total_items() > 0){
            //get cart items from session
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                ?>
                <tr>
                    <td><?php echo $item["name"]; ?></td>
                    <td><?php echo $item["desc"]; ?></td>
                    <td><?php echo '$'.$item["price"].'CAD'; ?></td>
                    <td><?php echo $item["qty"]; ?></td>
                    <td><?php echo '$'.$item["subtotal"].' CAD'; ?></td>
                </tr>
            <?php } }else{ ?>
        <tr><td colspan="4"><p>No items in your cart......</p></td>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3"></td>
            <?php if($cart->total_items() > 0){ ?>
                <td class="text-center"><strong>Total</strong><sub>(without taxes)</sub> - <b><?php echo '$'.number_format($cart->total(),2).' CAD'; ?></b></strong></td>
            <?php } ?>
        </tr>
        </tfoot>
    </table>
    <div class="shipAddr">
        <h4 style="font-weight: bold;">Customer Details</h4>
        <p>Company Name: <?php echo ucwords($custRow['name']); ?></p>
        <p>Email: <?php echo $custRow['email']; ?></p>
        <p>Phone: <?php echo phone_number_format($custRow['phone']); ?></p> <p>Work:<?php echo phone_number_format($custRow['wphone']); ?></p>
        <p>Address: <?php echo $custRow['address']; ?>,<?php echo $custRow['city'];?> <br><?php echo $custRow['province'];?>, <?php echo $custRow['zip'];?></p>

    </div>
    <div class="footBtn">
        <a href="services.php" class="btn btn-warning" style="border-radius: 15px;"><i class="glyphicon glyphicon-menu-left"></i> Add more services..</a>
        <a href="cartAction.php?action=placeOrder" class="btn btn-success orderBtn" style="border-radius: 15px;">Generate Quote<i class="glyphicon glyphicon-menu-right"></i></a>
    </div>
</div>
</body>
</html>