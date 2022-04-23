<?php

namespace Danonek\Kernel\Tools;

use Danonek\Kernel\Tools\Configurator as Config;

class Utils
{
	public static function isHTTPS() : bool
	{
		return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}

	public static function developmentCORS(): void 
	{
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
			header('Access-Control-Allow-Credentials: true');
		}
		
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");

			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers: " . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);

			exit(0);
		}
	}

	public static function returnHttpCode($code, $message = null)
	{
		if ($message == null)
		{
			switch($code)
			{
				case 200:
					$message = "OK.";
				break;

				case 400:
					$message = "Bad Request.";
				break;

				case 401:
					$message = "Not Authorized.";
				break;

				case 403:
					$message = "Forbidden.";
				break;

				case 404:
					$message = "Invalid Request.";
				break;

				case 405:
					$message = "Method Not Allowed.";
				break;

				case 429:
					$message = "Too Many Requests.";
				break;

				case 500:
					$message = "Internal Server Error.";
				break;

				case 503:
					$message = "Service Unavailable.";
				break;

				default:
					$message = "Internal Server Error.";
			}
		}

		$response = array(
			"error" => ($code !== 200 ? true : false),
			"details" => array(
				"code" => $code,
				"message" => $message
			),
		);

		http_response_code($code);
		return json_encode($response);
	}

	public static function genRandomString($length = 256) : string
	{
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public static function sanitiseInput($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	public static function getBurnCode() : ?string
	{
		if (isset($_COOKIE['BURN_CODE']) && !empty($_COOKIE['BURN_CODE']))
		{
			if (strlen(self::sanitiseInput($_COOKIE['BURN_CODE'])) === Config::getValue("PASTE_BURN_CODE_LENGTH"))
			{
				return self::sanitiseInput($_COOKIE['BURN_CODE']);
			}
		}
		return null;
	}

	public static function deleteCookie($cookie_name) : void
	{
		$expires = time() - Config::getValue('TOKEN_EXPIRE_TIME');

		$arr_cookie_options = array(
			'path' => '/',
			'secure' => Utils::isHTTPS(),
			'httponly' => true,
			'expires' => $expires,
		);
		
		setcookie($cookie_name, '', $arr_cookie_options);
	}

	public static function deletePastesOnFileSystem($pasteUids) : void
	{
		if ($pasteUids == null) return;
		if (is_array($pasteUids))
		{
			foreach ($pasteUids as $pasteUid)
			{
				$file = PASTES_DIR . $pasteUid . '.dat';
				if (file_exists($file))
					unlink($file);
			}
		}
		else
		{
			$file = PASTES_DIR . $pasteUids . '.dat';
			if (file_exists($file))
				unlink($file);
		}
	}

	public static function getUserIP() : string
	{
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
		{
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}

		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if (filter_var($client, FILTER_VALIDATE_IP)) 
		{ 
			$ip = $client; 
		}
		elseif (filter_var($forward, FILTER_VALIDATE_IP))
		{ 
			$ip = $forward; 
		}
		else
		{
			$ip = $remote; 
		}

		return $ip;
	}

	public static function checkRECAPTCHA($user_captcha) : bool
	{
		$post_data = http_build_query(
			array(
				'secret' => Config::getValue("RECAPTCHA_SECRET"),
				'response' => $user_captcha,
				'remoteip' => Utils::getUserIP()
			)
		);
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $post_data
			)
		);
		$context = stream_context_create($opts);
		$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
		$result = json_decode($response);

		return $result->success;
	}

	public static function formatBytes($size, $precision = 2)
	{
		$unit = ['Bytes','KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];

		for ($i = 0; $size >= 1024 && $i < count($unit) - 1; $i++)
		{
			$size /= 1024;
		}
		return round($size, $precision) .' ' . $unit[$i];
	}

	public static function expiryTagToSeconds($tag, $isPasteUpdate = false)
	{
		switch ($tag)
		{
			case "E":
				$deletion_date = -1;
			break;
			
			case "BURN":
				$deletion_date = -2;
			break;

			case "1H":
				$deletion_date = 3600;
			break;

			case "1D":
				$deletion_date = 86400;
			break;

			case "1W":
				$deletion_date = 604800;
			break;

			case "10M":
				$deletion_date = 600;
			break;

			default:
				$deletion_date = $isPasteUpdate ? null : 600;
		}

		if ($deletion_date === null) return null;

		if ($deletion_date > 0)
		{
			$deletion_date += time();
		}

		return $deletion_date;
	}

	public static function formatExposure($exposure, $isPasteUpdate = false)
	{
		if ($exposure === 'DC' && $isPasteUpdate) return null;

		$allowedExposure = [
			'PUBLIC',
			'UNLISTED',
			'PRIVATE',
		];
		
		return isset($allowedExposure[array_search($exposure, $allowedExposure)]) ? $allowedExposure[array_search($exposure, $allowedExposure)] : 'PUBLIC';
	}

	public static function formatExpiry($timestamp)
	{
		$abstractFormat = [
			0 => 'Expired',
			-1 => 'Never',
			-2 => 'Your eyes only'
		];

		if (isset($abstractFormat[$timestamp])) return $abstractFormat[$timestamp];

		$format = function($time, $string) : string
		{
			return $time ? $time . ' ' . $string . ($time > 1 ? 's' : '' ) . ' ' : '';
		};

		$expiration = new \DateTime('@'.$timestamp);
		$interval = $expiration->diff(new \DateTime(), true);

		$ret = $format($interval->d, 'day');
		$ret .= $format($interval->h, 'hour');
		if ($interval->d === 0)
		{
			$ret .= $format($interval->i, 'minute');
			if ($interval->h === 0)
			{
				$ret .= $format($interval->s, 'second');
			}
		}

		return rtrim($ret);
	}

	public static function getBearerToken() : ?string
	{
		$headers = null;
		if (isset($_SERVER['Authorization'])) 
		{
			$headers = trim($_SERVER["Authorization"]);
		}
		else if (isset($_SERVER['HTTP_AUTHORIZATION'])) 
		{
			$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
		}
		elseif (function_exists('apache_request_headers')) 
		{
			$requestHeaders = apache_request_headers();
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			if (isset($requestHeaders['Authorization'])) 
			{
				$headers = trim($requestHeaders['Authorization']);
			}
		}
		
		if (!empty($headers)) 
		{
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) 
			{
				return $matches[1] !== "null" ? $matches[1] : null;
			}
		}
		return null;
	}

	public static function getUserAgentDetails() : array
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "";

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

		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif (preg_match('/Firefox/i', $u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif (preg_match('/Chrome/i', $u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif (preg_match('/Safari/i', $u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif (preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif (preg_match('/Edge/i', $u_agent))
		{
			$bname = 'Edge';
			$ub = "Edge";
		}

		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		
		if (preg_match_all($pattern, $u_agent, $matches))
		{
			if (count($matches['browser']) != 1) 
			{
				$version = strripos($u_agent, "Version") < strripos($u_agent, $ub) ? $matches['version'][0] : $matches['version'][1];
			}
			else 
			{
				$version = $matches['version'][0];
			}

			if ($version == null || $version == "")
			{
				$version = "?";
			}
		}

		return array(
			'userAgent' => $u_agent,
			'name' => $bname,
			'version' => $version,
			'platform' => $platform,
			'pattern' => $pattern
		);
	}
}
?>