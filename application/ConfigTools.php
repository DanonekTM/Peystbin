<?php
if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

class ConfigTools
{	
	private function get_config(Array $replace = array())
	{
		static $config;

		if (empty($config))
		{
			$file_path = 'application/config/config.php';
			$found = false;
			if (file_exists($file_path))
			{
				$found = true;
				require($file_path);
			}

			if (!$found)
			{
				echo 'Configuration file does not exist.';
				exit(1);
			}

			if (!isset($config) or !is_array($config))
			{
				echo 'Format failed.';
				exit(1);
			}
		}
		foreach ($replace as $key => $val)
		{
			$config[$key] = $val;
		}

		return $config;
	}

	function config_item($item)
	{
		static $_config;

		if (empty($_config))
		{
			$_config[0] = $this->get_config();
		}

		return isset($_config[0][$item]) ? $_config[0][$item] : null;
	}
	
	function isHTTPS() 
	{
		return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}
	
	function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		if (preg_match('/linux/i', $u_agent)) 
		{
			$platform = 'Linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) 
		{
			$platform = 'Mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) 
		{
			$platform = 'Windows';
		}
	   
		if(preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i', $u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i', $u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i', $u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Edge/i', $u_agent))
		{
			$bname = 'Edge';
			$ub = "Edge";
		}
	   
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		
		if (preg_match_all($pattern, $u_agent, $matches))
		{
			$i = count($matches['browser']);
			if ($i != 1) 
			{
				if (strripos($u_agent, "Version") < strripos($u_agent, $ub))
				{
					$version = $matches['version'][0];
				}
				else 
				{
					$version = $matches['version'][1];
				}
			}
			else 
			{
				$version = $matches['version'][0];
			}
		   
			if ($version == null || $version == "")
			{
				$version = "?";
			}
		   
			return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
			);
		}
	}
	
	public function sanitizeRequest($request)
	{
		$request = trim($request);
		$request = stripslashes($request);
		$request = htmlspecialchars($request);
		$request = htmlentities($request);
		return $request;
	}

	public function sanitizePage($request)
	{
		$request = trim($request);
		$request = stripslashes($request);
		$request = htmlspecialchars($request);
		$request = htmlentities($request);
		
		if(!is_numeric($request))
		{
			$request = 1;
		}

		return $request;
	}
	
	public function sanitizePaste($string)
	{
		$string = htmlentities($string);
		return $string;
	}
	
	public function resetCookies()
	{
		if (isset($_COOKIE['theme']))
		{
			unset($_COOKIE['theme']);
			setcookie("theme", "", 1);
		}
	}
}
?>