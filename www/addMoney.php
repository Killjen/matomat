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
    	if ($conn->query($sql)==true){
		echo "Successfully updated existing table!";
   	 }	
	 else{
		echo "Error: No entries were updated!"	; 
	 }
	 $conn->close();
   	 ?>
	 <a href="index.php">Back</a>
</body>
</html>	  
