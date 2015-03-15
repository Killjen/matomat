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
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 

    </head>
    <body>
        <?php if (login_check($mysqli) == true) : ?>

        <div id="content">  
	<div class="container-fluid">
<!-- ########################### navigation#####-->
	<div class="row">
	<div class="col-sm-3">
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
	</div>
	
 <!-- ####################### User Table ###### -->      
		  <div class= "col-sm-6">
	 	  <h2>Stock</h2>
                  <table id="Stock">
                            <tr>
                                <th>ArticleID</th>
                                <th>ArticleName</th>
                                <th>Quantity</th>
                                <th>Price</th>
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
	            
		    $sql = "SELECT * from stock";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        //output data in a table
                        $i = 0;
                        while($row = $result->fetch_row()){
                            echo "<tr>";
                            echo "<td> ". $row[0] ."</td>";
                            for($x=1; $x < count($row); $x++){
                                echo "<td><input type='text' name='$i $x' onkeypress=\"if(event.keyCode==13) {changeStock($i,'$row[0]',$x);}\" value='". $row[$x] . "'></td>";
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

<div class="col-sm-3">
    <!--TODO-->
        <form action="addArticle.php" method="post">
            <br>
            Name: <input type="text" name="articlename"><br>
            Quantity: <input type="text" name="quantity"><br>
            Price: <input type="text" name="price"><br>
            <input type="submit" value="Add New Article">
        </form>
        <hr  \>
        <form action="addQuantity.php" method="post">
            Id: <input type="text" name="articleid" id="addarticleid" value="Click on a row!"><br>
            Add Bottles : <input type="text" name="amount"><br>
            <input type="hidden" name="articlename" id="addarticlename"><br>
            <input type="submit" value="Add Quantity">
        </form>
    </div>
</div>
<form action="delArticle.php" method="post" style="hidden" id="delarticleform">
    <input type="hidden" name="articleid" id="delarticleid"><br>
    <input type="hidden" name="articlename" id="delarticlename"><br>
</form>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $("#Stock tr td").click(function(){
            var parent = $(this).parent()[0];
            if($(this).is(":last-child")){
                var id =  parent.cells[0].innerHTML;
                var name = parent.cells[1].firstChild.value;
                if(confirm("Do you really want to delete the Article " + name + " with id \n" + id + "?")){
                    $("#delarticleid").val(id);
                    $("#delarticlename").val(name);
                    $("#delarticleform").submit();
                }
            }
            //window.alert(parent.cells[0].firstChild.value)
            $("#addarticleid").val(parent.cells[0].innerHTML);
            $("#addarticlename").val(parent.cells[1].firstChild.value); 
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
    </div>
    </body>
</html>
