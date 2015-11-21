<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<div class="container">
		<h1>Eventkalender<br /><small>Erstellen Sie hier einen neuen Benutzer.</small></h1>

		<?php
		if(isset($_GET["error"])) {
			echo '<div class="alert alert-danger">';
			echo '<p>Bei der Verarbeitung ist ein Fehler aufgetreten!</p>';
			echo '</div>';
		}
		?>

		<form action="function.php" method="post">
			<div class="form-group">
				<label for="username">Benutzername</label>
				<input class="form-control" type="text" id="username" name="username" placeholder="Foobar" required>
			</div>
			<div class="form-group">
				<label for="password">Passwort</label>
				<input class="form-control" type="password" id="password" name="password" placeholder="********" pattern="?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" required >
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</body>
</html>