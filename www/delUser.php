<?php
include_once 'db_connect.php';
include_once 'functions.php';

sec_session_start();
?>
<!Doctype html>
<html>
<body>
        <?php if (login_check($mysqli) == true) : ?>
    	<?php
    	$servername = "localhost";
    	$username   = "matomat";
    	$dbpassword = "matomat94";
    	$dbname     = "matomat";
    	$conn = new mysqli($servername, $username, $dbpassword, $dbname);
    	//catch failed connections
    	if($conn->connect_error ){
    	    die("Couldn't connect to db: " . $conn->connect_error);}
	$sql = "DELETE FROM users where Username='". $_POST["username"]."'";
	    	if ($conn->query($sql)==true){
	 	header("Location: index.php");
   	 }	
	 else{
		echo "Error: No entries were updated!"	; 
	 }
	 $conn->close();
   	 ?>
     <?php endif; ?>
</body>
</html>	  
