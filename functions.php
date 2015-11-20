<?php

function getSite()
{
	$site = "home";
	if(isset($_GET['site']))
	{
		$site = $_GET['site'];
	}
	return $site;
}

function parseSite($site, $data)
{
	$loader = new Twig_Loader_Filesystem('html');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate($site . ".html");
	return $template->render($data);
}

function getPriceGroups()
{
	$data = array();
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	$result = $connection->query("SELECT * from preisgruppe");
    while($row = $result->fetch_assoc())
    {
		$data[] = $row;
    }
    return $data;
}

function deletePriceGroup()
{
	$id = $_POST['selectid'];
	$connection = new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);
	if($connection->query("DELETE from preisgruppe where ID='" . $id . "';") === TRUE)
	{
		return "Preiskategorie wurde erfolgreich gelöscht!";
	}
	else
	{
		return "Preiskategorie konnte nicht gelöscht werden!";
	}
}

function isUserLoggedIn()
{
	return true;
}

function hasUserLoginCredentials()
{
	return true;
}

function login()
{
	return false;
}

?>
