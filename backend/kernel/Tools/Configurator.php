<?php

namespace Danonek\Kernel\Tools;

class Configurator
{
	private static function getConfig(Array $replace = array())
	{
		static $config;

		if (empty($config))
		{
			$found = false;
			if (file_exists(CONFIG_FILE))
			{
				$found = true;
				require(CONFIG_FILE);
			}

			if (!$found)
			{
				die('Configuration file does not exist.');
			}

			if (!isset($config) or !is_array($config))
			{
				die('Config format failed.');
			}
		}

		foreach ($replace as $key => $val)
		{
			$config[$key] = $val;
		}

		return $config;
	}

	public static function getValue($item)
	{
		static $_config;

		if (empty($_config))
		{
			$_config[0] = self::getConfig();
		}

		return isset($_config[0][$item]) ? $_config[0][$item] : null;
	}
}
?>