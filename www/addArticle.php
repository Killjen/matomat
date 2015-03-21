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

        $articlename = filter_input(INPUT_POST, 'articlename', FILTER_SANITIZE_STRING);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if (!($articlename and $quantity and $price)) {
            die("Error: not enough parameters provided (maybe an input field was empty?) <a href=\"../stock.php\">Back</a>");
        }


        //catch failed connections
        if($conn->connect_error ){
            die("Couldn't connect to db: " . $conn->connect_error);}
        //TODO if(!is_numeric($_POST['amount']) OR $_POST['amount']>50 ){
        //die("Either your input was no number or too high (over 50€)");  
    
    //automatic id assigned
    $sql = "INSERT INTO stock VALUES (0,'" . $articlename . "'," . $quantity . "," .$price. ")";
    if ($conn->query($sql)==true){
        date_default_timezone_set('Europe/Berlin');
        $date = date('Y-m-d H:i:s');

        $sql="SELECT ArticleID FROM stock WHERE ArticleName ='" . $articlename ."'";

        if ($result=mysqli_query($conn,$sql))
          {
          // Fetch one and one row
          while ($row=mysqli_fetch_row($result))
            {
                $assignedID = $row[0];
            }
          // Free result set
          mysqli_free_result($result);
        }

        $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','$assignedID " .$articlename. "','CREATE','" . $date . "','Stock',null,'". $quantity. " " .$price. "')";
        $conn->query($sql);
        //echo $sql;
        header("Location: stock.php");
     }  
     else{
        echo "Error: No entries were added!"  ; 
     }
     $conn->close();
     ?>
     <?php endif; ?>
</body>
</html>   