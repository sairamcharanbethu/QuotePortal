
<?php include_once 'config/session.php';

session_destroy();
header('location: login.php');