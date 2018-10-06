<?php

$username = 'root';
$dsn = 'mysql:host=localhost; dbname=cart';
$password = '';


try{

    // Create an instance  of the PDO class with the required parameters

    $db = new PDO($dsn,$username,$password);

    // set PDO Error mode exception
    $db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //Display Success message
    //echo "Connected to the register database";

}catch (PDOException $ex){

    echo "Connection failed to the database".$ex ->getMessage();
}