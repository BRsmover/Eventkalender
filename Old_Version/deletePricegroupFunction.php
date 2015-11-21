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


	// Get entries where $name is used in event
	$check = "select * from veranstaltung_hat_preisgruppe where veranstaltung_hat_preisgruppe.fk_preisgruppe_id='3';='$name'";

	// SQL-Query
	$sql = "DELETE FROM preisgruppe WHERE name='$name'";

	// Try the query
	if($mysqli->query($sql) === TRUE) {
		header("Location: admin.php");
		die();
	}
}

header("Location: deletePricegroup.php?error=true");
die();
?>