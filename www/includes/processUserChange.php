<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.


if (login_check($mysqli) == true) {
    if (isset($_POST['username']) and (isset($_POST['newusername']) or isset($_POST['balance']) or isset($_POST['userid']) or isset($_POST['fromLog']))) {
        

        $conn = $mMysqli;

        

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $newusername = filter_input(INPUT_POST, 'newusername', FILTER_SANITIZE_STRING);
        $balance = filter_input(INPUT_POST, 'balance', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
        $fromLog = filter_input(INPUT_POST, 'fromLog', FILTER_VALIDATE_BOOLEAN); //if this is the case the Log entry has to be deleted
        
        //echo "balance: $balance";

        $col = "Users";
        $newValue = "null";
        $oldValue = "null";

        //since in php 0==empty, we replace temporarily with a dummy value ('zero')
        if($balance==="0"){
            $balance="zero";        
        }

        //at least one additional character has to be set and not null:
        if (!($username and  ($newusername or $balance or $userid or $fromLog))) {
            die("Error: not enough parameters provided (maybe an input field was empty?) <a href=\"../index.php\">Back</a>");
        }

        if ($newusername) {
            //since username should be unique:
            $checkSql = "SELECT * FROM users WHERE Username='$newusername'"; 
            $checkQuery = mysqli_query($conn, $checkSql);
            $count = mysqli_num_rows($checkQuery); 

            if($count>0){
                die("Error: There already exists a user with name " . $newusername ."<br> <a href=\"../index.php\">Back</a>");
            }


            $col = "Users Name";
            $sql = "UPDATE users SET Username='$newusername' WHERE Username='$username';";
            $oldSql = "SELECT Username FROM users WHERE Username='$username';";
            $newValue = $newusername;
        } 
        if ($balance){
            if ($balance=="zero") {
                $balance=0;
            }
            $col = "Users Balance";
            $sql = "UPDATE users SET Balance=$balance WHERE Username='$username';";
            $oldSql = "SELECT Balance FROM users WHERE Username='$username';";
            $newValue = $balance;
        }             
    	if($userid){
            $checkSql = "SELECT * FROM users WHERE UserID='$userid'"; 
            $checkQuery = mysqli_query($conn, $checkSql);
            $count = mysqli_num_rows($checkQuery); 

            if($count>0){
                die("Error: There already exists a user with ID " . $userid ."<br> <a href=\"../index.php\">Back</a>");
            }
            $col = "Users ID";
    		$sql = "UPDATE users SET UserID='$userid' WHERE Username='$username';";
            $oldSql = "SELECT UserID FROM users WHERE Username='$username';";
            $newValue = $userid;
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



            $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$username. "','CHANGE','" . $date . "','".$col."','".$oldValue."','". $newValue. "')";
            $conn->query($sql);

            if ($fromLog) {
                $sql = "DELETE FROM log where RFID='". $userid ."'";
                $conn->query($sql);
            }
        }  else {
            die("Error: No entries were updated!"); 
        }
        // get back
        header('Location: ../index.php');
        
    } else {
        // The correct POST variables were not sent to this page. 
        echo 'Invalid Request';
    } 
}
?>
