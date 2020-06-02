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
	$loginHistory = $DatabaseFunctions->getLoginHistoryByPage(0, 3);

	if (isset($_POST['delete_confirm']) && isset($_POST['captcha']))
	{
		if (isset($_SESSION["token"]) && !empty($_SESSION['token']) && $_SESSION['token'] == $_POST['token'])
		{
			if (empty($_POST['captcha']))
			{
				$_SESSION['delete_account_error'] = "Captcha field was empty.";
				header('Location: ?delete_account');
			}
			elseif ($_POST['captcha'] != $_SESSION['digit'])
			{
				$_SESSION['delete_account_error'] = "Wrong captcha code.";
				header('Location: ?delete_account');
			}
			else
			{
				$DatabaseFunctions->deleteAccount($_SESSION['user_id']);
			}
			die();
		}
		else
		{
			$_SESSION['delete_account_error'] = "Request failed, please try again.";
			header('Location: ?delete_account');
		}
		die();
	}
	
	if (isset($_POST['upgrade_code']))
	{
		$code = $ConfigTools->sanitizeRequest($_POST['upgrade_code']);
		
		if (empty($code))
		{
			$_SESSION['delete_account_error'] = "Code field was left empty.";
			header('Location: ?upgrade_account');
		}
		elseif (strlen($code) < 8)
		{
			$_SESSION['upgrade_account_error'] = "Code must be 8 characters long!";
			header('Location: ?upgrade_account');
		}
		elseif ($_SESSION['user_title'] == "Premium")
		{
			$_SESSION['upgrade_account_error'] = "You're already a premium user!";
			header('Location: ?upgrade_account');
		}
		elseif (!$DatabaseFunctions->upgradeAccount($code))
		{
			$_SESSION['upgrade_account_error'] = "Code is invalid!";
			header('Location: ?upgrade_account');
		}
		else
		{
			$_SESSION['UPGRADE_SUCCESS'] = true;
		
			if ($ConfigTools->config_item('rewrite_on'))
			{
				header('Location: /info');
			}
			else
			{
				header('Location: /info.php');
			}
			$ConfigTools->resetSession();
		}
		die();
	}

	if (isset($_SERVER['QUERY_STRING']))
	{	
		switch ($_SERVER['QUERY_STRING'])
		{
			case "delete_account":
				include("pages/delete_account.php");
			break;
			
			case "upgrade_account":
			
				if ($_SESSION['user_title'] == "Premium")
				{
					include "pages/error.php";
				}
				else
				{
					include("pages/upgrade.php");
				}
			break;	

			case "login_history":
				if ($ConfigTools->config_item('rewrite_on'))
				{
					header("Location: /login_history");
				}
				else
				{
					header("Location: /login_history.php");
				}
				die();
			break;
			
			default:
				include("pages/cp.php");
			break;
		}
		
		return;
	}	

	include("pages/cp.php");
	return;
}
else
{
	include("pages/error.php");
	return;
}