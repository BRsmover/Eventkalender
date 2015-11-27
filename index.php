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
	if($site == "home") {
		echo(parseSite('home', array()));
	}

	else if($site == "delete-pricegroup") {
		echo(parseSite('delete-pricegroup', array("pricegroups" => getPriceGroups())));
	}
	else if ($site == "delete-pricegroup-submit") {
		echo(parseSite('delete-pricegroup', array("status" => deletePriceGroup(), "pricegroups" => getPriceGroups())));
	}

	else if ($site == 'create-pricegroup') {
		echo(parseSite('create-pricegroup', array()));
	}

	else if ($site == 'create-pricegroup-submit') {
		echo(parseSite('create-pricegroup-submit', array(createPriceGroup())));
	}

	else if ($site == 'create-user') {
		echo(parseSite('create-user', array()));
	}

	else if ($site == 'create-user-submit') {
		echo(parseSite('create-user-submit', array(createUser())));
	}

	else if ($site == 'create-event') {
		echo(parseSite('create-event', array()));
	}

	else if ($site == 'create-event-submit') {
		echo(parseSite('create-event-submit', array(createEvent())));
	}

	else if ($site == 'login') {
		echo(parseSite('login', array()));
	}

	else if ($site == 'login-submit') {
		echo(parseSite('login-submit', array(login())));
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
