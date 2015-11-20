

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<div class="container">
		<h1>Preisgruppe löschen</h1>

		<?php
		session_start();
		if(isset($_GET["error"])) {
			echo '<div class="alert alert-danger">';
			echo '<p>Bei der Verarbeitung ist ein Fehler aufgetreten!</p>';
			echo '</div>';
		}
		?>

		<form action="deletePricegroupFunction.php" method="post">
			<div class="form-group">
				<label for="name">Name</label>
				<input class="form-control" type="text" id="name" name="name" placeholder="Erwachsene" maxlength="20" required >
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</body>
</html>