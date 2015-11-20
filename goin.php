<?php
// Start session
session_start();

// MYSQL credentials
$servername = "localhost";
$username = "benjamin";
$password = "123456aA";
$dbname = "Eventkalender";

// Check connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
if($mysqli->connect_errno) {
	die("Connection failed: " . $mysqli->connect_error);
}

// Save form data
if(isset($_POST['username']) && isset($_POST['password'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
}

// Save username in session
$_SESSION['username'] = $username;

// Get salt from db
$saltQuery = "SELECT salt FROM benutzer WHERE benutzername = '$username'";
$saltResult = $mysqli->query($saltQuery);
$salt = getValue($saltResult);

// Get hash from db
$sql = "SELECT password FROM users WHERE benutzername = '$username'";
$result = $mysqli->query($sql);

// Verify password
if(strcasecmp(hash('sha512', $password . $salt), getHash($result))) {
	$_SESSION['loggedin'] = true;
	// Redirect to admin.php
	header("Location: checkLogin.php");
	die();
} else {
	echo "password is not correct";
}

// Get string from sql-query
function getHash($result) {
	while($row = mysqli_fetch_assoc($result)) {
		$result = $row["passwort"];
 		return $result;
	}
}

// Get string from sql-query
function getValue($result) {
	while($row = mysqli_fetch_assoc($result)) {
		$result = $row["salt"];
 		return $result;
	}
}

?>