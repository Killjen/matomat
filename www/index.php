<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
#phpinfo();
$id=$_POST['id'];
$type=$_POST['type'];
#$data = $data;
#echo "Your Data is: $data";

$user="php";
$password="matomat94";
$database="matomat";
$link = mysql_connect('localhost', $user, $password);

if (!$link) {
    die('Verbindung schlug fehl: ' . mysql_error());
}
#echo "Erfolgreich verbunden\n";
@mysql_select_db($database) or die( "Unable to select database");
#echo "test";

if ($type == 'check') {
    $query= "SELECT EXISTS(SELECT * FROM user WHERE id = $id)";
    $retval = mysql_query( $query, $link );
    if(! $retval )
    {
      die('Could not get data: ' . mysql_error());
    }
    $exists = 0;
    while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
    {

        $vals = array_values($row);
        if ($vals[0] == 1) {
            $exists = 1;
        }
    } 
    
    #if there is no entry create a new one with no money
    if (!$exists) {
        $query = "INSERT INTO user VALUES ($id, 0)";
        $retval = mysql_query( $query, $link );
        if(! $retval )
        {
          die('Could not enter data: ' . mysql_error());
        }
    } else {
        #else: return guthaben
        $query = "SELECT * FROM user WHERE id = $id";
        $retval = mysql_query( $query, $link );
        if(! $retval )
        {
          die('Could not get data: ' . mysql_error());
        }
        while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
        {

            $vals = array_values($row);
            echo $vals[1];
        } 
    }

    #echo "Fetched data successfully\n";
}
mysql_close($link);
?>