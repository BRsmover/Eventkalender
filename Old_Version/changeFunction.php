<?php
// Need to verify with password_hash() and/ or password_verify()



session_start();

// Connect to db
$servername = 'localhost';
$username = 'benjamin';
$password = '123456aA';
$database = 'eventdb';
$mysqli = new mysqli($servername, $username, $password, $database);
if($mysqli->connect_error) {
	die("Connection failed: " . $mysqli->connect_error);
}

$old = $_POST['old'];
$first = $_POST['newOne'];
$second = $_POST['newTwo'];

// Get salt from db
$saltQuery = "SELECT salt FROM users WHERE username = '$username'";
$saltResult = $mysqli->query($saltQuery);
$salt = getValue($saltResult);

// Check if fields are filled in
if (isset($old) && isset($first) && isset($second)) {
	$sql = "SELECT password FROM users WHERE username = '$username'";
	$result = $mysqli->query($sql);
	// Check if old pw is correct
	file_put_contents('hashes.txt', 'salt ' . $salt . ' old pw ' . $old . ' new hash ' . hash('sha512', $old . $salt) . ' old hash ' . getHash($result));
	if(strcasecmp(hash('sha512', $old . $salt), getHash($result))) {
		// Check if new password is complex enough
		//var_dump(validate($first, $second));
		if(validate($first, $second)) {
			// Check if old password is not the new one
			if($old != $first && $old != $second) {
				// Check if the new passwords match
				if($first == $second) {
					$salt = substr(md5(uniqid()), 0, 3);
					$newPassword = hash('sha512', $first.$salt);
					$user = $_SESSION['username'];
					$sql = "UPDATE users SET password = '$newPassword' WHERE username = '$user'";
					$result = $mysqli->query($sql);

					if($result === TRUE) {
						//echo '<p>Password was successfully changed!</p>';
						
						header("Location: admin.php");
						die();
					} else {
						echo '<p>Password could not be saved!</p>';
					}
				} else {
					echo '<p>The new passwords do not match!</p>';
				}
			} else {
				echo '<p>Your new password must be different from your old one!</p>';
			}
		} else {
			echo '<p>Your password is not strong enough or your new passwords are not the same!</p>';
			//echo 'validation ' . validate($first, $second) . '<br />';
			//echo 'first ' . $first . '<br />';
			//echo 'second ' . $second;
		}
	} else {
		echo '<p>Your old password is incorrect!</p>';
	}
} else {
	echo '<p>Bitte füllen Sie alle Felder ein</p>';
}

// Check if password is complex enough
function validate($firstpw, $secondpw) {
	if(strcmp($firstpw, $secondpw) == 0) {
		// Booleans to check password complexity - if true, then firstpw is ok
		$uppercase = preg_match('@[A-Z]@', $firstpw);
		$lowercase = preg_match('@[a-z]@', $firstpw);
		$number = preg_match('@[0-9]@', $firstpw);
		if($uppercase && $lowercase && $number && strlen($firstpw) > 8) {
			return true;
		}
	}
	return false;
}

// Get string from sql-query
function getHash($result) {
	while($row = mysqli_fetch_assoc($result)) {
		$result = $row["password"];
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

// Close session & mysqli connection
$mysqli->close();

?>