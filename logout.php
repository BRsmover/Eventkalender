<?php
session_start();
$_session = array();
session_destroy();
header("Location: login.php");
die();
?>