<?php

namespace Danonek\Kernel\Controllers;

use Danonek\Kernel\Tools\Configurator as Config;
use Danonek\Kernel\Providers\DatabaseHelper;
use Danonek\Kernel\Tools\Utils;
use Danonek\Kernel\Tools\Mailer;
use Firebase\JWT\JWT;

class AccountController
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

	public function doRegister()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			if (isset($json->email, $json->nickname, $json->password, $json->tos))
			{
				$message = null;
				if (empty($json->captcha)
				|| empty($json->email)
				|| empty($json->nickname)
				|| empty($json->password)
				|| empty($json->tos))
				{
					$message = "Fill out all fields!";
				}
				elseif (strlen($json->nickname) < Config::getValue("MINIMUM_NICKNAME_LENGTH"))
				{
					$message = "Nickname must have more than " . Config::getValue("MINIMUM_NICKNAME_LENGTH") . " characters!";
				}
				elseif (strlen($json->nickname) > Config::getValue("MAXIMUM_NICKNAME_LENGTH"))
				{
					$message = "Nickname cannot exceed " . Config::getValue("MAXIMUM_NICKNAME_LENGTH") . " characters!";
				}
				elseif (strlen($json->password) < Config::getValue("MINIMUM_PASSWORD_LENGTH"))
				{
					$message = "Password must have more than " . Config::getValue("MINIMUM_PASSWORD_LENGTH") . " characters!";
				}
				elseif (strlen($json->password) > Config::getValue("MAXIMUM_PASSWORD_LENGTH"))
				{
					$message = "Password cannot exceed " . Config::getValue("MAXIMUM_PASSWORD_LENGTH") . " characters!";
				}
				elseif (!preg_match('/^[a-zA-Z0-9\-_]{' . Config::getValue("MINIMUM_NICKNAME_LENGTH") . ',' . Config::getValue("MAXIMUM_NICKNAME_LENGTH") . '}$/', $json->nickname))
				{
					$message = "Nickname format invalid!";
				}
				elseif (!filter_var($json->email, FILTER_VALIDATE_EMAIL))
				{
					$message = "Email format invalid!";
				}
				elseif (strlen($json->email) > Config::getValue("MAXIMUM_EMAIL_LENGTH"))
				{
					$message = "Email address is too long!";
				}
				elseif ($json->tos !== true)
				{
					$message = "Please accept the terms and conditions!";
				}
				else
				{
					$json->email = filter_var($json->email, FILTER_SANITIZE_EMAIL);
					$parts = explode('@', $json->email);
					$domain = array_pop($parts);
					$allowed = file(ALLOWED_EMAIL_PROVIDERS, FILE_IGNORE_NEW_LINES);

					if (!in_array($domain, $allowed))
					{
						$message = "Email provider not supported!";
					}
					else if (!Utils::checkRECAPTCHA($json->captcha))
					{
						$message = "Captcha mismatch!";
					}
					else
					{
						$DbHelper = new DatabaseHelper();
						if (!$DbHelper->checkAccountExists($json->email, $json->nickname))
						{
							$code = Config::getValue("REQUIRE_EMAIL_CONFIRMATION") && Config::getValue("SEND_MAIL") ? Utils::genRandomString(Config::getValue("ACTIVATION_CODE_LENGTH")) : "DONE";

							$peppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->password, Config::getValue("PASSWORD_PEPPER"));
							$hash = password_hash($peppered, PASSWORD_DEFAULT);
						
							$details = array(
								'user_nickname' => $json->nickname, 
								'user_password' => $hash,
								'user_email' => $json->email,
								'user_join_date' => time(),
								'user_code' => $code,
								'user_api_key' => Utils::genRandomString(Config::getValue("API_KEY_LENGTH"))
							);
							
							$DbHelper->createAccount($details);
							
							if (Config::getValue("REQUIRE_EMAIL_CONFIRMATION") && Config::getValue("SEND_MAIL"))
							{
								$mail = new Mailer();
								$mail->sendMail($details, 'CONFIRM');
							}
							
							$message = "OK";
						}
						else
						{
							$message = "Email or nickname already exists!";
						}
					}
				}
				
				echo $message === "OK" ? Utils::returnHttpCode(200, "Account Created!") : Utils::returnHttpCode(400, $message);
			}
			else
			{
				echo Utils::returnHttpCode(400);
			}
		}
		else
		{
			echo Utils::returnHttpCode(405);
		}
	}

	public function doLogin()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			if (isset($json->email, $json->password, $json->captcha) && !empty($json->email) && !empty($json->password) && !empty($json->captcha))
			{
				if (!Utils::checkRECAPTCHA($json->captcha))
				{
					echo Utils::returnHttpCode(401, "Captcha mismatch!");
				}
				else
				{
					$DbHelper = new DatabaseHelper();
					$authentication = $DbHelper->authenticateUser($json->email, $json->password);

					if (!$authentication['error'])
					{
						$userInfo = Utils::getUserAgentDetails();
						$userBrowser = $userInfo['name'] . " (" . $userInfo['version'] . ")";
						$DbHelper->logUserLogin($authentication['user_email'], Utils::getUserIP(), $userBrowser, $userInfo['platform']);

						$data = array(
							'user_email' => $authentication['user_email'],
							'user_nickname' => $authentication['user_nickname'],
							'user_join_date' => $authentication['user_join_date'],
						);

						$jwt_token = ApiController::getInstance()->generateJWT($data);

						$refresh_token = $DbHelper->getRefreshTokenById($authentication['user_id']);
						if (!$refresh_token)
						{
							$refresh_token = $DbHelper->generateRefreshToken();
							$DbHelper->setRefreshToken($authentication['user_id'], $authentication['user_email'], $authentication['user_nickname'], $refresh_token);
						}

						$ApiController = new ApiController();
						$ApiController->getInstance()->setRefreshTokenCookie($refresh_token);

						echo json_encode(array(
							"message" => "Login successful!",
							"token" => $jwt_token,
						));
					}
					else
					{
						echo Utils::returnHttpCode(401, $authentication['message']);
					}
				}
			}
			else
			{
				echo Utils::returnHttpCode(400, "Fill out all fields!");
			}
		}
		else
		{
			echo Utils::returnHttpCode(404);
		}
	}

	public function forgotPassword()
	{
		if (Config::getValue("SEND_MAIL"))
		{
			if (Controller::isJsonized())
			{
				$json = Controller::isJsonized();
				if (isset($json->email, $json->captcha) 
				&& !empty($json->email) 
				&& !empty($json->captcha))
				{
					if (!filter_var($json->email, FILTER_VALIDATE_EMAIL))
					{
						echo Utils::returnHttpCode(401, "Invalid email format!");
					}
					else if (!Utils::checkRECAPTCHA($json->captcha))
					{
						echo Utils::returnHttpCode(401, "Captcha mismatch!");
					}
					else
					{
						$DbHelper = new DatabaseHelper();

						if ($DbHelper->checkAccountExists($json->email))
						{
							$code = Utils::genRandomString(Config::getValue("RESET_PASSWORD_CODE_LENGTH"));
							$DbHelper->setUserCodeByEmail($json->email, $code);

							$data = array(
								'user_email' => $json->email,
								'code' => $code
							);
							
							$mail = new Mailer();
							echo $mail->sendMail($data, 'RESET') ? Utils::returnHttpCode(200, "Password reset successfully!") : Utils::returnHttpCode(500, "Password reset failed!");
						}
						else
						{
							echo Utils::returnHttpCode(401, "This email doesn't exist in our database!");
						}
					}
				}
				else
				{
					echo Utils::returnHttpCode(401);
				}
			}
			else
			{
				echo Utils::returnHttpCode(400);
			}
		}
		else
		{
			echo Utils::returnHttpCode(500, "Mail server is turned off! Please contact the webmaster.");
		}
	}

	public function refreshJWT()
	{
		if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
		{
			$DbHelper = new DatabaseHelper();
			$refreshTokenInfo = $DbHelper->getDetailsFromRefreshToken(Utils::sanitiseInput($_COOKIE['REFRESH_TOKEN']));

			if ($refreshTokenInfo['exists'])
			{
				$ApiController = new ApiController();
				$ApiController->getInstance()->setRefreshTokenCookie('');

				$data = array(
					'user_email' => $refreshTokenInfo['user_email'],
					'user_nickname' => $refreshTokenInfo['user_nickname'],
					'user_join_date' => $refreshTokenInfo['user_join_date'],
				);
				$jwt_token = ApiController::getInstance()->generateJWT($data);

				$refresh_token = $DbHelper->generateRefreshToken();
				$ApiController->getInstance()->setRefreshTokenCookie($refresh_token);

				$DbHelper->updateRefreshToken(Utils::sanitiseInput($_COOKIE['REFRESH_TOKEN']), $refresh_token);

				echo json_encode(array(
					"message" => "Success!",
					"token" => $jwt_token,
				));
			}
			else
			{
				echo Utils::returnHttpCode(403);
			}
		}
		else
		{
			echo Utils::returnHttpCode(400);
		}
	}

	public function changePassword()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			if (isset($json->old_password, $json->new_password, $json->confirm_new_password)
			&& !empty($json->old_password)
			&& !empty($json->new_password)
			&& !empty($json->confirm_new_password))
			{
				$ApiController = new ApiController();
				$token = Utils::getBearerToken();

				if ($token)
				{
					if ($ApiController->isVerifiedToken())
					{
						if ($json->new_password !== $json->confirm_new_password)
						{
							echo Utils::returnHttpCode(400, "Passwords don't match!");
						}
						elseif ($json->new_password === $json->old_password)
						{
							echo Utils::returnHttpCode(400, "New and old password can't be identical!");
						}
						elseif (strlen($json->new_password) < Config::getValue("MINIMUM_PASSWORD_LENGTH"))
						{
							echo Utils::returnHttpCode(400, "Password length is too short!");
						}
						elseif (strlen($json->new_password) > Config::getValue("MAXIMUM_PASSWORD_LENGTH"))
						{
							echo Utils::returnHttpCode(400, "Password length is too long!");
						}
						else
						{
							$DbHelper = new DatabaseHelper();
							$decoded = JWT::decode($token, Config::getValue("SERVER_SECRET"), array('HS256'));
							$oldPassPeppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->old_password, Config::getValue("PASSWORD_PEPPER"));

							if (!password_verify($oldPassPeppered, $DbHelper->getPasswordByEmail($decoded->data->user_email)))
							{
								echo Utils::returnHttpCode(400, "Old password is incorrect!");
							}
							else
							{
								$peppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->new_password, Config::getValue("PASSWORD_PEPPER"));
								$hash = password_hash($peppered, PASSWORD_DEFAULT);
								$DbHelper->changePassword($hash, $decoded->data->user_email);
								echo Utils::returnHttpCode(200, "Password Changed");
							}
						}
					}
				}
				else
				{
					echo Utils::returnHttpCode(401);
				}
			}
			else
			{
				echo Utils::returnHttpCode(400);
			}
		}
		else
		{
			echo Utils::returnHttpCode(403);
		}
	}

	public function confirmEmail()
	{
		if (Config::getValue("REQUIRE_EMAIL_CONFIRMATION"))
		{
			if (Controller::isJsonized())
			{
				$json = Controller::isJsonized();
				if (isset($json->code, $json->captcha) 
				&& !empty($json->code) 
				&& !empty($json->captcha))
				{
					if (!Utils::checkRECAPTCHA($json->captcha))
					{
						echo Utils::returnHttpCode(401, "Captcha mismatch!");
					}
					elseif (strlen($json->code) !== Config::getValue("ACTIVATION_CODE_LENGTH"))
					{
						echo Utils::returnHttpCode(401, "Activation code is in invalid format!");
					}
					else
					{
						$DbHelper = new DatabaseHelper();
						echo $DbHelper->activateAccount($json->code) ? Utils::returnHttpCode(200, "Account confirmed!") : Utils::returnHttpCode(401, "Invalid activation code!");
					}
				}
				else
				{
					echo Utils::returnHttpCode(401, "Fill out the captcha!");
				}
			}
			else
			{
				echo Utils::returnHttpCode(400);
			}
		}
		else
		{
			echo Utils::returnHttpCode(404);
		}
	}

	public function resetPassword()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			if (isset($json->code, $json->captcha, $json->new_password) 
			&& !empty($json->code) 
			&& !empty($json->new_password) 
			&& !empty($json->captcha))
			{
				if (!Utils::checkRECAPTCHA($json->captcha))
				{
					echo Utils::returnHttpCode(401, "Captcha mismatch!");
				}
				elseif (strlen($json->new_password) < Config::getValue("MINIMUM_PASSWORD_LENGTH"))
				{
					echo Utils::returnHttpCode(401, "Password must have more than " . Config::getValue("MINIMUM_PASSWORD_LENGTH") . " characters!");
				}
				elseif (strlen($json->new_password) > Config::getValue("MAXIMUM_PASSWORD_LENGTH"))
				{
					echo Utils::returnHttpCode(401, "Password cannot exceed " . Config::getValue("MAXIMUM_PASSWORD_LENGTH") . " characters!");
				}
				elseif (strlen($json->code) !== Config::getValue("RESET_PASSWORD_CODE_LENGTH"))
				{
					echo Utils::returnHttpCode(401, "Password reset code is in invalid format!");
				}
				else
				{
					$DbHelper = new DatabaseHelper();
					$user_details = $DbHelper->getEmailAndPasswordByUserCode($json->code);
					$oldPassPeppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->new_password, Config::getValue("PASSWORD_PEPPER"));

					if ($user_details)
					{
						if (password_verify($oldPassPeppered, $user_details['old_password']))
						{
							echo Utils::returnHttpCode(400, "New password can't be the same as old password!");
						}
						else
						{
							$peppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->new_password, Config::getValue("PASSWORD_PEPPER"));
							$hash = password_hash($peppered, PASSWORD_DEFAULT);
							$DbHelper->changePassword($hash, $user_details['user_email']);
							$DbHelper->setUserCodeByEmail($user_details['user_email'], "DONE");
							echo Utils::returnHttpCode(200, "Password reset successful!");
						}
					}
					else
					{
						echo Utils::returnHttpCode(404);
					}
				}
			}
			else
			{
				echo Utils::returnHttpCode(401, "Fill out the captcha!");
			}
		}
		else
		{
			echo Utils::returnHttpCode(400);
		}
	}

	public function deleteAccount()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			if (isset($json->password) && !empty($json->password))
			{
				$ApiController = new ApiController();
				$token = Utils::getBearerToken();

				if ($token)
				{
					if ($ApiController->isVerifiedToken())
					{
						if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
						{
							$DbHelper = new DatabaseHelper();
							$decoded = JWT::decode($token, Config::getValue("SERVER_SECRET"), array('HS256'));
							$pepperedPass = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->password, Config::getValue("PASSWORD_PEPPER"));

							if ($DbHelper->checkRefreshToken(Utils::sanitiseInput($_COOKIE['REFRESH_TOKEN'])) && strcmp($DbHelper->getEmailByRefreshToken(Utils::sanitiseInput($_COOKIE['REFRESH_TOKEN'])), $decoded->data->user_email) == 0)
							{
								if (!password_verify($pepperedPass, $DbHelper->getPasswordByEmail($decoded->data->user_email)))
								{
									echo Utils::returnHttpCode(400, "Password is incorrect!");
								}
								else
								{
									Utils::deletePastesOnFileSystem($DbHelper->getALlPasteUidsOwnedByEmail($decoded->data->user_email));
									$DbHelper->deleteEverythingByEmail($decoded->data->user_email);

									echo Utils::returnHttpCode(200, "Account Deleted!");
								}
							}
							else
							{
								echo Utils::returnHttpCode(401);
							}
						}
						else
						{
							echo Utils::returnHttpCode(401);
						}
					}
				}
				else
				{
					echo Utils::returnHttpCode(401);
				}
			}
			else
			{
				echo Utils::returnHttpCode(400);
			}
		}
		else
		{
			echo Utils::returnHttpCode(403);
		}
	}

	public function doLogout()
	{
		if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
		{
			$DbHelper = new DatabaseHelper();
			$ApiController = new ApiController();

			if ($DbHelper->checkRefreshToken(Utils::sanitiseInput($_COOKIE['REFRESH_TOKEN'])))
			{
				$ApiController->getInstance()->setRefreshTokenCookie('');
				$DbHelper->deleteRefreshToken(Utils::sanitiseInput($_COOKIE['REFRESH_TOKEN']));
			}

			echo Utils::returnHttpCode(200, "Logout successful!");
		}
		else
		{
			echo Utils::returnHttpCode(403);
		}
	}
}