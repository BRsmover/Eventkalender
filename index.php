<?php
// Start session
session_start();

// Require the Twig Autoloader
require_once('libraries/Twig/lib/Twig/Autoloader.php');
// Require the functions
require_once('functions.php');
// Require the config (MYSQL)
require_once('config.php');

Twig_Autoloader::register();

$site = getSite();

// Home
if($site == "home") {
	echo(parseSite('home', array("events" => getCompleteEvents())));
}

// Archive
else if($site == "archive") {
	echo(parseSite('archive', array("oldEvents" => fillArchive())));
}

// About-page
else if ($site == 'about') {
	echo(parseSite('about', array()));
}

// Login
else if ($site == 'login') {
	echo(parseSite('login', array()));
}
else if ($site == 'login-submit') {
	echo(parseSite('login-submit', array(login())));
}

// The following sites require that the user is logged in

// Delete pricegroup
else if($site == "delete-pricegroup") {
	if(checkLogin()) {
		echo(parseSite('delete-pricegroup', array("pricegroups" => getPriceGroups())));
	}
}
else if ($site == "delete-pricegroup-submit") {
	if(checkLogin()) {
		echo(parseSite('delete-pricegroup', array("status" => deletePriceGroup(), "pricegroups" => getPriceGroups())));
	}
}

// Create pricegroup
else if ($site == 'create-pricegroup') {
	if(checkLogin()) {
		echo(parseSite('create-pricegroup', array()));
	}
}
else if ($site == 'create-pricegroup-submit') {
	if(checkLogin()) {
		echo(parseSite('create-pricegroup', array("status" => createPriceGroup())));
	}
}

// Create user
else if ($site == 'create-user') {
	if(checkLogin()) {
		echo(parseSite('create-user', array()));
	}
}
else if ($site == 'create-user-submit') {
	if(checkLogin()) {
		echo(parseSite('create-user', array("status" => createUser())));
	}
}

// Admin-Page
else if ($site == 'admin') {
	if(checkLogin()) {
		echo(parseSite('admin', array()));
	}
}

// Create genre
else if ($site == 'create-genre') {
	if(checkLogin()) {
		echo(parseSite('create-genre', array()));
	}
}
else if ($site == 'create-genre-submit') {
	if(checkLogin()) {
		echo(parseSite('create-genre', array("status" => createGenre())));
	}
}

// Delete genre
else if($site == "delete-genre") {
	if(checkLogin()) {
		echo(parseSite('delete-genre', array("genres" => getGenres())));
	}
}
else if ($site == "delete-genre-submit") {
	if(checkLogin()) {
		echo(parseSite('delete-genre', array("status" => deleteGenre(), "genres" => getGenres())));
	}
}

// Create event
else if ($site == 'create-event') {
	if(checkLogin()) {
		echo(parseSite('create-event', array("pricegroups" => getPriceGroups(), "genres" => getGenres())));
	}
}
else if ($site == 'create-event-submit') {
	if(checkLogin()) {
		echo(parseSite('create-event', array("status" => createEvent())));
	}
}

// Delete event
else if($site == "delete-event") {
	if(checkLogin()) {
		echo(parseSite('delete-event', array("events" => getEvents())));
	}
}
else if ($site == "delete-event-submit") {
	if(checkLogin()) {
		echo(parseSite('delete-event', array("status" => deleteEvent(), "events" => getEvents())));
	}
}

// Edit event
else if($site == "choose-event") {
	if(checkLogin()) {
		echo(parseSite('choose-event', array("events" => getEvents())));
	}
}
	else if($site == "edit-event") {
	if(checkLogin()) {
		echo(parseSite('edit-event', array("pricegroups" => getPriceGroups(), "genres" => getGenres(), "event" => getEditEvent())));
	}
}
else if ($site == "edit-event-submit") {
	if(checkLogin()) {
		echo(parseSite('edit-event', array("status" => editEvent())));
	}
}

// Logout
else if ($site == 'logout') {
	if(checkLogin()) {
		echo(parseSite('logout', array(logout())));
	}
}

// Change password
else if($site == "change-password") {
	if(checkLogin()) {
		echo(parseSite('change-password', array()));
	}
}
else if ($site == "change-password-submit") {
	if(checkLogin()) {
		echo(parseSite('change-password', array("status" => changePw())));
	}
}

else {
	echo(parseSite('error', array()));
} 

?>
