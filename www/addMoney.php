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
    	$conn = $mMysqli;

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $amount= filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    	//catch failed connections
    	if($conn->connect_error ){
    	    die("Couldn't connect to db: " . $conn->connect_error);
        }
    //check the old balance:
    $sql="SELECT Balance FROM users WHERE Username ='" . $username ."'";

    if ($result=mysqli_query($conn,$sql))
      {
      // Fetch one and one row
      while ($row=mysqli_fetch_row($result))
        {
            $balance = $row[0];
        }
      // Free result set
      mysqli_free_result($result);
    }

	$sql = "UPDATE users SET Balance=Balance+" . $amount . " where Username='". $username ."'";
	    	if ($conn->query($sql)==true){
        		date_default_timezone_set('Europe/Berlin');
                $date = date('Y-m-d H:i:s');
                $newbalance = $balance + $amount;
                $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$username. "','CHANGE','" . $date . "','Users Balance','".$balance."','". $newbalance. "')";
                $conn->query($sql);
                header("Location: index.php");
           	 }	
	 else{
		echo "Error: No entries were updated!<br>"	; 
        echo "<a href='index.php'>Back</a>";
	 }
	 $conn->close();
	 ?>
     <?php endif; ?>
</body>
</html>	  
