<?php

require_once('libraries/Twig/lib/Twig/Autoloader.php');
require_once('functions.php');
require_once('config.php');

Twig_Autoloader::register();

$site = getSite();

if(isUserLoggedIn())
{
	if($site == "home")
	{
		echo(parseSite('home', array()));
	}
	else if($site == "delete-pricegroup")
	{
		echo(parseSite('delete-pricegroup', array("pricegroups" => getPriceGroups())));
	}
	else if($site == "delete-pricegroup-submit")
	{
		echo(parseSite('delete-pricegroup', array("status" => deletePriceGroup(), "pricegroups" => getPriceGroups())));
	}
	else
	{
		echo(parseSite('error', array()));
	}
}
else
{
	if(hasUserLoginCredentials())
	{
		if(login())
		{
			// redirect home
		}
		else
		{
			echo(parseSite('login', array("error" => "Ihre Logindaten stimmen nicht")));
		}
	}
	else
	{
		echo(parseSite('login', array()));
	}
}

?>
