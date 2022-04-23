<?php

/*
	--------------------------------
	 Autoload modules from composer
	--------------------------------
*/
require_once '../vendor/autoload.php';

/*
	-----------------
	 Global Defines
	-----------------
*/

define('PASTES_DIR', __DIR__ . '/../data/pastes/');
define('EMAIL_TEMPLATE_DIR', __DIR__ . '/../data/email/');
define('ALLOWED_EMAIL_PROVIDERS', __DIR__ . '/../data/emails.dat');
define('CACHE_DIR', __DIR__ . '/../data/cache/');
define('TEMPLATES_FOLDER', '/../../views/');
define('CONFIG_FILE',  __DIR__ . './config.php');

session_name(Danonek\Kernel\Tools\Configurator::getValue('SESSION_NAME'));
session_start();


/*
	------------------------------
	 Set enviroment (dev or live)
	------------------------------
*/

define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : Danonek\Kernel\Tools\Configurator::getValue('ENVIROMENT'));

switch (ENVIRONMENT)
{
	case 'dev':
	{
		error_reporting(-1);
		ini_set('display_errors', 1);
		if (php_sapi_name() !== "cli")
			Danonek\Kernel\Tools\Utils::developmentCORS();
		break;
	}

	case 'live':
	{
		error_reporting(0);
		ini_set('display_errors', 0);
		break;
	}

	default:
	{
		http_response_code(503);
		die("Environment not configured.");
	}
}

/*
	-------------------
	 Time Zone of PHP
	-------------------
*/
date_default_timezone_set(Danonek\Kernel\Tools\Configurator::getValue("SITE_REGION"));

?>