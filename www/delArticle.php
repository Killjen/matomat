<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

        $articleid = filter_input(INPUT_POST, 'articleid', FILTER_SANITIZE_NUMBER_INT);
        $articlename = filter_input(INPUT_POST, 'articlename', FILTER_SANITIZE_STRING);

        //catch failed connections
        if($conn->connect_error ){
            die("Couldn't connect to db: " . $conn->connect_error);}
    $sql = "DELETE FROM stock where ArticleID='". $articleid."'";
            if ($conn->query($sql)==true){
                date_default_timezone_set('Europe/Berlin');
                $date = date('Y-m-d H:i:s');

                $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$articleid. " " .$articlename. "','DELETE','" . $date . "','Stock',null,null)";
                $conn->query($sql);
                //echo $sql;
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
