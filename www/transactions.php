<!--experimental file
    ONLY FOR CSS!
-->
<?php
#set_include_path('~/Documents/robotikfp/matomat/www');
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
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
	 	  <h2>Transactions</h2>
                  <table id="Header">
                            <tr>
                                <th>Username </th>
                                <th>ArticleID </th>
                                <th>ArticleName </th>
                                <th>Payed </th>
                                <th>Time</th>
                            </tr>
                    <?php
                    $conn = $mMysqli;
                    $transactions_username = filter_input(INPUT_POST, 'transactions_username', FILTER_SANITIZE_STRING);
                    $transactions_time = filter_input(INPUT_POST, 'transactions_time', FILTER_SANITIZE_STRING);
                    //catch failed connections
                    if($conn->connect_error){
                        die("Couldn't connect to db: " . $conn->connect_error);
			}
	            if (empty($transactions_username)){
                       	$sql = "SELECT * from transactions";
		    	if(!empty($transactions_time)){
				$sql .= " WHERE Time LIKE \"$transactions_time%\"";  
			}
                    } else {
                        $sql = "SELECT * from transactions WHERE Username LIKE \"$transactions_username%\"";
		    	if(!empty($transactions_time)){
				$sql .= " AND Time LIKE \"$transactions_time%\"";  
			}
                    }
                    $sql .= " ORDER BY Time DESC LIMIT 25";
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
 <!-- ########################### search stuff#####-->
           <div id="search">
		<form action="transactions.php" method="post" name="search_form">                      
                Filter Transactions:<br>
		Username<input type="text" name="transactions_username" id="transactions_username"/><br>
		Date<input type="text" name="transactions_time" id="transactions_time"/><br>
                <input type="submit" 
                       value="Search"/> 
                </form>

		<hr  \>

</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		
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
    </body>
</html>
