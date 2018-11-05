<?php
/**
 * Created by PhpStorm.
 * User: saira
 * Date: 03-Nov-2018
 * Time: 5:11 PM
 */
include 'Cart.php';
$cart = new Cart;
$cart->destroy();
header("Location:home.php");