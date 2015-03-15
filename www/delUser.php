<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!Doctype html>
<html>
<body>
        <?php if (login_check($mysqli) == true) : ?>
    	<?php
    	$servername = "localhost";
    	$dbusername   = "matomat";
    	$dbpassword = "matomat94";
    	$dbname     = "matomat";

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

    	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    	//catch failed connections
    	if($conn->connect_error ){
    	    die("Couldn't connect to db: " . $conn->connect_error);
        }
	    $sql = "DELETE FROM users where Username='". $username ."'";
	    if ($conn->query($sql)==true){
            date_default_timezone_set('Europe/Berlin');
            $date = date('Y-m-d H:i:s');

            $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$username. "','DELETE','" . $date . "','Users',null,null)";
            $conn->query($sql);
            //echo $sql;
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
