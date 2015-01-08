<!Doctype html>
<html>
<body>
    	<?php
    	$servername = "localhost";
    	$username   = "matomat";
    	$dbpassword = "matomat94";
    	$dbname     = "matomat";
    	$conn = new mysqli($servername, $username, $dbpassword, $dbname);
    	//catch failed connections
    	if($conn->connect_error ){
    	    die("Couldn't connect to db: " . $conn->connect_error);}
    	if(!is_numeric($_POST['amount']) OR $_POST['amount']>50 ){
		die("Either your input was no number or too high (over 50€)");	
	}
	$sql = "UPDATE users SET Balance=Balance+" . $_POST['amount'] . " where Username='". $_POST["username"]."'";
#this statement is not working for wrong(empty) usernames. needs to be fixed
	    	if ($conn->query($sql)==true){
		header("Location: index.php");
   	 }	
	 else{
		echo "Error: No entries were updated!"	; 
	 }
	 $conn->close();
	 ?>
</body>
</html>	  
