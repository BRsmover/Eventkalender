<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<?php
		session_start();
	?>
	<div class="container">
		<h1>Admin</h1>
		<?php
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
				// Successfully logged in
				print '<p>Sie sind eingeloggt als:<b> ' . $_SESSION['username'] . '</b></p>';
		} else {
			header("Location: login.php");
			die();
		}
		?>
		<a href='logout.php'>Logout</a><br />

		<div class="btn-toolbar" role="toolbar" aria-label="toolbar">
			<h2>Events</h2>
			<div class="btn-group" role="group" aria-label="event">
				<button class="btn btn-default"><a href="newEvent.php">Neue Veranstaltung</a></button>
				<button class="btn btn-default"><a href="editEvent.php">Veranstaltung bearbeiten</a></button>
				<button class="btn btn-default"><a href="deleteEvent.php">Veranstaltung löschen</a></button>
			</div>
			<br />
			<h2>Genres</h2>
			<div class="btn-group" role="group" aria-label="genre">
				<button class="btn btn-default"><a href="newGenre.php">Neues Genre</a></button>
				<button class="btn btn-default"><a href="deleteGenre.php">Genre löschen</a></button>
			</div>
			<br />
			<h2>Preisgruppen</h2>
			<div class="btn-group" role="group" aria-label="pricegroup">
				<button class="btn btn-default"><a href="newPricegroup.php">Neue Preisgruppe</a></button>
				<button class="btn btn-default"><a href="deletePricegroup.php">Preisgruppe löschen</a></button>
			</div>
			<br />
			<h2>Benutzer</h2>
			<div class="btn-group" role="group" aria-label="user">
				<button class="btn btn-default"><a href="newUser.php">Neuer Benutzer</a></button>
				<button class="btn btn-default"><a href="changePw.php">Passwort ändern</a></button>
			</div>
		</div>
	</div>
</body>
</html>