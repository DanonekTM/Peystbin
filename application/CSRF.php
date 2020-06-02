<?php
if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

class CSRF
{
	public function GenerateToken()
	{
		$token = hash('sha256', $this->RandomGen(64)); // 64 bytes
		$_SESSION['token'] = $token;
		return $token;
	}
	
	public function Check($token)
	{
		if (isset($_SESSION['token']) && $token === $_SESSION['token'])
		{
			return true;
		}
		return false;
	}
	
	private function RandomGen($len)
	{
		if (function_exists('mcrypt_create_iv')) 
		{
			return bin2hex(mcrypt_create_iv($len, MCRYPT_DEV_URANDOM));
		} 
		else if (function_exists('random_bytes'))
		{
			return bin2hex(random_bytes($len));
		}
		else
		{
			return bin2hex(openssl_random_pseudo_bytes($len));
		}
	}
	
}