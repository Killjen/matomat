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


if (login_check($mysqli) == true) {

    if (isset($_POST['articleid'])) {
        

        $conn = new mysqli($servername, $name, $dbpassword, $dbname);

      
        $articleid = filter_input(INPUT_POST, 'articleid', FILTER_SANITIZE_NUMBER_INT);
        $newarticlename = filter_input(INPUT_POST, 'newarticlename', FILTER_SANITIZE_STRING);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $logopath = filter_input(INPUT_POST, 'logopath', FILTER_SANITIZE_STRING);
        
        //since in php 0==empty, we replace temporarily with a dummy value ('zero')
        if($quantity==="0"){
            $quantity="zero";        
        }
        if (!($articleid and  ($newarticlename or $quantity or $price or isset($_POST['logopath'])))) {
            die("Error: not enough parameters provided (maybe the name, quantity or price input field was empty or price was set to 0?) <a href=\"../stock.php\">Back</a>");
        }

        $col = "Stock "; //column that was changed
        $newValue = "null"; //new value of that column
        if ($newarticlename){
            $col = "Stock ArticleName";
            $sql = "UPDATE stock SET ArticleName='$newarticlename' WHERE ArticleID='$articleid';";
            $oldSql = "SELECT ArticleName FROM stock WHERE ArticleID='$articleid';";
            $newValue = $newarticlename;
        } 
        if ($quantity){
            //replace dummy value
            if($quantity=="zero"){
                $quantity=0;        
            }
            $col = "Stock Quantity";
            $sql = "UPDATE stock SET Quantity=$quantity WHERE ArticleID='$articleid';";
            $oldSql = "SELECT Quantity FROM stock WHERE ArticleID='$articleid';";
            $newValue = $quantity;
        }             
        if($price){
            $col = "Stock Price";
            $sql = "UPDATE stock SET Price='$price' WHERE ArticleID='$articleid';";
            $oldSql = "SELECT Price FROM stock WHERE ArticleID='$articleid';";
            $newValue = $price;
        }
        if(isset($_POST['logopath'])){
            $col = "Stock LogoPath";
            $sql = "UPDATE stock SET LogoPath='$logopath' WHERE ArticleID='$articleid';";
            $oldSql = "SELECT LogoPath FROM stock WHERE ArticleID='$articleid';";
            $newValue = $logopath;
        } 


        if ($result=mysqli_query($conn,$oldSql))
          {
          // Fetch one and one row
          while ($row=mysqli_fetch_row($result))
            {
                $oldValue = $row[0];
            }
          // Free result set
          mysqli_free_result($result);
        }
       
        if ($conn->query($sql)==true){
            echo "Successfully updated existing table!";
            date_default_timezone_set('Europe/Berlin');
            $date = date('Y-m-d H:i:s');

            //get current article name:
            $currentSql = "SELECT ArticleName FROM stock WHERE ArticleID='$articleid';";
            if ($result=mysqli_query($conn,$currentSql))
              {
              // Fetch one and one row
              while ($row=mysqli_fetch_row($result))
                {
                    $articlename = $row[0];
                }
              // Free result set
              mysqli_free_result($result);
            }

            $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$articleid. " " .$articlename. "','CHANGE','" . $date . "','".$col."','".$oldValue."','". $newValue. "')";
            $conn->query($sql);
        }  else {
            die("Error: No entries were updated!"); 
        }
        // get back
        header('Location: ../stock.php');
        
    } else {
        // The correct POST variables were not sent to this page. 
        echo 'Invalid Request';
    } 
}
?>
