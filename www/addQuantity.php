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
        if(!is_numeric($_POST['amount']) OR $_POST['amount']>50 ){
        die("Either your input was no number or too high (over 50)");  
    }
    $sql = "UPDATE stock SET Quantity=Quantity+" . $amount . " where ArticleID='". $articleid."'";
#this statement is not working for wrong(empty) usernames. needs to be fixed
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