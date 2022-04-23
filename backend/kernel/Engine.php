<?php

namespace Danonek\Kernel;

use Danonek\Kernel\Controllers\AccountController;
use Danonek\Kernel\Controllers\ApiController;
use Danonek\Kernel\Router as Route;
use Danonek\Kernel\Controllers\Controller;
use Danonek\Kernel\Tools\Utils;
use Danonek\Kernel\Providers\DataRetriever;
use Danonek\Kernel\Providers\RedisHelper;
use Danonek\Kernel\Tools\Configurator as Config;

class Engine
{
	private static $instace = null;

	public static function getInstance()
	{
		if (self::$instace == null)
		{
			self::$instace = new self();
		}
		return self::$instace;
	}
	
	public function run()
	{
		if (!Config::getValue("MAINTENANCE_MODE"))
		{
			Route::add('/api/add_paste', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP()))
				{
					ApiController::getInstance()->addPaste();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::add('/api/get_paste', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					ApiController::getInstance()->getPaste();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');
			
			Route::add('/api/get_paste_metadata', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					ApiController::getInstance()->getPasteMetadata();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::add('/api/update_paste', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					ApiController::getInstance()->updatePaste();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::add('/api/delete_paste', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					ApiController::getInstance()->deletePaste();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'DELETE');

			Route::add('/api/my_pastes', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					ApiController::getInstance()->getMyPastes();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::add('/api/recent_pastes', function() 
			{
				DataRetriever::getCachedRecentPastes();
			}, 'GET');

			Route::add('/api/user', function() 
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					AccountController::getInstance()->getUserInfo();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::add('/api/auth/login', function()
			{
				AccountController::getInstance()->doLogin();
			}, 'POST');

			Route::add('/api/auth/refresh', function()
			{
				AccountController::getInstance()->refreshJWT();
			}, 'POST');

			Route::add('/api/auth/register', function()
			{
				AccountController::getInstance()->doRegister();
			}, 'POST');
			
			Route::add('/api/auth/confirm_email', function()
			{
				AccountController::getInstance()->confirmEmail();
			}, 'POST');

			Route::add('/api/auth/reset_password', function()
			{
				AccountController::getInstance()->resetPassword();
			}, 'POST');

			Route::add('/api/auth/forgot_password', function()
			{
				AccountController::getInstance()->forgotPassword();
			}, 'POST');

			Route::add('/api/auth/delete_account', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					AccountController::getInstance()->deleteAccount();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::add('/api/auth/change_password', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					AccountController::getInstance()->changePassword();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::add('/api/auth/logout', function()
			{
				$RedisHelper = new RedisHelper();
				if (!$RedisHelper->isThrottled(Utils::getUserIP())) 
				{
					AccountController::getInstance()->doLogout();
				}
				else
				{
					echo Utils::returnHttpCode(429, "You are being rate limited!");
				}
			}, 'POST');

			Route::methodNotAllowed(function() {
				echo Utils::returnHttpCode(405);
			});

			Route::add('.*', function()
			{
				Controller::showPage();
			});
		}
		else
		{
			Controller::MaintenancePage();
		}
		
		Route::run(Config::getValue("BASE_PATH"));
	}
}
?>