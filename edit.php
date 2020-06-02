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

$DatabaseFunctions->rewrite_on = $ConfigTools->config_item('rewrite_on');

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
	if (isset($_POST['save']))
	{
		if (isset($_SESSION["token"]) && !empty($_SESSION['token']) && $_SESSION['token'] == $_POST['token'])
		{
			$DatabaseFunctions->updatePaste($_POST['save'], $_POST['p'], isset($_POST['highlighting']), isset($_POST['wrap']), isset($_POST['private']));
		}
		else
		{
			include("pages/error.php");
			return;
		}
	}
	if (isset($_POST['delete']))
	{
		if (isset($_SESSION["token"]) && !empty($_SESSION['token']) && $_SESSION['token'] == $_POST['token'])
		{
			$DatabaseFunctions->deletePaste($ConfigTools->sanitizeRequest($_POST['delete']));
		
			header('Location: /');
			die();
		}
		else
		{
			include("pages/error.php");
			return;
		}
	}
	
	if ($ConfigTools->config_item('rewrite_on'))
	{
		if (isset($_SERVER['REQUEST_URI']))
		{
			$url = ltrim($_SERVER['REQUEST_URI'], 'edit/');
		}
		
		if ($url)
		{
			$paste_id = $ConfigTools->sanitizeRequest($url);
			$paste_info = $DatabaseFunctions->show_paste($paste_id);
			if (!$paste_info)
			{
				include("pages/error.php");
				return;
			}
			else
			{
				include("pages/edit.php");
				return;
			}
		}
		else
		{
			include("pages/error.php");
			return;
		}
	}
	else
	{
		if (isset($_GET['p']))
		{	
			$paste_id = $ConfigTools->sanitizeRequest($_GET['p']);
			$paste_info = $DatabaseFunctions->show_paste($paste_id);
			if (!$paste_info)
			{
				include("pages/error.php");
				return;
			}
			else
			{
				include("pages/edit.php");
				return;
			}
		}
		else
		{
			include("pages/error.php");
			return;
		}
		
	}
}
else
{
	include("pages/error.php");
	return;
}
?>