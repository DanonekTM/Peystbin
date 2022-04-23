<?php

namespace Danonek\Kernel\Providers;
use Danonek\Kernel\Tools\Configurator as Config;
use Danonek\Kernel\Tools\Utils;

class DatabaseHelper
{
	private $db_connection = null;

	public function __construct()
	{
		if (!$this->db_connection) 
		{
			$this->db_connection = new \mysqli(Config::getValue('MYSQL_HOST') . ':' . Config::getValue('MYSQL_PORT'), Config::getValue('MYSQL_LOGIN'), Config::getValue('MYSQL_PASSWORD'));

			if ($this->db_connection->connect_error)
			{
				die(Utils::returnHttpCode(503, "Database connection failed."));
			}

			if (!$this->db_connection->set_charset("utf8"))
			{
				die(Utils::returnHttpCode(503, "Error loading character set."));
			}
		}
		else
		{
			die(Utils::returnHttpCode(503));
		}
	}

	public function __destruct()
	{
		mysqli_close($this->db_connection);
	}

	public function authenticateUser($email, $password) : array
	{
		$query = $this->db_connection->prepare("SELECT user_id, user_nickname, user_password, user_status, user_join_date FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".users WHERE user_email = ?");
		$email_escaped = $this->db_connection->real_escape_string($email);
		$query->bind_param('s', $email_escaped);
		$query->execute();
		$result_of_login_check = $query->get_result();

		$peppered = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $password, Config::getValue("PASSWORD_PEPPER"));
		
		if ($result_of_login_check->num_rows == 1)
		{
			$result_row = $result_of_login_check->fetch_object();

			if ($result_row->user_status !== "OK")
			{
				$dbResult = array('error' => true, 'message' => "Account unconfirmed, in password change or banned.");
			}
			else if (password_verify($peppered, $result_row->user_password))
			{
				$dbResult = [
					'error' => false, 
					'message' => "Authorized", 
					'user_id' => $result_row->user_id, 
					'user_nickname' => $result_row->user_nickname, 
					'user_email' => $email,
					'user_join_date' => $result_row->user_join_date
				];
			}
			else
			{
				$dbResult = array('error' => true, 'message' => "Incorrect password!");
			}
		}
		else
		{
			$dbResult = array('error' => true, 'message' => "Wrong credentials!");
		}
		return $dbResult;
	}

	public function getPasswordByEmail($user_email) 
	{
		$query = $this->db_connection->prepare("SELECT user_password FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".users WHERE user_email = ?;");
		$query->bind_param('s', $user_email);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			return $result_row->user_password;
		}
		return null;
	}

	public function getEmailByRefreshToken($refreshToken) 
	{
		$query = $this->db_connection->prepare("SELECT user_email FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions WHERE refresh_token = ?;");
		$query->bind_param('s', $refreshToken);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			return $result_row->user_email;
		}
		return null;
	}
	
	public function updateOrDeletePasteExpiry($pasteUid, $burn_code)
	{
		$query = $this->db_connection->prepare("SELECT * FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_uid = ?;");
		$query->bind_param('s', $pasteUid);
		$query->execute();
		$result = $query->get_result();

		$result = $result->fetch_assoc();
		if ($result['paste_expiry'] < time() and $result['paste_expiry'] >= 0) 
		{
			$query = $query = $this->db_connection->prepare("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_uid = ?;");
			$query->bind_param('s', $pasteUid);
			$query->execute();
			return true;
		}
		elseif ($result['paste_expiry'] == -2 && !$this->isCorrectBurnCode($pasteUid, $burn_code))
		{
			$query = $query = $this->db_connection->prepare("UPDATE " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes SET paste_expiry = 0 WHERE paste_uid = ?;");
			$query->bind_param('s', $pasteUid);
			$query->execute();
			return true;
		}

		return false;
	}

	public function getPasteOwnerByPasteUid($pasteUid) : ?string
	{
		$query = $this->db_connection->prepare("SELECT paste_owner_email FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_uid = ?;");
		$query->bind_param('s', $pasteUid);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			return $result_row->paste_owner_email;
		}
		return null;
	}

	public function deletePasteByUid($pasteUid) : void
	{
		$query = $query = $this->db_connection->prepare("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_uid = ?;");
		$query->bind_param('s', $pasteUid);
		$query->execute();
	}

	public function isCorrectBurnCode($pasteUid, $burn_code) : bool
	{
		if (strlen($burn_code) !== Config::getValue("PASTE_BURN_CODE_LENGTH")) return false;
		$query = $this->db_connection->prepare("SELECT paste_burn_code FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_uid = ?;");
		$query->bind_param('s', $pasteUid);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			return strcmp($result_row->paste_burn_code, $burn_code) === 0;
		}
		return false;
	}

	public function getRecentPastes() 
	{
		$query = $this->db_connection->prepare("SELECT paste_owner_nickname, paste_title, paste_created, paste_uid, paste_views FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_exposure = 'PUBLIC' AND paste_expiry != '-2' ORDER BY paste_created ASC LIMIT 25;");
		$query->execute();
		$result_query = $query->get_result();
		
		return ($result_query->num_rows > 0) ? $result_query : null;
	}
	
	private function getUniquePasteUid()
	{
		$query = $this->db_connection->prepare("SELECT paste_uid FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_uid = ?;");	

		do 
		{
			$uniqid = Utils::genRandomString(13);
			$query->bind_param('s', $uniqid);
			$query->execute();
			$result = $query->get_result();
		} while ($result->num_rows != 0);

		return $uniqid;
	}

	function createNewPaste($data)
	{
		$uniqid = $this->getUniquePasteUid();

		$query = $this->db_connection->prepare("INSERT INTO " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes (paste_uid, paste_owner_email, paste_owner_nickname, paste_title, paste_created, paste_expiry, paste_exposure, paste_password, paste_syntax_highlighting, paste_burn_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
		$query->bind_param('ssssisssis', $uniqid, $data['owner_email'], $data['owner_nickname'], $data['title'], $data['created'], $data['expiry'], $data['exposure'], $data['password'], $data['syntax_highlighting'], $data['paste_burn_code']);
		$query->execute();
		
		return $uniqid;
	}

	function updatePasteByUid($pasteUid, $data)
	{
		$query = $this->db_connection->prepare("UPDATE " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes SET paste_owner_email = ?, paste_owner_nickname = ?, paste_title = ?, paste_created = ?, paste_expiry = ?, paste_exposure = ?, paste_password = ?, paste_syntax_highlighting = ?, paste_burn_code = ? WHERE paste_uid = ?;");
		$query->bind_param('sssisssiss', $data['paste_owner_email'], $data['paste_owner_nickname'], $data['paste_title'], $data['paste_created'], $data['paste_expiry'], $data['paste_exposure'], $data['paste_password'], $data['paste_syntax_highlighting'], $data['paste_burn_code'], $pasteUid);
		$query->execute();

		return $pasteUid;
	}


	public function incrementPasteViews($paste_uid, $user_ip)
	{	
		if ($this->db_connection)
		{
			if ($this->isUniquePasteVisit($paste_uid, $user_ip))
			{
				$query = $this->db_connection->prepare("UPDATE " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes SET paste_views = paste_views + 1 WHERE paste_uid = ?");
				$query->bind_param('s', $paste_uid);
				$query->execute();
			}
		}
	}

	public function isUniquePasteVisit($paste_uid, $visitor_ip)
	{		
		$query = $this->db_connection->prepare("SELECT * FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".paste_visitors WHERE paste_uid = ? AND visitor_ip = ?");
		$query->bind_param('ss', $paste_uid, $visitor_ip);
		$query->execute();
		
		$resultSet = $query->get_result();
		
		if ($resultSet->num_rows != 1)
		{
			$query = $this->db_connection->prepare("INSERT INTO " . Config::getValue('MYSQL_DATABASE_NAME') . ".paste_visitors (paste_uid, visitor_ip) VALUES (?, ?)");
			$query->bind_param('ss', $paste_uid, $visitor_ip);
			$query->execute();
			return true;
		}
		return false;
	}

	public function getPasteByUid($paste_uid)
	{
		$query = $this->db_connection->prepare("SELECT paste_owner_email, paste_owner_nickname, paste_title, paste_created, paste_expiry, paste_exposure, paste_password, paste_views, paste_syntax_highlighting FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_uid = ?;");
		$query->bind_param('s', $paste_uid);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			$paste_info = [
				'paste_owner_email' => $result_row->paste_owner_email,
				'paste_owner_nickname' => $result_row->paste_owner_nickname,
				'paste_title' => $result_row->paste_title,
				'paste_created' => $result_row->paste_created,
				'paste_expiry' => $result_row->paste_expiry,
				'paste_exposure' => $result_row->paste_exposure,
				'paste_password' => $result_row->paste_password,
				'paste_views' => $result_row->paste_views,
				'paste_syntax_highlighting' => $result_row->paste_syntax_highlighting,
			];
			return $paste_info;
		}
		return null;
	}

	public function logUserLogin($user_email, $user_ip, $user_browser, $user_platform)
	{
		$query = $this->db_connection->prepare("INSERT INTO " . Config::getValue('MYSQL_DATABASE_NAME') . ".last_logins (user_email, ip_address, browser_agent, operating_system, timestamp) VALUES (?, ?, ?, ?, UNIX_TIMESTAMP());");
		$query->bind_param('ssss', $user_email, $user_ip, $user_browser, $user_platform);
		$query->execute();
	}

	public function getRefreshTokenById($user_id)
	{
		$query = $this->db_connection->prepare("SELECT refresh_token FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions WHERE user_id = ?;");
		$query->bind_param('s', $user_id);
		$query->execute();
		$result_query = $query->get_result();
		
		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			return $result_row->refresh_token;
		}
		return null;
	}

	public function checkAccountExists($email)
	{
		$query = $this->db_connection->prepare("SELECT user_email FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".users WHERE user_email = ?;");
		$query->bind_param('s', $email);
		$query->execute();
		$result_query = $query->get_result();

		return $result_query->num_rows == 1;
	}

	public function createAccount($account_details)
	{
		$status = Config::getValue("REQUIRE_EMAIL_CONFIRMATION") && Config::getValue("SEND_MAIL") ? "BLOCK" : "OK";
		$query = $this->db_connection->prepare("INSERT INTO " . Config::getValue('MYSQL_DATABASE_NAME') . ".users(user_nickname, user_password, user_email, user_join_date, user_status, user_code, user_api_key) VALUES (?, ?, ?, ?, ?, ?, ?);");

		$query->bind_param('sssssss', $account_details['user_nickname'], $account_details['user_password'], $account_details['user_email'], $account_details['user_join_date'], $status, $account_details['user_code'], $account_details['user_api_key']);
		$query->execute();
	}

	public function changePassword($new_password, $email)
	{
		$query = $this->db_connection->prepare("UPDATE " . Config::getValue('MYSQL_DATABASE_NAME') . ".users SET user_password = ? WHERE user_email = ?");
		$query->bind_param('ss', $new_password, $email);
		$query->execute();

	}

	public function setRefreshToken($user_id, $user_email, $user_nickname, $token)
	{
		$query = $this->db_connection->prepare("INSERT INTO " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions (user_id, user_email, user_nickname, refresh_token) VALUES (?, ?, ?, ?);");
		$query->bind_param('ssss', $user_id, $user_email, $user_nickname, $token);
		$query->execute();
	}

	public function activateAccount($code)
	{
		$query = $this->db_connection->prepare("SELECT user_id FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".users WHERE user_code = ?;");
		$query->bind_param('s', $code);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			$query = $this->db_connection->prepare("UPDATE " . Config::getValue('MYSQL_DATABASE_NAME') . ".users SET user_status = 'OK', user_code = 'DONE' WHERE user_id = ?");
			$query->bind_param('i', $result_row->user_id);
			$query->execute();
			return true;
		}
		return false;
	}
	
	public function checkRefreshToken($refresh_token)
	{
		$query = $this->db_connection->prepare("SELECT refresh_token FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions WHERE refresh_token = ?;");
		$query->bind_param('s', $refresh_token);
		$query->execute();
		$result_query = $query->get_result();
		
		return $result_query->num_rows > 0;
	}

	public function getAccountDetailsByUserId($user_id)
	{
		$query = $this->db_connection->prepare("SELECT user_email, user_nickname, user_join_date FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".users WHERE user_id = ?;");
		$query->bind_param('s', $user_id);
		$query->execute();
		$result_query = $query->get_result();
		
		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			return [ 'user_email' => $result_row->user_email, 'user_nickname' => $result_row->user_nickname, 'user_join_date' => $result_row->user_join_date ];
		}
		return null;
	}

	public function getDetailsFromRefreshToken($refresh_token)
	{
		$query = $this->db_connection->prepare("SELECT refresh_token, user_id FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions WHERE refresh_token = ?;");
		$query->bind_param('s', $refresh_token);
		$query->execute();
		$result_query = $query->get_result();

		$token_info = [
			'exists' => false,
			'user_id' => null,
			'user_email' => null,
			'user_nickname' => null,
			'user_join_date' => null,
		];

		
		if ($result_query->num_rows > 0)
		{
			$result_row = $result_query->fetch_object();
			$details = $this->getAccountDetailsByUserId($result_row->user_id);

			if ($details)
			{
				$token_info['exists'] = true;
				$token_info['user_id'] = $result_row->user_id;
				$token_info['user_email'] = $details['user_email'];
				$token_info['user_nickname'] = $details['user_nickname'];
				$token_info['user_join_date'] = $details['user_join_date'];

				return $token_info;
			}
		}
		return $token_info;
	}

	public function deleteEverythingByEmail($email)
	{
		$query = $this->db_connection->prepare("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".last_logins WHERE user_email = ?;");
		$query->bind_param('s', $email);
		$query->execute();

		$query = $this->db_connection->prepare("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_owner_emaiL = ?;");
		$query->bind_param('s', $email);
		$query->execute();

		$query = $this->db_connection->prepare("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions WHERE user_email = ?;");
		$query->bind_param('s', $email);
		$query->execute();

		$query = $this->db_connection->prepare("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".users WHERE user_email = ?;");
		$query->bind_param('s', $email);
		$query->execute();
	}

	public function getALlPasteUidsOwnedByEmail($email)
	{
		$query = $this->db_connection->prepare("SELECT paste_uid FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_owner_email = ?;");
		$query->bind_param('s', $email);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows > 0)
		{
			$pasteUids = [];
			while($row = $result_query->fetch_array())
			{
				array_push($pasteUids, $row['paste_uid']);
			}
			return $pasteUids;
		}
		return null;
	}

	public function getALlPastesOwnedByEmail($email)
	{
		$query = $this->db_connection->prepare("SELECT paste_title, paste_created, paste_uid, paste_views, paste_exposure, paste_expiry FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_owner_email = ? ORDER BY paste_created ASC;");
		$query->bind_param('s', $email);
		$query->execute();
		$result_query = $query->get_result();
		
		return ($result_query->num_rows > 0) ? $result_query : null;
	}

	public function getAllExpiredPastes()
	{
		$currentTime = time();
		$query = $this->db_connection->prepare("SELECT paste_uid FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_expiry < ? AND paste_expiry >= 0;");
		$query->bind_param('s', $currentTime);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows > 0)
		{
			$pasteUids = [];
			while($row = $result_query->fetch_array())
			{
				array_push($pasteUids, $row['paste_uid']);
			}
			return $pasteUids;
		}
		return null;
	}

	public function cronJob()
	{
		$currentTime = time();
		$this->db_connection->query("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes WHERE paste_expiry < $currentTime AND paste_expiry >= 0;");
		$this->db_connection->query("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".last_logins WHERE " . Config::getValue("PURGE_HISTORY_AFTER_DAYS") . " < FLOOR((UNIX_TIMESTAMP() - timestamp) / 86400)");
		$this->db_connection->query("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".paste_visitors WHERE paste_uid NOT IN (SELECT p.paste_uid FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".pastes p);");
	}

	public function updateRefreshToken($old, $new)
	{
		$query = $this->db_connection->prepare("UPDATE " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions SET refresh_token = ? WHERE refresh_token = ?");
		$query->bind_param('ss', $new, $old);
		$query->execute();
	}

	public function deleteRefreshToken($refresh_token)
	{
		$query = $this->db_connection->prepare("DELETE FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions WHERE refresh_token = ?;");
		$query->bind_param('s', $refresh_token);
		$query->execute();
	}

	public function setUserCodeByEmail($email, $code)
	{
		$status = $code === 'DONE' ? 'OK' : 'RESET';
		$query = $this->db_connection->prepare("UPDATE " . Config::getValue('MYSQL_DATABASE_NAME') . ".users SET user_code = ?, user_status = ? WHERE user_email = ?;");
		$query->bind_param('sss', $code, $status, $email);
		$query->execute();
	}

	public function getEmailAndPasswordByUserCode($code)
	{
		$query = $this->db_connection->prepare("SELECT user_email, user_password FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".users WHERE user_code = ?;");
		$query->bind_param('s', $code);
		$query->execute();
		$result_query = $query->get_result();

		if ($result_query->num_rows == 1)
		{
			$result_row = $result_query->fetch_object();
			return [ 'user_email' => $result_row->user_email, 'old_password' => $result_row->user_password]; 
		}
		return null;
	}

	public function generateRefreshToken()
	{
		$query = $this->db_connection->prepare("SELECT refresh_token FROM " . Config::getValue('MYSQL_DATABASE_NAME') . ".sessions WHERE refresh_token = ?;");	
		do
		{
			$uniqid = Utils::genRandomString();
			$query->bind_param('s', $uniqid);
			$query->execute();
			$result = $query->get_result();
		} while ($result->num_rows != 0);

		return $uniqid;
	}
}
?>