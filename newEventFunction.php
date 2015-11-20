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
if(isset($_POST['name']) && isset($_POST['beschreibung']) && isset($_POST['termin']) && isset($_POST['dauer']) && isset($_POST['bild']) && isset($_POST['bildbeschreibung']) && isset($_POST['link']) && isset($_POST['linkbeschreibung']) && isset($_POST['genre_id'])) {
	$name = $_POST['name'];
	if(isset($_POST['besetzung'])) {
		$besetzung = $_POST['besetzung'];
	} else {
		$besetzung = '';
	}
	$beschreibung = $_POST['beschreibung'];
	$termin = $_POST['termin'];
	$dauer = $_POST['dauer'];
	$bild = file_get_contents($_POST['bild']);
	$bildbeschreibung = $_POST['bildbeschreibung'];
	$link = $_POST['link'];
	$linkbeschreibung = $_POST['linkbeschreibung'];
	$genre_id = $_POST['genre_id'];

	$array = [$name, $beschreibung, $besetzung, $termin, $dauer, $bild, $bildbeschreibung, $link, $linkbeschreibung, $genre_id];
	file_put_contents('array.txt', var_dump($array));

	// SQL-Query
		$sql = "INSERT INTO veranstaltung (name, besetzung, beschreibung, termin, dauer, bild, bildbeschreibung, link, linkbeschreibung, fk_genre_id) VALUES ('$name', '$besetzung', '$beschreibung', $termin, $dauer, '$bild', '$bildbeschreibung', '$link', '$linkbeschreibung', $genre_id)";

	// Try the query
	if($mysqli->query($sql) === TRUE) {
		header("Location: login.php");
		die();
	} else {
		file_put_contents('fail.txt', 'Query failed. ' . $bild);
	}
}

header("Location: newEvent.php?error=true");
die();
?>