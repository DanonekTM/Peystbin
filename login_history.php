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
$Pages = new Pages;

if ($ConfigTools->config_item('check_https'))
{
	if (!$ConfigTools->isHTTPS())
	{
		header('HTTP/1.1 503 Service Unavailable.', true, 503);
		echo 'The application is set to run only over HTTPS.';
		exit(1);
	}
}

if (isset($_POST['old_password'], $_POST['new_password'], $_POST['new_password_repeat']) && !empty($_SESSION['token']))
{
	if (isset($_SESSION["token"]) && !empty($_SESSION['token']) && $_SESSION['token'] == $_POST['token'])
	{
		$samePass = $DatabaseFunctions->isSamePass($_POST['old_password'], $_POST['new_password'], $_POST['new_password_repeat']);
	}
	else
	{
		include("pages/error.php");
		return;
	}
}

if ($Login->isUserLoggedIn())
{	
	$limit = 20;
	$pageRequest = 1;
	$count = mysqli_num_rows($DatabaseFunctions->getLoginHistory());
	$pages = $Pages->findPages($count, $limit);
	
	if (isset($_GET['page']))
	{
		$pageRequest = $_GET['page'];
		if (!($_GET['page'] <= $pages))
		{
			include("pages/error.php");
			return;
		}
	}
	
	$start = $Pages->findStart($limit, $ConfigTools->sanitizePage($pageRequest));
	$result = $DatabaseFunctions->getLoginHistoryByPage($start, $limit);
	$pagelist = $Pages->pageList($ConfigTools->sanitizePage($pageRequest), $pages);

	include("pages/login_history.php");
	return;
}
else
{
	include("pages/error.php");
	return;
}