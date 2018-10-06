<?php require_once 'config/session.php';
$username=$_SESSION['username'];
if($_SESSION['username']=='mujeeb'){
    include 'indexAdmin.php';
}
else
    include "indexUser.php";
?>
