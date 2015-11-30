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
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from veranstaltung");
    while($row = $result->fetch_assoc()) {
		$data[] = $row;
    }
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

// Delete pricegroup
function deletePriceGroup() {
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	if($connection->query("DELETE from preisgruppe where ID='" . $id . "';") === TRUE) {
		return 'Preiskategorie wurde erfolgreich gelöscht!';
	} else {
		// Go to error page
		header("Location: index.php?site=error");
		die();	}
}

// Insert pricgroup
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
	if(isset($_POST['name']) && isset($_POST['beschreibung']) && isset($_POST['termin']) && isset($_POST['dauer']) && isset($_FILES) && isset($_POST['bildbeschreibung']) && isset($_POST['link']) && isset($_POST['linkbeschreibung']) && isset($_POST['genre_id'])) {
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

		// Check if filetype is an image
		$allowed = array('image/jpeg', 'image/png', 'image/jpg');
		$filetype = $_FILES['bild']['type'];
		if (in_array($filetype, $allowed)) {
			// Getting path of file
			$uploaddir = 'files/';
			$uploadfile = $uploaddir . basename($_FILES['bild']['name']);
			// Move file to files/
			if (move_uploaded_file($_FILES['bild']['tmp_name'], $uploadfile)) {
				echo 'Datei ist valide und wurde erfolgreich hochgeladen.';
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

		$bildbeschreibung = $_POST['bildbeschreibung'];
		$link = $_POST['link'];
		$linkbeschreibung = $_POST['linkbeschreibung'];
		$genre_id = $_POST['genre_id'];

		// SQL-Query to insert event
		$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
		if($connection->query("INSERT INTO veranstaltung (name, besetzung, beschreibung, termin, dauer, bild, bildbeschreibung, link, linkbeschreibung, fk_genre_id) VALUES ('$name', '$besetzung', '$beschreibung', '$termin', $dauer, '$uploadfile', '$bildbeschreibung', '$link', '$linkbeschreibung', $genre_id)")) {
			return 'Veranstaltung wurde erfolgreich erstellt!';
		} else {
			// Go to error page
			header("Location: index.php?site=error");
			die();
		}
	}
}

// Check if user is logged in
function isUserLoggedIn() {
	//if ($_SESSION['loggedin'] == true) {
		return true;
// 	} else {
// 	return false;
// 	}
}

function hasUserLoginCredentials() {
	return true;
}

// Login
function login() {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$username = $_POST['username'];
// 		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$password = $_POST['password'];

		// Save username in session
		$_SESSION['username'] = $username;

		// Get hash from db
		$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
		$sql = "SELECT passwort FROM benutzer WHERE benutzername = '$username'";
		$result = $connection->query($sql);

		$hash = getHash($result);
		$verification = password_verify($password, $hash);
		file_put_contents('verification.txt', 'ergebnis: ' . $verification . ' passwort: ' . $password . ' hash: ' . $hash);
		if ($verification) {
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

// Get string from sql-query
function getHash($result) {
	while($row = mysqli_fetch_assoc($result)) {
		$result = $row["passwort"];
		return $result;
	}
}

?>
