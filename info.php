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

$ConfigTools = new ConfigTools();
$Login = new Login();

if (!isset($_SESSION))
{
	session_start();
}


if ($ConfigTools->config_item('check_https'))
{
	if (!$ConfigTools->isHTTPS())
	{
		header('HTTP/1.1 503 Service Unavailable.', true, 503);
		echo 'The application is set to run only over HTTPS.';
		exit(1);
	}
}

if (isset($_SESSION['REGISTRATION_SUCCESS']) || isset($_SESSION['FORGOT_PASSWORD']) || isset($_SESSION['PASTE_LIMIT']) || isset($_SESSION['PASTE_SIZE_LIMIT']) || isset($_SESSION['UPGRADE_SUCCESS']))
{
	include("pages/info.php");
	return;
}
else
{
	include "pages/error.php";
	return;
}

