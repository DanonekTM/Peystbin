<?php

namespace Danonek\Kernel\Controllers;
use Danonek\Kernel\Tools\Configurator as Config;
use Danonek\Kernel\Tools\Utils;
use Danonek\Kernel\Providers\DatabaseHelper;
use Danonek\Kernel\Tools\Crypto as Crypto;

use Firebase\JWT\JWT;

class ApiController
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

	public function setRefreshTokenCookie($refresh_token)
	{
		$expires = $refresh_token === '' ? time() - Config::getValue('TOKEN_EXPIRE_TIME') : time() + (60 * 60 * 24 * Config::getValue("SESSION_DAYS_EXPIRY"));

		$arr_cookie_options = array(
			'path' => '/',
			'secure' => Utils::isHTTPS(),
			'httponly' => true,
			'expires' => $expires
		);
		
		setcookie('REFRESH_TOKEN', $refresh_token, $arr_cookie_options);
	}

	public function updatePaste()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();

			if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
			{
				if ($this->isVerifiedToken('NO_HTTP_RESPONSE'))
				{
					$isset = isset($json->content) && isset($json->expiry) && isset($json->exposure);
					$empty = !empty($json->content) && !empty($json->expiry) && !empty($json->exposure);

					if ($isset && $empty)
					{
						if (strlen($json->content) > Config::getValue("PASTE_LIMIT_SIZE") * 1000)
						{
							echo Utils::returnHttpCode(400, "Content exceeds the limit of " . Utils::formatBytes(Config::getValue("PASTE_LIMIT_SIZE") * 1000) . '!');
						}
						else if (isset($json->title) && !empty($json->title) && strlen(Utils::sanitiseInput($json->title)) > Config::getValue("MAXIMUM_PASTE_TITLE_LENGTH"))
						{
							echo Utils::returnHttpCode(400, "Title cannot be longer than " . Config::getValue("MAXIMUM_PASTE_TITLE_LENGTH") . " characters.");
						}
						elseif (!preg_match('/[A-Za-z0-9_-]{13}/', $json->paste_uid))
						{
							echo Utils::returnHttpCode(400);
						}
						else
						{
							$token = JWT::decode(Utils::getBearerToken(), Config::getValue("SERVER_SECRET"), array('HS256'));
							$DbHelper = new DatabaseHelper();
							$paste = $DbHelper->getPasteByUid($json->paste_uid);

							$data = [
								'paste_owner_email' => '',
								'paste_owner_nickname' => 'A Guest',
								'paste_title' => Utils::sanitiseInput(empty($json->title) ? "Untitled" : $json->title),
								'paste_created' => time(),
								'paste_expiry' => Utils::expiryTagToSeconds($json->expiry, true),
								'paste_exposure' => Utils::formatExposure($json->exposure, true),
								'paste_password' => '',
								'paste_syntax_highlighting' => isset($json->syntax) && !empty($json->syntax) ? 1 : 0,
								'paste_burn_code' => null
							];
							
							$data['paste_expiry'] = $data['paste_expiry'] === null ? $paste['paste_expiry'] : $data['paste_expiry'];
							$data['paste_exposure'] = $data['paste_exposure'] === null ? $paste['paste_exposure'] : $data['paste_exposure'];

							if ($data['paste_expiry'] === -2)
							{
								$burn_code = Utils::genRandomString(Config::getValue("PASTE_BURN_CODE_LENGTH"));
								$arr_cookie_options = array(
									'path' => '/',
									'secure' => Utils::isHTTPS(),
									'httponly' => true,
									'expires' => time() + (Config::getValue("TOKEN_EXPIRE_TIME") / 3),
								);
								setcookie('BURN_CODE', $burn_code, $arr_cookie_options);
								$data['paste_burn_code'] = $burn_code;
							}

							if ($this->isVerifiedToken('NO_HTTP_RESPONSE')) 
							{
								if (!isset($json->guest) && empty($json->guest))
								{
									$token = JWT::decode(Utils::getBearerToken(), Config::getValue("SERVER_SECRET"), array('HS256'));
									$data['paste_owner_email'] = $token->data->user_email;
									$data['paste_owner_nickname'] = $token->data->user_nickname;
								}
								
								$content = $json->content;
								if (isset($json->password) && !empty($json->password))
								{
									$peppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->password, Config::getValue("PASSWORD_PEPPER"));
									$data['paste_password'] = password_hash($peppered, PASSWORD_DEFAULT);
									$content = Crypto::encrypt($json->content, base64_encode($json->password));
								}
								$paste = $DbHelper->updatePasteByUid($json->paste_uid, $data);
								
								if ($paste)
								{
									file_put_contents(PASTES_DIR . $paste . '.dat', $content);
									echo Utils::returnHttpCode(200, "Paste updated successfully!");
								}
								else
								{
									echo Utils::returnHttpCode(500);
								}
							}
							else
							{
								echo Utils::returnHttpCode(401, "Refresh your token.");
							}
						}
					}
					else
					{
						echo Utils::returnHttpCode(400, "Fill out all required fields!");
					}
				}
				else
				{
					echo Utils::returnHttpCode(401, "Refresh your token.");
				}
			}
			else
			{
				echo Utils::returnHttpCode(404);
			}
		}
		else
		{
			echo Utils::returnHttpCode(400);
		}
	}
	
	public function addPaste() 
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			$isset = isset($json->content) && isset($json->expiry) && isset($json->exposure);
			$empty = !empty($json->content) && !empty($json->expiry) && !empty($json->exposure);

			if ($isset && $empty)
			{
				if (strlen($json->content) > Config::getValue("PASTE_LIMIT_SIZE") * 1000)
				{
					echo Utils::returnHttpCode(400, "Content exceeds the limit of " . Utils::formatBytes(Config::getValue("PASTE_LIMIT_SIZE") * 1000) . '!');
				}
				else if (isset($json->title) && !empty($json->title) && strlen(Utils::sanitiseInput($json->title)) > Config::getValue("MAXIMUM_PASTE_TITLE_LENGTH"))
				{
					echo Utils::returnHttpCode(400, "Title cannot be longer than " . Config::getValue("MAXIMUM_PASTE_TITLE_LENGTH") . " characters.");
				}
				else
				{
					$data = [
						'owner_email' => '',
						'owner_nickname' => 'A Guest',
						'title' => Utils::sanitiseInput(empty($json->title) ? "Untitled" : $json->title),
						'created' => time(),
						'expiry' => Utils::expiryTagToSeconds($json->expiry),
						'exposure' => Utils::formatExposure($json->exposure),
						'password' => '',
						'syntax_highlighting' => isset($json->syntax) && !empty($json->syntax) ? 1 : 0,
						'paste_burn_code' => null
					];

					if ($data['expiry'] === -2)
					{
						$burn_code = Utils::genRandomString(Config::getValue("PASTE_BURN_CODE_LENGTH"));
						$arr_cookie_options = array(
							'path' => '/',
							'secure' => Utils::isHTTPS(),
							'httponly' => true,
							'expires' => time() + (Config::getValue("TOKEN_EXPIRE_TIME") / 3),
						);
						setcookie('BURN_CODE', $burn_code, $arr_cookie_options);
						$data['paste_burn_code'] = $burn_code;
					}

					if ($this->isVerifiedToken('NO_HTTP_RESPONSE') === null)
					{
						self::addingPaste($json, $data);
					}
					elseif ($this->isVerifiedToken('NO_HTTP_RESPONSE')) 
					{
						if (!isset($json->guest) && empty($json->guest))
						{
							$token = JWT::decode(Utils::getBearerToken(), Config::getValue("SERVER_SECRET"), array('HS256'));
							$data['owner_email'] = $token->data->user_email;
							$data['owner_nickname'] = $token->data->user_nickname;
						}
						self::addingPaste($json, $data);
					}
					else
					{
						echo Utils::returnHttpCode(401, "Refresh your token.");
					}
				}
			}
			else
			{
				echo Utils::returnHttpCode(400, "Fill out all required fields!");
			}
		}
		else
		{
			echo Utils::returnHttpCode(400);
		}
	}

	private function addingPaste($json, $data)
	{
		$content = $json->content;
		if (isset($json->password) && !empty($json->password))
		{
			$peppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->password, Config::getValue("PASSWORD_PEPPER"));
			$data['password'] = password_hash($peppered, PASSWORD_DEFAULT);
			$content = Crypto::encrypt($json->content, base64_encode($json->password));
		}

		$DbHelper = new DatabaseHelper();
		$paste = $DbHelper->createNewPaste($data);

		if ($paste)
		{
			file_put_contents(PASTES_DIR . $paste . '.dat', $content);

			$response = array(
				'error' => false,
				'paste_uid' => $paste
			);
	
			http_response_code(200);
			echo json_encode($response);
		}
		else
		{
			echo Utils::returnHttpCode(500);
		}
	}

	public function getPasteMetadata()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			if (isset($json->paste_uid) && !empty($json->paste_uid))
			{
				if (preg_match('/[A-Za-z0-9_-]{13}/', $json->paste_uid))
				{
					$DbHelper = new DatabaseHelper();
					$paste = $DbHelper->getPasteByUid($json->paste_uid);
					if ($paste)
					{
						if (strcmp($paste['paste_expiry'], -2) !== 0 && $DbHelper->updateOrDeletePasteExpiry($json->paste_uid, Utils::getBurnCode()))
						{
							Utils::deletePastesOnFileSystem($json->paste_uid);
							echo Utils::returnHttpCode(404);
						}
						else
						{
							if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
							{
								if ($this->isVerifiedToken('NO_HTTP_RESPONSE'))
								{
									$token = JWT::decode(Utils::getBearerToken(), Config::getValue("SERVER_SECRET"), array('HS256'));

									if (strcmp($paste['paste_owner_email'], $token->data->user_email) === 0)
									{
										self::showPaste($paste, $json, isset($paste['paste_password']) && !empty($paste['paste_password']) ? true : false, $DbHelper, true);
									}
									else
									{
										echo Utils::returnHttpCode(404);
									}
								}
								else
								{
									echo Utils::returnHttpCode(401);
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
						echo Utils::returnHttpCode(404, "Paste not found!");
					}
				}
				else
				{
					echo Utils::returnHttpCode(400);
				}
			}
			else
			{
				echo Utils::returnHttpCode(400);
			}
		}
		else
		{
			echo Utils::returnHttpCode(400);
		}
	}

	public function getPaste()
	{
		if (Controller::isJsonized())
		{
			$json = Controller::isJsonized();
			if (isset($json->paste_uid) && !empty($json->paste_uid))
			{
				if (preg_match('/[A-Za-z0-9_-]{13}/', $json->paste_uid))
				{
					$DbHelper = new DatabaseHelper();
					$paste = $DbHelper->getPasteByUid($json->paste_uid);
					if ($paste)
					{
						if (strcmp($paste['paste_expiry'], -2) !== 0 && $DbHelper->updateOrDeletePasteExpiry($json->paste_uid, Utils::getBurnCode()))
						{
							Utils::deletePastesOnFileSystem($json->paste_uid);
							echo Utils::returnHttpCode(404);
						}
						else if (strcmp($paste['paste_exposure'], "PRIVATE") === 0)
						{
							if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
							{
								if (strcmp($paste['paste_owner_email'], $DbHelper->getEmailByRefreshToken(Utils::sanitiseInput($_COOKIE['REFRESH_TOKEN']))) === 0)
								{
									self::showPaste($paste, $json, isset($paste['paste_password']) && !empty($paste['paste_password']) ? true : false, $DbHelper);
								}
								else
								{
									echo Utils::returnHttpCode(403);
								}
							}
							else
							{
								echo Utils::returnHttpCode(404);
							}
						}
						else
						{
							self::showPaste($paste, $json, isset($paste['paste_password']) && !empty($paste['paste_password']) ? true : false, $DbHelper);
						}
					}
					else
					{
						echo Utils::returnHttpCode(404, "Paste not found!");
					}
				}
				else
				{
					echo Utils::returnHttpCode(405);
				}
			}
			else
			{
				echo Utils::returnHttpCode(400);
			}
		}
		else
		{
			echo Utils::returnHttpCode(400);
		}
	}

	private function burnPaste($paste, $json, $DbHelper)
	{
		if (strcmp($paste['paste_expiry'], -2) === 0)
		{
			if ($DbHelper->updateOrDeletePasteExpiry($json->paste_uid, Utils::getBurnCode()))
			{
				Utils::deletePastesOnFileSystem($json->paste_uid);
			}
			if (isset($_COOKIE['BURN_CODE'])) Utils::deleteCookie('BURN_CODE');
		}
	}

	private function showPaste($paste, $json, $hasPassword, $DbHelper, $gettingMeta = false)
	{
		if ($hasPassword)
		{
			if (isset($json->password) && !empty($json->password))
			{
				$peppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $json->password, Config::getValue("PASSWORD_PEPPER"));
	
				if (password_verify($peppered, $paste['paste_password']))
				{
					$DbHelper->incrementPasteViews($json->paste_uid, Utils::getUserIP());
					$paste_content = Crypto::decrypt(file_get_contents(PASTES_DIR . $json->paste_uid . '.dat'), base64_encode($json->password));
	
					$response = array(
						'content' => $paste_content,
						'owner_nickname' => empty($paste['paste_owner_nickname']) ? "A Guest" : $paste['paste_owner_nickname'],
						'title' => $paste['paste_title'],
						'created' => $paste['paste_created'],
						'expiry' => Utils::formatExpiry($paste['paste_expiry']),
						'views' => $paste['paste_views'],
						'syntax' => $paste['paste_syntax_highlighting'],
						'size' => Utils::formatBytes(strlen($paste_content))
					);
	
					http_response_code(200);
					echo json_encode($response);
					if (!$gettingMeta) self::burnPaste($paste, $json, $DbHelper);
				}
				else
				{
					echo Utils::returnHttpCode(403, "Wrong password!");
				}
			}
			else
			{
				echo Utils::returnHttpCode(403, "Please enter the password!");
			}
		}
		else
		{
			$DbHelper->incrementPasteViews($json->paste_uid, Utils::getUserIP());
			$paste_content = file_get_contents(PASTES_DIR . $json->paste_uid . '.dat');
		
			$response = array(
				'content' => $paste_content,
				'owner_nickname' => $paste['paste_owner_nickname'],
				'title' => Utils::sanitiseInput($paste['paste_title']),
				'created' => $paste['paste_created'],
				'expiry' => Utils::formatExpiry($paste['paste_expiry']),
				'views' => $paste['paste_views'],
				'syntax' => $paste['paste_syntax_highlighting'],
				'size' => Utils::formatBytes(strlen($paste_content))
			);
	
			http_response_code(200);
			echo json_encode($response);
			if (!$gettingMeta) self::burnPaste($paste, $json, $DbHelper);
		}
	}

	public function getMyPastes()
	{
		if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
		{
			if ($this->isVerifiedToken('NO_HTTP_RESPONSE'))
			{
				$token = JWT::decode(Utils::getBearerToken(), Config::getValue("SERVER_SECRET"), array('HS256'));

				$DbHelper = new DatabaseHelper();
				$all_pastes = $DbHelper->getALlPastesOwnedByEmail($token->data->user_email);
			
				if ($all_pastes)
				{
					$pastes = [];
					foreach ($all_pastes as $paste)
					{
						$paste_info = [
							"title" => $paste['paste_title'],
							"created" => $paste['paste_created'],
							"uid" => $paste['paste_uid'],
							"views" => $paste['paste_views'],
							"exposure" => $paste['paste_exposure'],
							"expiry" => Utils::formatExpiry($paste['paste_expiry']),
						];
						array_push($pastes, $paste_info);
					}

					http_response_code(200);
					echo json_encode($pastes);
				}
				else
				{
					http_response_code(200);
					echo json_encode([]);
				}
			}
			else
			{
				echo Utils::returnHttpCode(401);
			}
		}
		else
		{
			echo Utils::returnHttpCode(404);
		}
	}

	public function deletePaste()
	{
		if (Controller::isJsonized())
		{
			if (isset($_COOKIE['REFRESH_TOKEN']) && !empty($_COOKIE['REFRESH_TOKEN']))
			{
				$json = Controller::isJsonized();
				
				if (isset($json->paste_uid) && !empty($json->paste_uid))
				{
					if (preg_match('/[A-Za-z0-9_-]{13}/', Utils::sanitiseInput($json->paste_uid)))
					{
						if ($this->isVerifiedToken('NO_HTTP_RESPONSE'))
						{
							$token = JWT::decode(Utils::getBearerToken(), Config::getValue("SERVER_SECRET"), array('HS256'));
							$DbHelper = new DatabaseHelper();
							$paste_owner = $DbHelper->getPasteOwnerByPasteUid(Utils::sanitiseInput($json->paste_uid));

							if ($paste_owner !== null && strcmp($paste_owner, $token->data->user_email) === 0)
							{
								$DbHelper->deletePasteByUid(Utils::sanitiseInput($json->paste_uid));
								Utils::deletePastesOnFileSystem(Utils::sanitiseInput($json->paste_uid));
								echo Utils::returnHttpCode(200, "Deleted successfully!");
							}
							else
							{
								echo Utils::returnHttpCode(403);
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
					echo Utils::returnHttpCode(400, "Paste UID format invalid!");
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

	public function generateJWT($data)
	{
		$issuedAt = time();
		$expire =  $issuedAt + Config::getValue('TOKEN_EXPIRE_TIME');
		
		$token = array(
			"iat" => $issuedAt,
			'iss' => Config::getValue('JWT_ISSUER'),
			"exp" => $expire,
			"data" => array(
				"user_nickname" => $data['user_nickname'],
				"user_email" => $data['user_email'],
				"user_join_date" => $data['user_join_date'],
			),
		);

		return JWT::encode($token, Config::getValue("SERVER_SECRET"));
	}

	public function isVerifiedToken($response = true)
	{
		$response = $response !== 'NO_HTTP_RESPONSE';
		$verified = true;
		switch($this->checkTokenState())
		{
			case "EXPIRED":
				$verified = !$verified;
				if ($response) echo Utils::returnHttpCode(401, "Expired Token!");
			break;

			case "ERROR":
				$verified = !$verified;
				if ($response) echo Utils::returnHttpCode(401);
			break;

			case "MALFORMED":
				$verified = !$verified;
				if ($response) echo Utils::returnHttpCode(403, "Malformed token!");
			break;

			case null:
				return null;
		}

		return $verified;
	}
	
	private function checkTokenState()
	{
		try 
		{
			$token = Utils::getBearerToken();
			if ($token === null) return null;
			return $token === "ERROR" ? "ERROR" : JWT::decode($token, Config::getValue("SERVER_SECRET"), array('HS256'));
		}
		catch (\Exception $e)
		{
			if ($e->getMessage() == "Expired token")
			{
				return "EXPIRED";
			}
		}
		
		return "MALFORMED";
	}
}
?>