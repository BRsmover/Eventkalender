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
if(isset($_POST['name']) && isset($_POST['preis'])) {
	$name = $_POST['name'];
	$preis = $_POST['preis'];

	// SQL-Query
		$sql = "INSERT INTO preisgruppe (name, preis) VALUES ('$name', '$preis')";

	// Try the query
	if($mysqli->query($sql) === TRUE) {
		header("Location: admin.php");
		die();
	} else {
		file_put_contents('preisgruppefail.txt', 'Query for pricegroup failed.');
	}
}

header("Location: newPricegroup.php?error=true");
die();
?>