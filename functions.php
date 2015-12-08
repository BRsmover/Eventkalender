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

// Get all Events
function getEvents() {
	$data = array();
	$date = gmdate('Y-m-d h:i:s \G\M\T');
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from veranstaltung WHERE termin > '$date'");
	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
// 	file_put_contents('events.txt', var_dump($data));
	return $data;
}

// Get last 5 passed events
function fillArchive() {
	$date = gmdate('Y-m-d h:i:s \G\M\T');
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from veranstaltung WHERE termin < '$date' LIMIT 0,5");
	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
// 	file_put_contents('archiv.txt', var_dump($data));
	return $data;
}

// Get all pricegroups
function getPriceGroups() {
	$data = array();
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from preisgruppe");
	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	return $data;
}

// Get the pricegroups of an event
function getAssociatedPriceGroups() {
	$data = array();
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$events = getEvents();
	var_dump($events);
	foreach($events as $event) {
		$eId = $event['id'];
		echo $eId;
		$result = $connection->query("SELECT fk_preisgruppe_id FROM veranstaltung_hat_preisgruppe WHERE fk_veranstaltung_id = '$eId'");
		// Save all foreign keys from query in array
		while($row = $result->fetch_assoc()) {
			$data[] = $row;
			echo 'hallo';
			var_dump($data);
		}
		var_dump($data);
		// Get for each foreign key the pricegroup
		foreach($data as $dataEntry) {
			$id = $dataEntry['fk_preisgruppe_id'];
			$pricegroups = $connection->query("SELECT * FROM preisgruppe WHERE ID = '$id'");
			while($row = $pricegroups->fetch_assoc()) {
				$data[] = $row;
			}
			return $data;
		}
	}
}

// Delete pricegroup
function deletePriceGroup() {
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT fk_preisgruppe_id FROM veranstaltung_hat_preisgruppe WHERE fk_preisgruppe_id = '$id'");
	$usage = $result->num_rows;
	file_put_contents('usage.txt', $usage);
	if($usage == 0) {
		if($connection->query("DELETE from preisgruppe where ID='$id'") === TRUE) {
			return 'Preiskategorie wurde erfolgreich gelöscht!';
		} else {
			file_put_contents('query.txt', 'fail');
			// Go to error page
			header("Location: index.php?site=error");
			die();
		}
	} else {
		file_put_contents('used.txt', 'fail');
		// Go to error page
		header("Location: index.php?site=error");
		die();
	}
}

// Insert pricegroup
function createPriceGroup() {
	$name = $_POST['name'];
	$price = $_POST['price'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	if($connection->query("INSERT INTO preisgruppe (name, preis) VALUES ('$name', '$price')")) {
		return 'Preisgruppe wurde erfolgreich hinzugefügt!';
	} else {
		// Go to error page
		header("Location: index.php?site=error");
		die();
	}
}

// Insert genre
function createGenre() {
	$name = $_POST['name'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	if($connection->query("INSERT INTO genre (name) VALUES ('$name')")) {
		return 'Genre wurde erfolgreich hinzugefügt!';
	} else {
		// Go to error page
		header("Location: index.php?site=error");
		die();
	}
}

// Get all genres
function getGenres() {
	$data = array();
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from genre");
	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	return $data;
}

// Delete genre
function deleteGenre() {
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	// Check if genre is used
	$result = $connection->query("SELECT name FROM veranstaltung WHERE fk_genre_id = '$id'");
	$usage = $result->num_rows;
	// If not used - delete it
	if($usage == 0) {
		if($connection->query("DELETE from genre where id='" . $id . "';") === TRUE) {
			return 'Genre wurde erfolgreich gelöscht!';
		} else {
			// Go to error page
			header("Location: index.php?site=error");
			die();
		}
	} else {
		// Go to error page
		header("Location: index.php?site=error");
		die();
	}
}

// Create user
function createUser() {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
// 		file_put_contents('login.txt', 'user: ' . $username . ' password: ' . $password);

		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);

		if($uppercase && $lowercase && $number && strlen($password) > 8) {
			$newpw = password_hash($password, PASSWORD_DEFAULT);
			$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);

			if($connection->query("INSERT INTO benutzer (benutzername, passwort) VALUES ('$username', '$newpw')")) {
				return 'Benutzer wurde erfolgreich erstellt!';
			} else {
				// Go to error page
				header("Location: index.php?site=error");
				die();
			}
		} else {
			// Go to error page
			header("Location: index.php?site=error");
			die();
		}
	} else {
		// Go to error page
		header("Location: index.php?site=error");
		die();
	}
}

// Create event
function createEvent() {
	// Check if necessary fields are set
	if(isset($_POST['name']) && isset($_POST['beschreibung']) && isset($_POST['termin']) && isset($_POST['dauer']) && isset($_FILES) && isset($_POST['bildbeschreibung']) && isset($_POST['link']) && isset($_POST['linkbeschreibung']) && isset($_POST['selectid'])) {
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
		
		$allowed = array('image/jpeg', 'image/png', 'image/jpg');
		$filetype = $_FILES['bild']['type'];
		if (in_array($filetype, $allowed)) {
			// Getting path of file
			$uploaddir = 'files/';
			$uploadfile = $uploaddir . basename($_FILES['bild']['name']);
			// Move file to files/
			$moveFile = move_uploaded_file($_FILES['bild']['tmp_name'], $uploadfile);
			if ($moveFile) {
				$bildbeschreibung = $_POST['bildbeschreibung'];
				$link = $_POST['link'];
				$linkbeschreibung = $_POST['linkbeschreibung'];
				$genre_id = $_POST['selectid'];
// 				file_put_contents('genre.txt', $genre_id);

				// SQL-Query to insert event
				$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
				if($connection->query("INSERT INTO veranstaltung (name, besetzung, beschreibung, termin, dauer, bild, bildbeschreibung, link, linkbeschreibung, fk_genre_id) VALUES ('$name', '$besetzung', '$beschreibung', '$termin', $dauer, '$uploadfile', '$bildbeschreibung', '$link', '$linkbeschreibung', $genre_id)")) {

					// SQL-Query to get last id
					$resultId = $connection->query("SELECT id FROM veranstaltung WHERE id=(SELECT max(id) FROM veranstaltung)");
// 					file_put_contents('id.txt', getId($resultId));

					// SQL-Query to insert entries in "veranstaltung_hat_preisgruppe"
// 					$pricegroups = getPriceGroups();
					// Get array of checkboxes with ID's from pricegroups
					$pricegroups = $_POST['pricegroup'];
// 					var_dump($pricegroups);
					$veranstaltungId = getId($resultId);
					foreach($pricegroups as $pricegroup) {
						if(isset($pricegroup)) {
							$insert = $connection->query("INSERT INTO veranstaltung_hat_preisgruppe (fk_preisgruppe_id, fk_veranstaltung_id) VALUES ('$pricegroup', '$veranstaltungId')");
							if($insert == false) {
								file_put_contents('connection-creation-fail.txt', "connection wasn't created... -- veranstaltungId: " . $veranstaltungId . " pricegroupId: " . $pricegroup);
								// Go to error page
								header("Location: index.php?site=error");
								die();
							}
						} else {
							// Go to error page
							header("Location: index.php?site=error");
							die();
						}
					}

					return 'Veranstaltung wurde erfolgreich erstellt!';
				} else {
					file_put_contents('event-creation-fail.txt', "Event wasn't created...");
					// Go to error page
					header("Location: index.php?site=error");
					die();
				}
			} else {
				// Go to error page
				file_put_contents('image-upload-fail.txt', "uploaddir: " . $uploaddir . "\nuploadfile: " . $uploadfile . "\nMoveFile: " . $moveFile . "\ntmp: " . $_FILES['bild']['tmp_name']);
				header("Location: index.php?site=error");
				die();
			}
		} else {
			// Go to error page
			file_put_contents('image-not-valid.txt', "Image ain't valid!");
			header("Location: index.php?site=error");
			die();
		}
	}
}

// Upload image
function uploadImage($bild) {
// 	file_put_contents('fileupload.txt', 'file upload');
	// Check if filetype is an image
	
}

// Check if user is logged in
function isUserLoggedIn() {
// if ($_SESSION['loggedin'] == true) {
		return true;
//  	} else {
//  	return false;
//  	}
}

function hasUserLoginCredentials() {
	return true;
}

// Login
function login() {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];

		// Save username in session
		$_SESSION['username'] = $username;

		if(verify($username, $password)) {
			$_SESSION['loggedin'] = true;
			header("Location: index.php?site=admin");
			die();
		} else {
			$_SESSION['loggedin'] = false;
			header("Location: index.php?site=error");
			die();
		}
	}
}

// Verify password
function verify($username, $password) {
	// Get hash from db
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$sql = "SELECT passwort FROM benutzer WHERE benutzername = '$username'";
	$result = $connection->query($sql);

	$hash = getHash($result);
	$verification = password_verify($password, $hash);
// 	file_put_contents('verification.txt', 'ergebnis: ' . $verification . ' passwort: ' . $password . ' hash: ' . $hash);
	if ($verification) {
		return true;
	} else {
		return false;
	}
}

// Get string from sql-query
function getHash($result) {
	while($row = mysqli_fetch_assoc($result)) {
		$result = $row["passwort"];
		return $result;
	}
}

// Get string from sql-query
function getId($result) {
	while($row = mysqli_fetch_assoc($result)) {
		$result = $row["id"];
		return $result;
	}
}

// Change password
function changePw() {
	$username = $_SESSION['username'];
	$password = $_POST['password'];
	$newPasswordOne = $_POST['newPasswordOne'];
	$newPasswordTwo = $_POST['newPasswordTwo'];
// 	file_put_contents('passwords.txt', 'user: ' . $username . ' old: ' . $password . ' new1: ' . $newPasswordOne . ' new2: ' . $newPasswordTwo . ' verification: ' . verify($username, $password));

	if(verify($username, $password)) {
		if($newPasswordOne == $newPasswordTwo && $password != $newPasswordOne) {
			$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
			$newPasswordOne = password_hash($newPasswordOne, PASSWORD_DEFAULT);
			if($connection->query("UPDATE benutzer SET passwort = '$newPasswordOne' WHERE benutzername = '$username'")) {
				return 'Das Passwort wurde erfolgreich geändert!';
			} else {
// 			file_put_contents('query-failed.txt', 'fail');
			// Query failed
				header("Location: index.php?site=error");
				die();
			}
		} else {
			// New pw's don't match or are the same as the old one
			header("Location: index.php?site=error");
			die();
		}
	} else {
		// Old pw is incorrect
		header("Location: index.php?site=error");
		die();
	}
}

// Delete event
function deleteEvent() {
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	if($connection->query("DELETE from veranstaltung where ID='$id'") === TRUE) {
		return 'Preiskategorie wurde erfolgreich gelöscht!';
	} else {
		file_put_contents('used.txt', 'fail');
		// Go to error page
		header("Location: index.php?site=error");
		die();
	}
}

// Logout
function logout() {
	session_destroy();
	header("Location: index.php?site=login");
	die();
}
?>
