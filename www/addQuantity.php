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
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
        //catch failed connections

        $articleid = filter_input(INPUT_POST, 'articleid', FILTER_SANITIZE_NUMBER_INT);
        $articlename = filter_input(INPUT_POST, 'articlename', FILTER_SANITIZE_STRING);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if (!$articleid or  !($articlename and $amount)) {
            die("Error: not enough parameters provided (maybe an input field was empty?) <a href=\"../stock.php\">Back</a>");
        }

        $sql="SELECT Quantity FROM stock WHERE ArticleID ='" . $articleid ."'";

        if ($result=mysqli_query($conn,$sql))
          {
          // Fetch one and one row
          while ($row=mysqli_fetch_row($result))
            {
                $quantity = $row[0];
            }
          // Free result set
          mysqli_free_result($result);
        }

        if($conn->connect_error ){
            die("Couldn't connect to db: " . $conn->connect_error);}
    
    $sql = "UPDATE stock SET Quantity=Quantity+" . $amount . " where ArticleID='". $articleid."'";
     if ($conn->query($sql)==true){
        date_default_timezone_set('Europe/Berlin');
        $date = date('Y-m-d H:i:s');
        $newquantity = $quantity + $amount;
        $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$articleid. " " .$articlename. "','CHANGE','" . $date . "','Stock Quantity','".$quantity."','". $newquantity. "')";
        $conn->query($sql);
        header("Location: stock.php");
     }  
     else{
        echo "Error: No entries were updated!"  ; 
     }
     $conn->close();
     ?>
     <?php endif; ?>
</body>
</html>   