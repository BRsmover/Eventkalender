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
		<p>Hi, nice to see you again!</p>
		<?php
			if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
				// Successfully logged in
				header("Location: admin.php");
				die();
			} else {
				// Security feature to make sure a non loggedin user really can't access this page
				header("Location: login.php");
				//file_put_contents('failed.txt', 'login failed -> from admin back to login' . " Val:" . var_dump($_session));
				die();
				//var_dump($_SESSION);
			}
		?>
	</div>
</body>
</html>