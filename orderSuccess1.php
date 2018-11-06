<?php
if(!isset($_REQUEST['id'])){
    header("Location: home.php");
}
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include 'config/db.php';
$quote=$_GET['id'];
// initialize shopping cart class
include 'Cart.php';
$message = '';
$output='';
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
            <td class="unit" style="text-align: center;">$'.number_format($item_price,2).'</td>
            <td class="qty" style="text-align: center;">'.$item_qty.'</td>
            <td class="total" align="center">$'.number_format($item_total,2). '</td>
        </tr>';

        }
    }

    return $output;

}
$output=fetch_data();
$data=$output;
if(isset($_POST["action"]))
{
    if($cart->total_items() <= 0){
        header("Location: email.php");
    }

    require_once('tcpdf/tcpdf.php');

    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Invoice-SSCI");
    $obj_pdf->SetDefaultMonospacedFont('helvetica');
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    // remove default header/footer
    $obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
//    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $obj_pdf->SetFont('helvetica', '', 10);
    $obj_pdf->AddPage();
    $content = '';

    $date=date("F j, Y");
    $content .=' <link rel="stylesheet" href="css/styleQuote.css" media="all" />';
    $content .= '<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">';
    $content .= '
</br></br>
   <img src="img/logo.png" align="right">
    <div id="company" style="float: right">
        <div>3105 Dundas St W Suite 202, Mississauga, Ontario, L5L 3R8</div>
        <div>Contact Us:(602) 519-0450</div>
        <div><a href="mailto:company@example.com">info@sscinc.ca</a></div>
    </div>
    <h4>Quote to: '.$custRow['name'].'</h4>  
    <p align="left">Email: '.$custRow['email'].'</p>
    <p align="left">Contact No: '.$custRow['phone'].'</p>
    <p align="left">Address: '.$custRow['address'].' </p>
 
        <h4 align="right" style="display: inline-block;">Quote# '.$quote.'</h4> 
        <h4 align="right" style="display: inline-block;">Quote by: '.$_SESSION['username'].'</h4>
        <h4 align="right" style="display: inline-block;">Date:'.$date.'</h4>
        <h3 align="center"><u>Quote Estimate</u></h3><br /><br /> 
       
      <table class="table" border="0.05" cellspacing="0" cellpadding="5">  
           <tr>  
                
                <th align="center" width="20%" style="font-weight: bold">Service Name</th> 
                <th align="center" width="20%" style="font-weight: bold">Service Description</th>  
                <th align="center" width="20%" style="font-weight: bold">Price</th>  
                <th align="center" width="20%" style="font-weight: bold">Quantity</th>  
                <th align="center" width="20%" style="font-weight: bold">Subtotal</th>  
           </tr> ';
    $content .= fetch_data();

    $content .= '</table>';
    $content .='<h3 align="right">Tax 13%: '.number_format($taxVal,2).' CAD</h3>';
    $content .='<h3 align="right">Total: '.number_format($totalNo,2).' CAD</h3>
';
    $obj_pdf->SetLineStyle( array( 'width' => 3, 'color' => array(240,240,240)));
    $obj_pdf->Line(0,62,$obj_pdf->getPageWidth(),62);

    $content.='<div class="center" style="float: bottom;"> 
                        <img src="img/1.jpg" style="width:80px;height:50px;">
                        <img src="img/2.jpg" style="width:80px;height:50px;">
                        <img src="img/3.png" style="width:80px;height:50px;">
                        <img src="img/4.png" style="width:80px;height:50px;">
                        <img src="img/6.png" style="width:80px;height:50px;">
                        <img src="img/7.png" style="width:80px;height:50px;">
                        </div>
                        ';


    $obj_pdf->writeHTML($content);
    $filename='Invoice-Quote#'.$quote.'.pdf';
    ob_clean();
    $file=$obj_pdf->Output(__DIR__.'/quotations/'.$filename, 'F');
    $cart->destroy();

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
//        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  //Sets the SMTP hosts of your Email hosting, this for Godaddy
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = '465';        //Sets the default SMTP server port
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'sairamcharanbethu18@gmail.com';     //Sets SMTP username
        $mail->Password = 'Lassi626$';     //Sets SMTP password
        //Recipients
        $mail->setFrom('info@sscinc.ca', 'SSCI Inc.');
        $mail->addAddress($custRow['email'],$custRow['name']);     // Add a recipient
//        $mail->addAddress('ellen@example.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

        $file = __DIR__ .'/quotations/' . $filename;
        //Attachments
        $mail->addAttachment($file);         // Add attachments
        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject =  'Your Quote# '.$quote;
        $mail->Body    = 'Dear '.ucfirst($customerName).',<br>'
        .'Welcome to <b>SSCI</b><br> Please see your quotation attached. We are happy to serve you. <br><br> Thank you.
         <br> Regards,<br> <b>SSCI Inc.</b><br>3105 Dundas St W Suite 202, Mississauga , Ontario,L5L 3R8
         <br>Call us at: (602) 519-0450<br>
         <b><a href="mailto:company@example.com" style="text-decoration: none;">info@sscinc.ca</a></b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        $message='E-mail has sent successfully';

    } catch (Exception $e) {
        $message= 'Message could not be sent. Mailer Error:';
    }
    $cart->destroy();
unlink($file);
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
        .button {
            background-color: #5175C0; /* Green */
            border: none;
            border-radius: 10px;
            color: white;
            padding: 12px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            cursor: pointer;
        }
        .button:hover{
            background-color: #FFFFFF; /* Green */
            color: black;
            border: 1px solid #5175C0;
        }


    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <form method="post">
        <div id="formsubmitbutton">
            <a href="back.php"><button class="button" style="float: left;" type="button"><i class="fas fa-home"></i> Go Home</button></a>&nbsp;
            <button type="submit" class="button" name="action" id="email" value="Send PDF" style="float: right"> <i class="fas fa-envelope"></i> Send PDF</button>
            <p align="center" style="color: #1c7430;font-weight: bold;"><?php echo $message; ?></p>
        </div>

    </form>
    <br>
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
        echo $data;
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
