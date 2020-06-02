<?php

require_once ("application/config/settings.php");

if (!defined("SELF_CALLED"))
{
	exit('Application not configured.');
}

switch (ENVIRONMENT)
{
	case 'dev':
	{
		error_reporting(-1);
		ini_set('display_errors', 1);
		break;
	}

	case 'live':
	{
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>=')) 
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		} 
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
		break;
	}

	default:
	{
		header('HTTP/1.1 503 Service Unavailable.', true, 503);
		echo 'The application environment is not set correctly.';
		exit(1);
	}
}

$Login = new Login();
$ConfigTools = new ConfigTools();

if ($Login->isUserLoggedIn())
{
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$themeToggle = isset($_POST['themeToggle']) ? "style-d.css" : "style.css";

		if (isset($themeToggle))
		{
			setcookie("theme", $themeToggle, time() + (60*60*24*20));
		}
		
		echo $themeToggle;
		die();
	}
	else
	{
		include("pages/error.php");
		return;
	}
}
else
{
	include("pages/error.php");
	return;
}

?>