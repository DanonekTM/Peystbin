<?php

namespace Danonek\Kernel\Controllers;

use Danonek\Kernel\Tools\Configurator as Config;

class Controller
{
	public static function showPage()
	{
		include_once(__DIR__ . TEMPLATES_FOLDER . Config::getValue('APP_TEMPLATE'));
	}
	
	public static function MaintenancePage()
	{
		include_once(__DIR__ . TEMPLATES_FOLDER . Config::getValue('MAINTENANCE_TEMPLATE'));
	}

	public static function isJsonized()
	{
		if (json_decode(file_get_contents('php://input')) != null) 
		{
			return json_decode(file_get_contents('php://input'));
		}
		return false;
	}
}
?>