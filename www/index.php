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
	   <!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">-->
       <link rel="stylesheet" href="stylesheet.css">

        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 

    </head>
    <body>
        <?php if (login_check($mysqli) == true) : ?>

<div id="display">         
<!-- ########################### navigation#####-->
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
<div id="content">  
		    <h2>Users</h2>
                    <table id="Users">
                            <tr>
                                <th>Username</th>
                                <th>Balance (€)</th>
			    	<th>UserID</th>
                            </tr>
                    <?php
                    $conn = $mMysqli;
 		            $users_username = filter_input(INPUT_POST, 'users_username', FILTER_SANITIZE_STRING);
                    if($conn->connect_error){
                            die("Couldn't connect to db: " . $conn->connect_error);
                    }
                    if (empty($users_username)){
                        $sql = "SELECT * from users";
                    } else {
                        $sql = "SELECT * from users WHERE Username LIKE \"$users_username%\"";
                    }
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        //output data in a table
                        //is edible, so we count which row is displayed:
                        $i = 0;
                        while($row = $result->fetch_row()){
                            echo "<tr>";
                            for($x=0; $x < count($row); $x++){                             
                                echo "<td><input type='text' name='$i $x' onkeypress=\"if(event.keyCode==13) {changeUser($i,'$row[0]',$x);}\" value='". $row[$x] . "'></td>";
                                }
			    echo "<td>X</td>";
                            echo "</tr>";
                            $i = $i + 1;
                        }
                        
                    }
                    $conn->close();
                    ?>
                    </table>
</div>
	<div id="search">
                <form action="index.php" method="post" name="search_form">                      
                Search User table: <input type="text" name="users_username" id="users_username"/>
                <input type="submit" 
                       value="Search"/> 
                </form>
		<hr  \>
		<form action="addMoney.php" method="post">
			Name: <input type="text" name="username" id="addusername" value="Click on a row!"><br>
			Add(€) : <input type="text" name="amount"><br>
			<input type="submit" value="Charge">
		</form>
	</div>
	<form action="delUser.php" method="post" style="hidden" id="deluserform">
	 <input type="hidden" name="username" id="delusername"><br>
	</form>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>-->
	<script>
		$(document).ready(function(){
		$("#Users tr td").click(function(){
		var parent = $(this).parent()[0];
			if($(this).is(":last-child")){
				var user =  parent.cells[0].firstChild.value; 
				if(confirm("Do you really want to delete the user \n" + user + "?")){
					$("#delusername").val(user);
					$("#deluserform").submit();
				}
			}
			$("#addusername").val(parent.cells[0].firstChild.value);	
		});
        $("#Users tr td input").blur(function() {
            //restore original value if input field is out of focus:
            var parent = $(this).parent()[0];
            $(this).val(parent.firstChild.defaultValue);
            
        });
	});

	</script>

		
            <div id="footer">
               <p>Robotik Fortgeschrittenenpraktikum | Mat-o-Mat | WS 2014/15 | von Jakob Schmid und Amos Treiber</p>
               <p>Login-System by <a href="www.wikihow.com">WikiHow</a>: <a href="http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL">Secure Login Script</a></p>
            </div>
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
