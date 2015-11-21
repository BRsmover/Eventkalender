<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
	<div class="container">
		<h1>Login</h1>
		<form action="goin.php" method="post">
			<div class="form-group">
				<label for="username">Username</label>
				<input class="form-control" type="text" id="username" name="username" placeholder="Foobar" required >
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input class="form-control" type="password" id="password" name="password" placeholder="********" required >
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</body>
</html>
