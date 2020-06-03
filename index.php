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
$CSRF = new CSRF();
$DatabaseFunctions = new DatabaseFunctions();
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

if (!isset($_SESSION))
{
	session_start();
}

if ($Login->isUserLoggedIn())
{
	if (isset($_POST['t']) && isset($_POST['d']) && isset($_POST['p']))
	{
		$DatabaseFunctions->add_paste($_POST['t'], $_POST['d'], $_POST['p'], isset($_POST['highlighting']), isset($_POST['wrap']), isset($_POST['guest']), isset($_POST['private']), true);
		return;
	}
	
	if ($ConfigTools->config_item('rewrite_on'))
	{
		if (isset($_SERVER['REQUEST_URI']))
		{
			$url = ltrim($_SERVER['REQUEST_URI'], '/');
		}
		
		if ($url)
		{
			$paste_id = $ConfigTools->sanitizeRequest($url);
			$DatabaseFunctions->incrementPasteViews($paste_id, $DatabaseFunctions->getUserIP());
			$paste_info = $DatabaseFunctions->show_paste($paste_id, false);

			include("pages/view.php");
			return;
		}
		else
		{
			include("pages/index.php");
			return;
		}
	}
	else
	{
		if (isset($_GET['p']))
		{
			$paste_id = $ConfigTools->sanitizeRequest($_GET['p']);
			$DatabaseFunctions->incrementPasteViews($paste_id, $DatabaseFunctions->getUserIP());
			
			if (isset($_SESSION['just_created']) && $_SESSION['just_created'] == true)
			{
				$paste_info = $DatabaseFunctions->show_paste($paste_id, true);
			}
			else
			{
				$paste_info = $DatabaseFunctions->show_paste($paste_id, false);
			}
			
			unset($_SESSION['just_created']);
			include("pages/view.php");
			return;
		}
		else
		{
			include("pages/index.php");
			return;
		}
	}
}
else
{
	if (isset($_GET['p']))
	{
		$paste_id = $ConfigTools->sanitizeRequest($_GET['p']);
		$DatabaseFunctions->incrementPasteViews($paste_id, $DatabaseFunctions->getUserIP());
		
		if (isset($_SESSION['just_created']) && $_SESSION['just_created'] == true)
		{
			$paste_info = $DatabaseFunctions->show_paste($paste_id, true);
		}
		else
		{
			$paste_info = $DatabaseFunctions->show_paste($paste_id, false);
		}
		
		unset($_SESSION['just_created']);
		include("pages/view.php");
		return;
	}
	if (isset($_POST['t']) && isset($_POST['d']) && isset($_POST['p']))
	{
		$DatabaseFunctions->add_paste($_POST['t'], $_POST['d'], $_POST['p'], isset($_POST['highlighting']), isset($_POST['wrap']), false, false, false);
		return;
	}
	
	include("pages/index.php");
	return;
}