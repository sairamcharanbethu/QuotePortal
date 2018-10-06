<?php
session_cache_expire(20);
session_start();
$inactive = 300;
if(isset($_SESSION['last_time']) ) {
    $session_life = time() - $_SESSION['last_time'];
    if($session_life > $inactive){
        echo "<script>window.location='logout.php';</script>";
    }
}
$_SESSION['last_time'] = time();

if(empty($_SESSION["authenticated"]) || $_SESSION["authenticated"] != 'true') {
   echo "<script>window.location='login.php';</script>";
}
//else{
//    $time = $_SERVER['REQUEST_TIME'];
//
//    /**
//     * for a 30 minute timeout, specified in seconds
//     */
//    $timeout_duration = 10;
//
//    /**
//     * Here we look for the user's LAST_ACTIVITY timestamp. If
//     * it's set and indicates our $timeout_duration has passed,
//     * blow away any previous $_SESSION data and start a new one.
//     */
//    if (isset($_SESSION['last_time']) &&
//        ($time - $_SESSION['last_time']) > $timeout_duration) {
//        session_unset();
//        session_destroy();
//        session_start();
//    }
//
//    /**
//     * Finally, update LAST_ACTIVITY so that our timeout
//     * is based on it and not the user's login time.
//     */
//    $_SESSION['last_time'] = $time;
//}

?>