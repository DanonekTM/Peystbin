<?php

namespace Danonek\Kernel\Providers;
use Danonek\Kernel\Tools\Configurator as Config;
use Danonek\Kernel\Tools\Utils;

class RedisHelper
{
	private $redis_connection = null;
	private $total_user_calls = 0;

	public function __construct()
	{
		if (!$this->redis_connection)
		{
			$this->redis_connection = new \Redis();
			try 
			{
				$this->redis_connection->connect(Config::getValue('REDIS_HOST'), Config::getValue('REDIS_PORT'));
				if (Config::getValue('REDIS_PASSWORD'))
					$this->redis_connection->auth(Config::getValue('REDIS_PASSWORD'));
			}
			catch (\Exception $e)
			{
				die(Utils::returnHttpCode(503, "Redis connection failed."));
			}
		}
		else
		{
			die(Utils::returnHttpCode(503));
		}
	}

	public function __destruct()
	{
		$this->redis_connection->close();
		$this->redis_connection = null;
	}

	public function isThrottled($key)
	{
		if (!$this->redis_connection->exists($key)) 
		{
			$this->redis_connection->set($key, 1);
			$this->redis_connection->expire($key, Config::getValue('THROTTLE_TIME_PERIOD'));
			$this->total_user_calls = 1;
		}
		else 
		{
			$this->redis_connection->INCR($key);
			$this->total_user_calls = $this->redis_connection->get($key);
		}

		return $this->total_user_calls > Config::getValue('THROTTLE_REQUESTS_PERMITTED');
	}
}
?>