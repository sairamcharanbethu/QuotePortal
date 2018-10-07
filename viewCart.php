<?php
// initializ shopping cart class
include 'Cart.php';
require_once 'config/session.php';
include_once "index.php";
$cart = new Cart;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Cart - PHP Shopping Cart Tutorial</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .container{padding: 50px;}
        input[type="number"]{width: 20%;}
    </style>
    <script>
        function updateCartItem(obj,id){
            $.get("cartAction.php", {action:"updateCartItem", id:id, qty:obj.value}, function(data){
                if(data == 'ok'){
                    location.reload();
                }else{
                    alert('Cart update failed, please try again.');
                }
            });
        }
    </script>
</head>
<body>
<div class="container">
    <h1 align="center">Service List</h1>

    <table class="table table-condensed">
        <thead>
        <tr align="center" style="background-color: #0B4F7B ;color: whitesmoke;border-radius: 10px;
  overflow: hidden; border: 1px;">
            <th>Service</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>&nbsp;</th>
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
                    <td><?php echo '$'.$item["price"].' CAD'; ?></td>
                    <td><input type="number" class="form-control text-center" value="<?php echo $item["qty"]; ?>" onchange="updateCartItem(this, '<?php echo $item["rowid"]; ?>')" onkeypress="this.style.width = ((this.value.length + 1) * 8) + 'px';"></td>
                    <td><?php echo '$'.$item["subtotal"].' CAD'; ?></td>
                    <td>
                        <a href="cartAction.php?action=removeCartItem&id=<?php echo $item["rowid"]; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
            <?php } }else{ ?>
        <tr><td colspan="5"><p>Your list is empty.....</p></td>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <td style="border-radius: 15px;"><a href="services.php" class="btn btn-warning" style="border-radius: 15px;"><i class="glyphicon glyphicon-menu-left"></i> Add more...</a></td>
            <td colspan="3"></td>
            <?php if($cart->total_items() > 0){ ?>

                <td style="float: right; border-radius: 15px;"><a href="checkout.php" class="btn btn-success btn-block" style="border-radius: 15px;" >Proceed <i class="glyphicon glyphicon-menu-right"></i></a></td>
            <?php } ?>
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>