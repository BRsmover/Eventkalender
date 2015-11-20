<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<div class="container">
		<h1>Passwort ändern</h1>
		<?php
		if($_SESSION['loggedin'] = true) {
			echo '<form action="changeFunction.php" method="post">
					<div class="form-group">
						<label for="old">Old Password</label>
						<input class="form-control" type="password" id="old" name="old" placeholder="********" required >
					</div>
					<div class="form-group">
						<label for="newOne">New Password</label>
						<input class="form-control" type="password" id="newOne" name="newOne" placeholder="********" required >
					</div>
					<div class="form-group">
						<label for="newTwo">Repeat new Password</label>
						<input class="form-control" type="password" id="newTwo" name="newTwo" placeholder="********" required >
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>';
		} else {
			header('Location: login.php');
			die();
		}
		?>
	</div>
</body>
</html>
