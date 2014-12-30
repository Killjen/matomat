<?php
#set_include_path('~/Documents/robotikfp/matomat/www');
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin-Schnittstelle</title>
        <link rel="stylesheet" href="stylesheet.css" />
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    <body>
        <?php if (login_check($mysqli) == true) : ?>
            <p>Welcome <?php echo htmlentities($_SESSION['username']); ?>!</p>
            <p><a href="includes/logout.php">logout</a></p>


<!-- #####  transaction table ###########-->
    <h2>Transactions</h2>
    <table id="Transactions">
            <tr>
                <th>Username</th>
                <th>TransactionID</th>
                <th>Time</th>
            </tr>
    <?php
    $servername = "localhost";
    $username   = "matomat";
    $dbpassword = "matomat94";
    $dbname     = "matomat";
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);
    //catch failed connections
    if($conn->connect_error){
        die("Couldn't connect to db: " . $conn->connect_error);}
    $sql = "SELECT * from transactions";
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
        //output data in a table
        while($row = $result->fetch_row()){
            echo "<tr> ";
            for($x=0; $x < count($row); $x++){
                echo "<td> " . $row[$x] . "</td>";
                }
            echo "</tr>";
        }
    }

	
    ?>  
    </table>
<!-- ####################### User Table ###### -->      
     <h2 id="header">Users</h2>
    <table id="Users">
            <tr>
                <th>Username</th>
                <th>Balance</th>
            </tr>
	<?php
	$sql = "SELECT * from users";
	$result = $conn->query($sql);
	if ($result->num_rows > 0){
        //output data in a table
        while($row = $result->fetch_row()){
            echo "<tr onclick='setName(this)'> ";
            for($x=0; $x < count($row); $x++){
                echo "<td> " . $row[$x] . "</td>";
                }
            echo "</tr>";
        }
    }
	$conn->close();
	?>
	</table>

	<form action="addMoney.php" method="post">
	Name: <input type="text" name="username" id="username" value="Click on a column!"><br>
	Add(€) : <input type="text" name="amount"><br>
	<input type="submit">
	</form>

	<script>
	function setName(row) {
		document.getElementById("username").value=row.cells[0].innerHTML;
	}

	window.onload = function(){
		var table = document.getElementById("Users");
	};
	</script>
	

<!--security footer -->
        <?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please login:
            </p>
            <form action="includes/process_login.php" method="post" name="login_form">                      
            Email: <input type="text" name="email" />
            Password: <input type="password" 
                             name="password" 
                             id="password"/>
            <input type="button" 
                   value="Login" 
                   onclick="formhash(this.form, this.form.password);" /> 
            </form>
        <?php endif; ?>
    </body>
</html>
