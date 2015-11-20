<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<div class="container">
		<h1>Neuen Event erstellen</h1>

		<?php
		if(isset($_GET["error"])) {
			echo '<div class="alert alert-danger">';
			echo '<p>Bei der Verarbeitung ist ein Fehler aufgetreten!</p>';
			echo '</div>';
		}
		?>

		<form action="newEventFunction.php" method="post">
			<div class="form-group">
				<label for="name">Name</label>
				<input class="form-control" type="text" id="name" name="name" placeholder="Veranstaltungsname" maxlength="100" required >
			</div>
			<div class="form-group">
				<label for="besetzung">Besetzung</label>
				<input class="form-control" type="text" id="besetzung" name="besetzung" placeholder="Max Frisch" maxlength="255" >
			</div>
			<div class="form-group">
				<label for="beschreibung">Beschreibung</label>
				<input class="form-control" type="text" id="beschreibung" name="beschreibung" placeholder="Spannend, packend..." required >
			</div>
			<div class="form-group">
				<label for="termin">Termin</label>
				<input class="form-control" type="datetime" id="termin" name="termin" placeholder="YYYY-MM-DD hh:mm:ss" required >
			</div>
			<div class="form-group">
				<label for="dauer">Dauer (in Minuten)</label>
				<input class="form-control" type="number" id="dauer" name="dauer" maxlength="5" placeholder="120" required >
			</div>
			<div class="form-group">
				<label for="bild">Bild</label>
				<input class="form-control" type="file" id="bild" name="bild" placeholder="Wählen Sie ein Bild aus..." required >
			</div>
			<div class="form-group">
				<label for="bildbeschreibung">Bildbeschreibung</label>
				<input class="form-control" type="text" id="bildbeschreibung" name="bildbeschreibung" maxlength="255" placeholder="Info zum Bild" required>
			</div>
			<div class="form-group">
				<label for="link">Link</label>
				<input class="form-control" type="text" id="link" name="link" placeholder="http://www.example.com" maxlength="100" required >
			</div>
			<div class="form-group">
				<label for="linkbeschreibung">Linkbeschreibung</label>
				<input class="form-control" type="text" id="linkbeschreibung" name="linkbeschreibung" placeholder="Info zum Link" required >
			</div>
			<div class="form-group">
				<label for="genre_id">ID des Genres</label>
				<input class="form-control" type="number" id="genre_id" name="genre_id" placeholder="3" required >
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</body>
</html>
<!-- Uploadtypen definieren -->