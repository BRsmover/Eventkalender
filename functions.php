<?php

// Get current site
function getSite() {
	$site = "home";
	if(isset($_GET['site'])) {
		$site = $_GET['site'];
	}
	return $site;
}

// Check if user is logged in
function checkLogin() {
	if(!isUserLoggedIn()) {
		echo(parseSite("error", array("error" =>  "Sie sind nicht angemeldet")));
		return false;
	}
	else {
		return true;
	}
}
// Parse site
function parseSite($site, $data) {
	$loader = new Twig_Loader_Filesystem('html');
	$twig = new Twig_Environment($loader, array('debug' => true));
	$twig->addExtension(new Twig_Extension_Debug());
	$template = $twig->loadTemplate($site . ".html");
	return $template->render($data);
}

// Get all Events
function getEvents() {
	$data = array();
	// Get current time
	$date = gmdate('Y-m-d h:i:s \G\M\T');
	// Open db connection
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	// Select entries from db which are in the future
	$result = $connection->query("SELECT * from veranstaltung WHERE termin > '$date'");
	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
// 	file_put_contents('events.txt', var_dump($data));
	return $data;
}

// Get last 5 passed events
function fillArchive() {
	// Get the current time
	$date = gmdate('Y-m-d h:i:s \G\M\T');
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	// Get the five last events from db
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
	// Select pricegroups
	$result = $connection->query("SELECT * from preisgruppe");
	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	return $data;
}

// Get all events including their data
function getCompleteEvents() {
	$data = array();
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	// Get events in the future
	$events = getEvents();
	// For each one of these events
	foreach($events as $event) {
		$pricegroups = array();
		// Get its id
		$eId = $event['id'];
		// Get its genre id
		$genreid = $event['fk_genre_id'];
		// Select the pricgroups of it in veranstaltung_hat_preisgruppe table
		$result = $connection->query("SELECT fk_preisgruppe_id FROM veranstaltung_hat_preisgruppe WHERE fk_veranstaltung_id = '$eId'");
		// Save all foreign keys from query in array
		while($row = $result->fetch_assoc()) {
			$pricegroups[] = $row;
		}
		// Get for each foreign key the pricegroup
		$finalpricegroups = array();
		foreach($pricegroups as $pricegroup) {
			$id = $pricegroup['fk_preisgruppe_id'];
			$pricegrouplines = $connection->query("SELECT * FROM preisgruppe WHERE ID = '$id'");
			while($row = $pricegrouplines->fetch_assoc()) {
				$finalpricegroups[] = $row;
			}
		}
		// Get the genre from the id
		$genreresult = $connection->query("SELECT * FROM genre WHERE id = '$genreid'");
		$genre = $genreresult->fetch_assoc();
		// Save everything into one big array
		$data[] = array("event" => $event, "pricegroups" => $finalpricegroups, "genre" => $genre);
	}
	return $data;
}

// Delete pricegroup
function deletePriceGroup() {
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	// Check if the pricegroup is used in veranstaltung_hat_preisgruppe
	$result = $connection->query("SELECT fk_preisgruppe_id FROM veranstaltung_hat_preisgruppe WHERE fk_preisgruppe_id = '$id'");
	$usage = $result->num_rows;
	file_put_contents('usage.txt', $usage);
	// If it isn't used delete it
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
	// Insert the pricegroup
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
	// Insert the genre
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
	// Get all genres
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
	// If not used delete it
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

		// Check if it has at least one uppercase letter, one lowercase letter and a number
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);

		// If it has this and it's longer than 8
		if($uppercase && $lowercase && $number && strlen($password) > 8) {
			// Create password hash
			$newpw = password_hash($password, PASSWORD_DEFAULT);
			$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);

			// Insert the user into db
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

		// Define which filetypes are allowed
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

// Check if user is logged in
function isUserLoggedIn() {
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
		return true;
 	} else {
 	return false;
 	}
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

// Get event that is to be edited
function getEditEvent() {
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from veranstaltung where ID='$id'");
	if($result) {
		$_SESSION['veranstaltung_id'] = $id;
		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		return $data;
	} else {
// 		file_put_contents('no-event-chosen.txt', 'fail');
		// Go to error page
		header("Location: index.php?site=error");
		die();
	}
}

function editEvent() {
// var_dump($event);
$veranstaltung_id = $_SESSION['veranstaltung_id'];
// Get fields and UPDATE into database
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

			// SQL-Query to update event
			$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
			if($connection->query("UPDATE veranstaltung SET name='$name', besetzung='$besetzung', beschreibung='$beschreibung', termin='$termin', dauer='$dauer', bild='$uploadfile', bildbeschreibung='$bildbeschreibung', link='$link', linkbeschreibung='$linkbeschreibung', fk_genre_id='$genre_id' WHERE id='$veranstaltung_id'")) {

				// SQL-Query to update entries in "veranstaltung_hat_preisgruppe"
				// Get array of checkboxes with ID's from pricegroups
				$pricegroups = $_POST['pricegroup'];
				foreach($pricegroups as $pricegroup) {
					if(isset($pricegroup)) {
						$update = $connection->query("UPDATE veranstaltung_hat_preisgruppe SET fk_preisgruppe_id='$pricegroup', fk_veranstaltung_id='$veranstaltung_id' WHERE fk_veranstaltung_id='$veranstaltung_id'");
						if($update == false) {
							file_put_contents('connection-creation-fail.txt', "connection wasn't created... -- veranstaltungId: " . $veranstaltung_id . " pricegroupId: " . $pricegroup . " session-variable: " . $_SESSION['veranstaltung_id']);
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

				return 'Veranstaltung wurde erfolgreich angepasst!';
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
?>
