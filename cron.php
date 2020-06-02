<?php

require_once ("application/config/settings.php");
// PHP IS WEIRD
require_once ("application/DatabaseFunctions.php");
require_once ("application/Login.php");
require_once ("application/ConfigTools.php");

if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

$DatabaseFunctions = new DatabaseFunctions;
$ConfigTools = new ConfigTools;
$Login = new Login();

if (php_sapi_name() == 'cli')
{
    $DatabaseFunctions->cron();
    die();
}
else
{
	include "pages/error.php";
	return;
}