<?php
/**
 * Created by PhpStorm.
 * User: saira
 * Date: 23-Sep-2018
 * Time: 12:24 PM
 */

include "config/session.php";

$_SESSION["customerid"]=$_GET['id'];
if ($_SESSION["customerid"]==''){
    header("Location:home.php");
}
else{
    header("Location:checkout.php");
}