<?php
if(!isset($_REQUEST['id'])){
    header("Location: home.php");
}
?>
<?php
include 'config/db.php';
$quote=$_GET['id'];
// initialize shopping cart class
include 'Cart.php';

$cart = new Cart;
$totalNo=0;
$taxVal=0;
$subtotal=0;
// redirect to home if cart is empty
if($cart->total_items() <= 0){
    header("Location: home.php");
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
// get customer details by session customer ID
$query = $db->query("SELECT * FROM customers WHERE id = ".$_SESSION['sessCustomerID']);
$custRow = $query->fetch_assoc();
$customerName=$custRow['name'];
$customerEmail=$custRow['email'];
$customerPhone=$custRow['phone'];
$customerWPhone=$custRow['wphone'];
$customerAddress=$custRow['address'];
$customerAddress2=$custRow['city'];
$customerProvince=$custRow['province'];
$customerzip=$custRow['zip'];




if($cart->total_items()>0 && $customerProvince=='alberta' || 'british columbia' || 'manitoba' ||'northwest 
territories' ||'nunavut' || 'quebec' || 'saskatchewan' || 'yukon'){

    $subtotal=$cart->total();
    $taxVal=$subtotal*0.05;
    $totalNo=$subtotal+$taxVal;
}

elseif($cart->total_items()>0 && $customerProvince == 'ontario' || 'ON'){

    $subtotal=$cart->total();
    $taxVal=$subtotal*0.13;
    $totalNo=$subtotal+$taxVal;
}



function fetch_data()
{
    $cart = new Cart;
    $output = '';


    if ($cart->total_items() > 0) {
//get cart items from session
        $cartItems = $cart->contents();

        foreach ($cartItems as $item) {

//            $itemid= $item['id'];
            $itemName=$item["name"];
            $itemDesc=$item["desc"];
            $item_price=$item["price"];
            $item_qty=$item["qty"];
            $item_total=$item["subtotal"];
//        echo $itemName;
            $output .= ' <tr align="center">
           
            <td class="no">'.ucwords($itemName).'</td>
            <td class="desc" align="center">'.ucwords($itemDesc).'</td>
            <td class="unit" style="text-align: center;">$'.number_format($item_price).'</td>
            <td class="qty" style="text-align: center;">'.$item_qty.'</td>
            <td class="total" align="center">$'.number_format($item_total). '</td>
        </tr>';

        }

        $cart->destroy();

    }

    return $output;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quote-<?php echo $quote?></title>
    <link rel="stylesheet" href="css/styleQuote.css" media="all" />
    <script src="js/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
          integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
          crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="js/datatables.min.js"></script>
<style>
    .btn {

        float: left;
        color: #5e5e5e;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-color: #d1dade;
        -webkit-border-radius: 3px;
        background-image: none !important;
        border: none;
        text-shadow: none;
        box-shadow: none;
        transition: all 0.12s linear 0s !important;
        font: 14px/20px "Helvetica Neue",Helvetica,Arial,sans-serif;
    }

    .btn-cons {
    }

    .btn-primary {
        color: #fff;
        background-color: #428bca;
        border-color: #357ebd;
    }

    .btn-primary:hover{
        color: white;
        background-color: #5e5e5e;
        border-color: #5e5e5e;
    }
    .btn-default, .btn-primary, .btn-success, .btn-info, .btn-warning, .btn-danger {
        text-shadow: 0 -1px 0 rgba(0,0,0,0.2);
        -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.15),0 1px 1px rgba(0,0,0,0.075);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.15),0 1px 1px rgba(0,0,0,0.075);
    }
</style>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="img/logo.png">
    </div>
    <div id="company">
        <h2 class="name">SSCI</h2>
        <div>3105 Dundas St W Suite 202, Mississauga , ON, L5L 3R8</div>
        <div>(602) 519-0430</div>
        <div><a href="mailto:info@sscinc.ca">info@sscinc.ca</a></div>
    </div>
</header>
<main>
    <div id="details" class="clearfix">
        <div id="client">
            <div class="to">Quote created to:</div>
            <h2 class="name">Name: <?php echo ucwords($customerName)?></h2>
            <div class="address">Address: <?php echo ucwords($customerAddress)?>,
                <?php echo $customerAddress2;?>, <?php echo $customerProvince;?>, <?php echo $customerzip;?></div>
            <div class="email">Email: <a href="mailto:john@example.com"><?php echo $customerEmail?></a></div>
            <div class="email">Phone: <?php echo phone_number_format($customerPhone)?>,
                Work: <?php echo phone_number_format($customerWPhone); ?></div>
        </div>
        <div id="invoice">
            <h1>Quote #<?php echo $quote ?></h1>
            <div class="date">Date of Quote: <?php echo date("d-m-Y") ?></div>
            <div class="date">User: <?php echo ucfirst($_SESSION["username"])?></div>
        </div>
    </div>
    <button class="btn btn-primary btn-cons" onclick="window.print()">
        <i class="fa fa-print" aria-hidden="true"></i> Print</button>
    <table id="table" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;border-radius: 1em;
  overflow: hidden;">
        <thead>
        <tr>
            <th class="no" width="20%">SERVICE</th>
            <th class="desc" width="55%">DESCRIPTION</th>
            <th class="unit" width="10%">UNIT PRICE</th>
            <th class="qty" width="05%">QUANTITY</th>
            <th class="total" width="10%">TOTAL</th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo fetch_data();
        ?>
        </tbody>
        <tfoot style="display: table-row-group">
        <tr>
            <td colspan="2"></td>
            <td colspan="2">SUBTOTAL</td>
            <td>$<?php echo number_format($subtotal,2) ?> CAD</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">TAX </td>
            <td>$<?php echo number_format($taxVal,2)?> CAD</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">GRAND TOTAL</td>
            <td>$<?php echo number_format($totalNo,2)?> CAD</td><br>
        </tr>
        </tfoot>
    </table>

</main>
<div id="thanks" align="center" style="font-size: 1em; font-weight: bold;">Thank you for your business!</div>

<footer style="align-content: center">

    <img src="img/1.jpg" style="padding:2px;height:55PX;width: 100PX; ">
        <img src="img/2.jpg" style="padding:2px; height:55PX; width: 100PX; ">
        <img src="img/3.png" style="padding:2px; height:55PX;width: 100PX; ">
        <img src="img/4.png" style="padding:2px; height:55PX;width: 100PX; ">
        <img src="img/5.png" style="padding:2px; height:55PX;width: 110PX; ">
        <img src="img/6.png" style="padding:2px; height:55PX;width: 100PX; ">
        <img src="img/7.png" style="padding:2px; height:55PX;width: 100PX;">

</footer>
</body>

<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            "bPaginate": false,
            "paging":   false,
            "lengthChange": false,
            "searching": false,
            "bInfo" : false
        } );
    } )
</script>
</html>
