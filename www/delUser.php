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

        //get old balance:
        //check the old balance:
        $bSql="SELECT Balance FROM users WHERE Username ='" . $username ."'";

        if ($result=mysqli_query($conn,$bSql))
          {
          // Fetch one and one row
          while ($row=mysqli_fetch_row($result))
            {
                $balance = $row[0];
            }
          // Free result set
          mysqli_free_result($result);
        } else {
            echo "Error: No entries were updated!"  ; 
        }

	    if ($conn->query($sql)==true){
            date_default_timezone_set('Europe/Berlin');
            $date = date('Y-m-d H:i:s');

            $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$username. "','DELETE','" . $date . "','Users','".$balance."',null)";
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
