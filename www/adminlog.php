<?php
#set_include_path('~/Documents/robotikfp/matomat/www');
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
ini_set('display_errors', 1);
error_reporting(E_ALL); 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin-Schnittstelle</title>
    <link rel="stylesheet" href="stylesheet.css">
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 

    </head>
    <body>
        <?php if (login_check($mysqli) == true) : ?>

        <div id="content">  
            <div id="navigation">
                <h1>MATOMAT</h1>
                <hr \>
                <p><i>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</i></p>
                <ul>
                  <li><a href="index.php">Users</a></li>
                  <li><a href="stock.php">Stock</a></li>
                  <li><a href="log.php">Unknown RFID log</a></li>
                  <li><a href="transactions.php">Transactions</a></li>
                  <li><a href="adminlog.php">Admin log</a></li>
                </ul>
                <hr \>
                <ul>
                    <li><a href="register.php">Register new Admin</a></li>
                    <li><p><a href="includes/logout.php">logout</a></p></li>
              </ul>
            </div>
   
    
 <!-- ####################### User Table ###### -->      
          <h2>Admin Log</h2>
                  <table id="AdminLog">
                            <tr>
                                <th>AdminName</th>
                                <th>Target</th>
                                <th>Action</th>
                                <th>Time</th>
                                <th>Column</th>
                                <th>OldValue</th>
                                <th>NewValue</th>
                                
                            </tr>                   
        <?php
                    $conn = $mMysqli;
                   //catch failed connections
                    if($conn->connect_error){
                        die("Couldn't connect to db: " . $conn->connect_error);}
                

            $log_adminname = filter_input(INPUT_POST, 'log_adminname', FILTER_SANITIZE_STRING);
            $log_target = filter_input(INPUT_POST, 'log_target', FILTER_SANITIZE_STRING);
            $log_time = filter_input(INPUT_POST, 'log_time', FILTER_SANITIZE_STRING);
            $log_col = filter_input(INPUT_POST, 'log_col', FILTER_SANITIZE_STRING);
            $log_action = filter_input(INPUT_POST, 'log_action', FILTER_SANITIZE_STRING);

            if (empty($log_adminname)) {
                $log_adminname = "";
            }
            $sql = "SELECT * FROM adminaction WHERE AdminName LIKE \"$log_adminname%\"";
            
            if (!empty($log_target)) {
                //since an article target can look like "id name"
                $sql .= " AND Target LIKE \"%$log_target%\"";
            }
            if (!empty($log_time)) {
                $sql .= " AND Time LIKE \"$log_time%\"";
            }
            if (!empty($log_col)) {
                //since a Column can look like "Table Column"
                $sql .= " AND Col LIKE \"%$log_col%\"";
            }
            if (!empty($log_action)) {
                //also accept lowercase:
                $log_action = strtoupper($log_action);
                $sql .= " AND Action = \"$log_action\"";
            }
            $sql .= " ORDER BY Time DESC LIMIT 25"; //limit since we will get many rows in the log
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        //output data in a table
                        while($row = $result->fetch_row()){
                            echo "<tr>";
                            for($x=0; $x < count($row); $x++){
                                echo "<td>" . $row[$x] . "</td>";
                                }
                            echo "</tr>";
                        }
                    }

                    $conn->close();
                    ?>  
                    </table>
</div>
<div id="search">
 <!-- ########################### search stuff#####-->
        <form action="adminlog.php" method="post" name="search_form">                      
                Filter Actions:<br>
        Adminname<input type="text" name="log_adminname" id="log_adminname"/><br>
        Target<input type="text" name="log_target" id="log_target"/><br>
        Action<input type="text" name="log_action" id="log_action"/><br>
        Time<input type="text" name="log_time" id="log_time"/><br>
        Column<input type="text" name="log_col" id="log_col"/><br>

                <input type="submit" 
                       value="Search"/> 
                </form>

        <hr  \>
            

</div>

            <div id="footer">
               <p>Robotik Fortgeschrittenenpraktikum | Mat-o-Mat | WS 2014/15 | von Jakob Schmid und Amos Treiber</p>
                <p>Login-System by <a href="www.wikihow.com">WikiHow</a>: <a href="http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL">Secure Login Script</a></p>
            </div>

          
    
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please login:
            </p>
            <form action="includes/process_login.php" method="post" name="login_form">                      
            Email: <input type="text" name="email" />
            Password: <input type="password" 
                             name="password" 
                             id="password" 
                             onkeypress="if(event.keyCode==13) {formhash(this.form, this.form.password);}"/>
            <input type="button" 
                   value="Login" 
                   onclick="formhash(this.form, this.form.password);" /> 
            </form>
            
        <?php endif; ?>
    </div>
    </body>
</html>
