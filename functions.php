<?php

// Get current site
function getSite() {
	$site = "home";
	if(isset($_GET['site'])) {
		$site = $_GET['site'];
	}
	return $site;
}

// Parse site
function parseSite($site, $data) {
	$loader = new Twig_Loader_Filesystem('html');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate($site . ".html");
	return $template->render($data);
}

// Get all pricgroups
function getPriceGroups() {
	$data = array();
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from preisgruppe");
    while($row = $result->fetch_assoc()) {
		$data[] = $row;
    }
    return $data;
}

// Delete pricegroup
function deletePriceGroup() {
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	if($connection->query("DELETE from preisgruppe where ID='" . $id . "';") === TRUE) {
		return 'Preiskategorie wurde erfolgreich gelöscht!';
	} else {
		return 'Preiskategorie konnte nicht gelöscht werden!';
	}
}

// Insert pricgroup
function createPriceGroup() {
	$name = $_POST['name'];
	$price = $_POST['price'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	if($connection->query("INSERT INTO preisgruppe (name, preis) VALUES ('$name', '$price')")) {
		return 'Preisgruppe wurde erfolgreich hinzugefügt!';
	} else {
		return 'Preisgruppe konnte nicht hinzugefügt werden!';
	}
}

// Create user
function createUser() {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		file_put_contents('login.txt', 'user: ' . $username . ' password: ' . $password);

		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);

		if($uppercase && $lowercase && $number && strlen($password) > 8) {
			$newpw = password_hash($password, PASSWORD_DEFAULT);
			$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);

			if($connection->query("INSERT INTO benutzer (benutzername, passwort) VALUES ('$username', '$newpw')")) {
				return 'Benutzer wurde erfolgreich erstellt!';
			} else {
				return 'Benutzer konnte nicht erstellt werden!';
			}
		} else {
			return 'Das Passwort ist nicht komplex genug!';
		}
	} else {
		return 'Bitte füllen Sie alle Felder aus!';
	}
}

// Create event
function createEvent() {
	if(isset($_POST['name']) && isset($_POST['beschreibung']) && isset($_POST['termin']) && isset($_POST['dauer']) && isset($_POST['bild']) && isset($_POST['bildbeschreibung']) && isset($_POST['link']) && isset($_POST['linkbeschreibung']) && isset($_POST['genre_id'])) {
		$name = $_POST['name'];

		// Check if 'besetzung' is set, if not make it an empty string
		if(isset($_POST['besetzung'])) {
			$besetzung = $_POST['besetzung'];
		} else {
			$besetzung = '';
		}

		$beschreibung = $_POST['beschreibung'];
		$termin = $_POST['termin'];
		$dauer = $_POST['dauer'];

		// Getting path of file
		$bild = $_POST['bild'];
		if(is_uploaded_file($bild)) {
			$handle = fopen($_FILES[$bild]["tmp_name"], 'r');
		} else {
			return 'Failed to upload file!';
		}

		$bildbeschreibung = $_POST['bildbeschreibung'];
		$link = $_POST['link'];
		$linkbeschreibung = $_POST['linkbeschreibung'];
		$genre_id = $_POST['genre_id'];

		// SQL-Query
		$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
		if($connection->query("INSERT INTO veranstaltung (name, besetzung, beschreibung, termin, dauer, bild, bildbeschreibung, link, linkbeschreibung, fk_genre_id) VALUES ('$name', '$besetzung', '$beschreibung', '$termin', $dauer, '$bild', '$bildbeschreibung', '$link', '$linkbeschreibung', $genre_id)")) {
			return 'Veranstaltung wurde erfolgreich erstellt!';
		} else {
// 			return 'Veranstaltung konnte nicht erstellt werden!';
			file_put_contents('fail.txt', 'Query failed!');
		}
	}
}

function isUserLoggedIn() {
	return true;
}

function hasUserLoginCredentials() {
	return true;
}

function login() {
	return false;
}

?>
