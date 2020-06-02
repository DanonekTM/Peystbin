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
$DatabaseFunctions = new DatabaseFunctions();
$Login = new Login();
$CSRF = new CSRF();

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

if ($ConfigTools->config_item('use_mail'))
{
	if (isset($_POST['reset_password'], $_POST['captcha']))
	{
		if ($_POST['captcha'] != $_SESSION['digit'])
		{
			$error = "Wrong captcha code.";
			include "pages/forgot.php";
			return;
		}
		else
		{
			if ($DatabaseFunctions->sendForgottenPasword($_POST['forgot_email'], $_POST['forgot_username']))
			{
				if ($ConfigTools->config_item('rewrite_on'))
				{
					$_SESSION['FORGOT_PASSWORD'] = true;
					header('Location: info');
				}
				else
				{
					$_SESSION['FORGOT_PASSWORD'] = true;
					header('Location: info.php');
				}
				die();
			}
			else
			{
				if ($ConfigTools->config_item('rewrite_on'))
				{
					$_SESSION['FORGOT_PASSWORD'] = false;
					header('Location: info');
				}
				else
				{
					$_SESSION['FORGOT_PASSWORD'] = false;
					header('Location: info.php');
				}
				die();
			}
		}
	}
	else
	{
		include "pages/forgot.php";
		return;
	}
}
else
{
	header('HTTP/1.1 503 Service Unavailable.', true, 503);
	echo 'Sending forgotten passwords is not currently set.';
	exit(1);
}
