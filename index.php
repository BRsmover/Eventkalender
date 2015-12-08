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

// Different actions for different sites ----------------------------- ?Switch?
if(isUserLoggedIn()) {
	// Home
	if($site == "home") {
		echo(parseSite('home', array("events" => getEvents())));
	}

	// Archive
	else if($site == "archive") {
		echo(parseSite('archive', array("oldEvents" => fillArchive())));
	}

	// Delete pricegroup
	else if($site == "delete-pricegroup") {
		echo(parseSite('delete-pricegroup', array("pricegroups" => getPriceGroups())));
	}
	else if ($site == "delete-pricegroup-submit") {
		echo(parseSite('delete-pricegroup', array("status" => deletePriceGroup(), "pricegroups" => getPriceGroups())));
	}

	// Create pricegroup
	else if ($site == 'create-pricegroup') {
		echo(parseSite('create-pricegroup', array()));
	}
	else if ($site == 'create-pricegroup-submit') {
		echo(parseSite('create-pricegroup-submit', array("status" => createPriceGroup())));
	}

	// Create user
	else if ($site == 'create-user') {
		echo(parseSite('create-user', array()));
	}
	else if ($site == 'create-user-submit') {
		echo(parseSite('create-user-submit', array("status" => createUser())));
	}

	// Create event
	else if ($site == 'create-event') {
		echo(parseSite('create-event', array("pricegroups" => getPriceGroups(), "genres" => getGenres())));
	}
	else if ($site == 'create-event-submit') {
		echo(parseSite('create-event-submit', array("status" => createEvent())));
	}

	// Login
	else if ($site == 'login') {
		echo(parseSite('login', array()));
	}
	else if ($site == 'login-submit') {
		echo(parseSite('login-submit', array(login())));
	}

	// About-page
	else if ($site == 'about') {
		echo(parseSite('about', array()));
	}

	// Admin-Page
	else if ($site == 'admin') {
		echo(parseSite('admin', array()));
	}

	// Create genre
	else if ($site == 'create-genre') {
		echo(parseSite('create-genre', array()));
	}
	else if ($site == 'create-genre-submit') {
		echo(parseSite('create-genre-submit', array("status" => createGenre())));
	}

	// Delete genre
	else if($site == "delete-genre") {
		echo(parseSite('delete-genre', array("genres" => getGenres())));
	}
	else if ($site == "delete-genre-submit") {
		echo(parseSite('delete-genre', array("status" => deleteGenre(), "genres" => getGenres())));
	}

	// Change password
	else if($site == "change-password") {
		echo(parseSite('change-password', array()));
	}
	else if ($site == "change-password-submit") {
		echo(parseSite('change-password-submit', array("status" => changePw())));
	}

	// Delete genre
	else if($site == "delete-event") {
		echo(parseSite('delete-event', array("events" => getEvents())));
	}
	else if ($site == "delete-event-submit") {
		echo(parseSite('delete-event', array("status" => deleteEvent(), "events" => getEvents())));
	}

	// Logout
	else if ($site == 'logout') {
		echo(parseSite('logout', array(logout())));
	}

	else {
		echo(parseSite('error', array()));
	} 
} else {
	if(hasUserLoginCredentials()) {
		if(login()) {
			// redirect home
		} else {
			echo(parseSite('login', array("error" => "Ihre Logindaten stimmen nicht")));
		}
	} else {
		echo(parseSite('login', array()));
	}
}

?>
