<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

$servername = "localhost";
$name   = "matomat";
$dbpassword   = "matomat94";
$dbname     = "matomat";


 

if (isset($_POST['username'])) {
    

    $conn = new mysqli($servername, $name, $dbpassword, $dbname);

    

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $newusername = filter_input(INPUT_POST, 'newusername', FILTER_SANITIZE_STRING);
    $balance = filter_input(INPUT_POST, 'balance', FILTER_SANITIZE_NUMBER_INT);
    
    if ($newusername){
        $sql = "UPDATE users SET Username='$newusername' WHERE Username='$username';";
    } 
    if ($balance){
        $sql = "UPDATE users SET Balance=$balance WHERE Username='$username';";
    }             
 
    if ($conn->query($sql)==true){
        echo "Successfully updated existing table!";
    }  else {
        die("Error: No entries were updated!"); 
    }
    // get back
    header('Location: ../index.php');
    
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
} 
?>
