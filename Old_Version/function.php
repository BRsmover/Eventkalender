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

// Salt for password hashing
$salt = substr(md5(uniqid()), 0, 3);

// Check if necessary fields are set and save data from form
if(isset($_POST['username']) && isset($_POST['password'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);

// 	echo("Status uppercase: " . $uppercase . "<br>");
// 	echo("Status lowercase: " . $lowercase . "<br>");
// 	echo("Status number: " . $number . "<br>");

	if($uppercase && $lowercase && $number && strlen($password) > 8) {
// 		echo("Passwort ok");
		$password = hash('sha512', $password . $salt);

		// SQL-Query
		$sql = "INSERT INTO benutzer (benutzername, passwort) VALUES ('$username', '$password')";

		// Try the query
		if($mysqli->query($sql) === TRUE) {
			header("Location: login.php");
			die();
		} else {
			file_put_contents(failedsql.txt, 'SQL failed.');
		}
	} else {
		file_put_contents(failedpw.txt, 'Password was not ok.');
	}
	file_put_contents(failedset.txt, 'A field was not set.');
}

header("Location: newUser.php?error=true");
die();
?>