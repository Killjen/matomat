<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
sec_session_start();
?>

        <?php if (login_check($mysqli) == true) : ?>
        <?php
        $servername = "localhost";
        $dbusername   = "matomat";
        $dbpassword = "matomat94";
        $dbname     = "matomat";
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $balance = filter_input(INPUT_POST, 'balance', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if (empty($userid)) {
            die("Error: Empty userid<br> <a href=\"log.php\">Back</a>");
        }
        if (empty($username)) {
            die("Error: Empty username<br> <a href=\"log.php\">Back</a>");
        }

        //catch failed connections
        if($conn->connect_error ){
            die("Couldn't connect to db: " . $conn->connect_error);}
        //TODO if(!is_numeric($_POST['amount']) OR $_POST['amount']>50 ){
        //die("Either your input was no number or too high (over 50€)");  

            //RFID must be in LOG to ensure correct RFIDs
    $checkSql = "SELECT * FROM log WHERE `RFID`='$userid'"; 
    $checkQuery = mysqli_query($conn, $checkSql);
    $count = mysqli_num_rows($checkQuery); 

    if($count==0){

        die("Error: Invalid RFID (must be logged)<br> <a href=\"log.php\">Back</a>");
    }
    //check for unique RFID and Username
    $checkSql = "SELECT * FROM users WHERE `UserID`='$userid'"; 
    $checkQuery = mysqli_query($conn, $checkSql);
    $count = mysqli_num_rows($checkQuery); 

    if($count>0){

        die("Error: There already exists a user with ID " . $userid ."<br> <a href=\"log.php\">Back</a>");
    }

    $checkSql = "SELECT * FROM users WHERE `Username`='$username'"; 
    $checkQuery = mysqli_query($conn, $checkSql);
    $count = mysqli_num_rows($checkQuery); 

    $sql = "INSERT INTO users VALUES ('" . $username . "'," . $balance . ",'" .$userid. "')";
    if($count>0){
        //echo "<body onload=\"conf\">";
        //there already is a user with that name. Either it was a mistake or the admin wants to assign this user a new rfid
        //$string = "<script>\n$(document).ready(function(){\nif(confirm(\"User with name $username already eists. Do you want to change his RFID to this RFID?\n (Your Balance input will be ignored)\")){\nvar params = new Array(); \nparams[\"username\"] = $username;\nparams[\"userid\"] = $userid;\npost(\"includes/processUserChange.php\", params);\n}\n});\n</script>";
        $string = "
        <!Doctype html>
        <html>
        <head>
                <meta charset=\"UTF-8\">
               <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                <title>Admin-Schnittstelle</title>
               
                <script type=\"text/JavaScript\" src=\"js/forms.js\"></script> 
        </head>
        <body>
        <script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\"></script>
        <script>
        function conf() {
            if(confirm(\"User with name $username already eists. Do you want to change his RFID to this RFID? (Your Balance input will be ignored)\")){
                    var params = new Array(); 
                    params[\"username\"] = \"$username\";
                    params[\"userid\"] = \"$userid\";
                    params[\"fromLog\"] = true;

                    //now post to processTableChange.php:
                    post(\"includes/processUserChange.php\", params);
                } else {
                    window.location = \"log.php\";
                }
        }
        $(document).ready(function() {
          conf();
        });
        </script>";
        echo $string;
    } elseif ($conn->query($sql)==true){
        date_default_timezone_set('Europe/Berlin');
        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO adminaction VALUES ('" . $_SESSION['username'] . "','" .$username. "','CREATE','" . $date . "','Users',null,'". $balance. "')";
        $conn->query($sql);

        //since user was created with RFID: delete it from RFID log
        $sql = "DELETE FROM log where RFID='". $userid ."'";
        $conn->query($sql);

        //echo $sql;
        header("Location: index.php");
     }  else {
        echo "Error: No entries were added!"  ; 
     }
     $conn->close();
     ?>
     <?php endif; ?>
</body>
</html>   