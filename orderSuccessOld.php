<?php
if(!isset($_REQUEST['id'])){
    header("Location: home.php");
}
?>
<?php
include 'dbConfig.php';
$quote=$_GET['id'];
// initialize shopping cart class
include 'Cart.php';

$cart = new Cart;
$totalNo=0;
$taxVal=0;
// redirect to home if cart is empty
if($cart->total_items() <= 0){
    header("Location: home.php");
}


// get customer details by session customer ID
$query = $db->query("SELECT * FROM customers WHERE id = ".$_SESSION['sessCustomerID']);
$custRow = $query->fetch_assoc();

function fetch_data()
{
    $cart = new Cart;
    $output = '';


    if ($cart->total_items() > 0) {
//get cart items from session
        $cartItems = $cart->contents();
        foreach ($cartItems as $item) {
            $itemName=$item["name"];
            $itemDesc=$item["desc"];
            $item_price=$item["price"];
            $item_qty=$item["qty"];
            $item_total=$item["subtotal"];
//        echo $itemName;
            $output .= ' <tr>
            <td align="center">'.$itemName.'</td>
            <td align="center">'.$itemDesc.'</td>
            <td align="center">$'.$item_price.'&nbsp;CAD</td>
            <td align="center">'.$item_qty.'</td>
            <td align="center">$'.$item_total. '&nbsp;CAD</td>
        </tr>';

        }
        return $output;
    }

}
$message='';
if(isset($_POST["action"])){

}
if(isset($_POST["create_pdf"]))
{
    require_once('tcpdf/tcpdf.php');

    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetTitle("Invoice-SSCI");

// set default header data
    $obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $obj_pdf->SetDefaultMonospacedFont('helvetica');
    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    $obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $obj_pdf->SetFont('helvetica', '', 10);
    $obj_pdf->AddPage();

    $obj_pdf->SetLineStyle( array( 'width' => 3, 'color' => array(150,150,150)));
    $obj_pdf->Line(0,7,$obj_pdf->getPageWidth(),7);
    $content = '';

    $date=date("F j, Y");
    $totalNo=0;
    $taxVal=0;
    if($cart->total_items()>0){

        $totalNo=$cart->total();
        $taxVal=$totalNo*0.13;
        $totalNo=$totalNo+$taxVal;
    }
    $content .= '

</br></br>

    <header class="clearfix">
    <div id="logo">
        <img src="logo.png">
    </div>
    <div id="company">
        <h2 class="name">SSCI</h2>
        <div>3105 Dundas St W Suite 202, Mississauga , Ontario,L5L 3R8</div>
        <div>(602) 519-0450</div>
        <div><a href="mailto:company@example.com">info@sscinc.ca</a></div>
    </div>
</header>
    <h3>Quote to: '.$custRow['name'].'</h3>  
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
    $content .='<h3 align="right">Tax 13%: '.$taxVal.' CAD</h3>';
    $content .='<h3 align="right">Total: '.$totalNo.' CAD</h3>
';
    $obj_pdf->SetLineStyle( array( 'width' => 3, 'color' => array(240,240,240)));
    $obj_pdf->Line(0,62,$obj_pdf->getPageWidth(),62);


    $content.='<div class="center"> 
                        <img src="1.jpg" style="width:80px;height:50px;">
                        <img src="2.jpg" style="width:80px;height:50px;">
                        <img src="3.png" style="width:80px;height:50px;">
                        <img src="4.png" style="width:80px;height:50px;">
                        <img src="6.png" style="width:80px;height:50px;">
                        <img src="7.png" style="width:80px;height:50px;">
                        </div>
                        ';


    $obj_pdf->writeHTML($content);


    $cart->destroy();
//    header("Location:services.php");

    $obj_pdf->Output('Invoice-Quote#'.$quote.'.pdf', 'I');

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice - PDF</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="logo.png">
    </div>
</header>
<br /><br />
<div class="container" style="width:700px;">
    <h3 align="center">Quote Information</h3><br />
    <h4 align="left" style="display: inline;">Quote #<?php echo $quote ?></h4>
    <h4 align="right" >Date: <?php echo date("F j, Y") ?></h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th align="center" width="20%" style="font-weight: bold">Service Name</th>
                <th align="center" width="45%" style="font-weight: bold">Service Description</th>
                <th align="center" width="20%" style="font-weight: bold">Unit Price</th>
                <th align="center" width="10%" style="font-weight: bold">Qty</th>
                <th align="center" width="30%" style="font-weight: bold">Subtotal</th>
            </tr>
            <?php
                 echo fetch_data();
            ?>
        </table>
        <br />
          <form method="post">
            <input type="submit" name="create_pdf" class="btn btn-danger" value="Create PDF" />
<!--              <a href="email.php?id=--><?php //echo $_REQUEST['id'] ?><!--"> <input type="button" class="btn btn-danger" value="PDF Send" /></a>-->
            <a href="checkout.php"><button type="button" class="btn btn-secondary"  style="float: right;" name="goback">Go Back!</button></a>
        </form>
    </div>
</div>

</body>
<footer>

</footer>

</html>


