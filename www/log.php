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
    
          <h2>Unknown RFID Log</h2>
                  <table id="Log">
                            <tr>
                                <th>RFID</th>
                                <th>Time</th>
                                
                            </tr>                   
        <?php
                    $conn = $mMysqli;
                   //catch failed connections
                    if($conn->connect_error){
                        die("Couldn't connect to db: " . $conn->connect_error);}
                


            


            $sql = "SELECT * FROM log ORDER BY Time DESC LIMIT 50"; //limit only for security, since new rfid cards are deleted from the unknown RFID log once activated
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        //output data in a table
                        while($row = $result->fetch_row()){
                            echo "<tr>";
                            echo "<td>". $row[0] ."</td>";
                            echo "<td>". $row[1] ."</td>";
                            echo "</tr>";
                        }
                    }

                    $conn->close();
                    ?>  
                    </table>
</div>
<div id="search">

        <form action="addUser.php" method="post">
            Id: <input type="text" name="userid" id="adduserid" value="Click on a row!"><br>
            Username: <input type="text" name="username"><br>
            Balance: <input type="text" name="balance" value="0"><br>
            <input type="submit" value="Add User">
        </form>
</div>
<form action="delArticle.php" method="post" style="hidden" id="delarticleform">
    <input type="hidden" name="articleid" id="delarticleid"><br>
</form>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("#Log tr td").click(function(){
            var parent = $(this).parent()[0];
            //window.alert(parent.cells[0].firstChild.value)
            $("#adduserid").val(parent.cells[0].innerHTML);    
        });
    });

    </script>
        
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
