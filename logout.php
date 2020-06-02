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
$DatabaseFunctions = new DatabaseFunctions();
$ConfigTools = new ConfigTools;

$Login->rewrite_on = $ConfigTools->config_item('rewrite_on');

if ($ConfigTools->config_item('check_https'))
{
	if (!$ConfigTools->isHTTPS())
	{
		header('HTTP/1.1 503 Service Unavailable.', true, 503);
		echo 'The application is set to run only over HTTPS.';
		exit(1);
	}
}

if ($Login->isUserLoggedIn())
{
	if (isset($_SESSION["token"]) && !empty($_SESSION['token']))
	{
		if (isset($_SESSION['session_started']) && $_SESSION['session_started'])
		{
			$DatabaseFunctions->databaseSession("stop");
		}
		$Login->doLogout();
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