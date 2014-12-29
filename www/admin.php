<!DOCTYPE html>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
		<title>Admin-Schnittstelle</title>
	<body>
	<h2>Transaction table</h2>
	<table id="Header">
		<tr>
			<th>Username</th>
			<th>UserID</th>
			<th>Time</th>
		</tr>
<?php
$servername	= "localhost";
$username 	= "matomat";
$password	= "matomat94";
$dbname 	= "matomat";
$conn = new mysqli($servername, $username, $password, $dbname);
//catch failed connections
if($conn->connect_error){
	die("Couldn't connect to db: " . $conn->connect_error);}
$sql = "SELECT * from TRANSACTIONS";
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
$conn->close();
?>	
	</table>
</body>
</html>
