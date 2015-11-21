<?php
// MYSQL credentials
$servername = "localhost";
$username = "benjamin";
$password = "123456aA";
$dbname = "Eventkalender";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
// Check connection
if($mysqli->connect_error) {
	die("Connection failed: " . $mysqli->connect_error);
}

// Check if necessary fields are set and save data from form
if(isset($_POST['name'])) {
	$name = $_POST['name'];

	// SQL-Query
		$sql = "INSERT INTO genre (name) VALUES ('$name')";

	// Try the query
	if($mysqli->query($sql) === TRUE) {
		header("Location: admin.php");
		die();
	} else {
		file_put_contents('genrefail.txt', 'Query for genre failed.');
	}
}

header("Location: newGenre.php?error=true");
die();
?>