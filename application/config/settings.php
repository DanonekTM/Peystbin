<?php

define("SELF_CALLED", true);

// set to "live" when launching publically or "dev" when developing
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'dev');

if (!defined("SELF_CALLED"))
{
	exit('Application not configured.');
}

define("DB_HOST", "127.0.0.1:1337");
define("DB_USER", "danonek");
define("DB_PASS", "localhost");
define("DB_NAME", "pastebin");

if (version_compare(PHP_VERSION, '5.3.7', '<'))
{
	exit("PHP version 5.3.7 or newer required!");
}
else if (version_compare(PHP_VERSION, '5.5.0', '<'))
{
	require_once("application/libraries/password_compatibility_library.php");
}

spl_autoload_register(function($class)
{
	require_once 'application/' . $class . '.php';
});

?>