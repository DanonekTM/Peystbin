<?php
if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

class DatabaseFunctions
{
	public $rewrite_on;
	
	function cron()
	{
		$daysLogged = $this->getDaysLoggedIn();
		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$currentTime = time();
			$this->db_connection->query("DELETE FROM pastes WHERE deletion_date < $currentTime AND deletion_date >= 0;");
			$this->db_connection->query("DELETE FROM bans WHERE $currentTime > ban_period;");
			$this->db_connection->query("DELETE FROM paste_visitors WHERE paste_id NOT IN (SELECT p.paste_id FROM pastes p);");
			
			if ($daysLogged)
			{
				$deleteHistoryQuery = "DELETE FROM logins WHERE id = " . $daysLogged[0];
				
				for ($i = 1; $i < count($daysLogged); $i++)
				{
					$deleteHistoryQuery .= " OR id = " . $daysLogged[$i];
				}
				
				$this->db_connection->query($deleteHistoryQuery);
			}
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function remaining_time($timestamp)
	{
		if ($timestamp == -1)
		{
			return 'Never expires';
		}
		elseif ($timestamp == 0)
		{
			return 'Expired';
		}
		elseif ($timestamp == -2)
		{
			return 'Your eyes only';
		}

		$format = function($t, $s)
		{
			return $t ? $t.' '.$s.($t > 1 ? 's' : '' ).' ' : '';
		};

		$expiration = new DateTime('@'.$timestamp);
		$interval = $expiration->diff(new DateTime(), true);

		$ret = 'Expires in ' . $format($interval->d, 'day');
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
	
	public function getLoginHistory()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM logins WHERE user_id = ?;");
			$query->bind_param("i", $_SESSION['user_id']);
			$query->execute();
		
			$resultSet = $query->get_result();
			
			return $resultSet;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function getAllLoginHistory()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM logins;");
			$query->execute();
		
			$resultSet = $query->get_result();
			
			return $resultSet;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function getLoginHistoryByPage($start, $limit)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM logins WHERE user_id = ? ORDER BY id DESC LIMIT ?, ?;");
			$query->bind_param("iii", $_SESSION['user_id'], $start, $limit);
			$query->execute();
		
			$resultSet = $query->get_result();
			
			mysqli_close($this->db_connection);
			return $resultSet;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function getDaysLoggedIn()
	{
		$logins = $this->getAllLoginHistory();
		
		if ($logins)
		{
			$ids = array();
			
			foreach ($logins as $date)
			{
				$time = $date['login_date'];
				$str = explode(' ', $time);
				$today = new DateTime(date('d-m-Y'));
				$last_login = new DateTime($str[1]);
				$diff = $last_login->diff($today)->format("%a");
				
				if ($diff >= 20) // 20 Days
				{
					$ids[] = $date['id'];
				}
			}
			
			return $ids;
		}
		else
		{
			return null;
		}
	}
	
	public function databaseSession($status)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$ConfigTools = new ConfigTools();
			
			$user_id = $_SESSION['user_id'];
			$currentTime = date('H:i:s d-m-Y', time());
			$session_token = $_SESSION['token'];
			$default_logout_string = "Didn't logout yet.";
			$userAgent = $ConfigTools->getBrowser();
			$userAgent = $userAgent['name'] . " (" . $userAgent['version'] . ") on " . $userAgent['platform'];
			$ipAddress = $this->getUserIP();
	
			switch ($status)
			{
				case "start":
					$checkLogin = $this->db_connection->prepare("SELECT logout_date FROM logins WHERE session_token = ? AND user_id = ?;");
					$checkLogin->bind_param('ss', $session_token, $user_id);
					$checkLogin->execute();
					$row = $checkLogin->get_result()->fetch_assoc();
					
					if ($row['logout_date'] != $default_logout_string)
					{
						$query = $this->db_connection->prepare("INSERT into logins (user_id, login_date, browser_agent, ip_address, session_token) VALUES (?, ?, ?, ?, ?);");
						$query->bind_param('sssss', $user_id, $currentTime, $userAgent, $ipAddress, $session_token);
						$query->execute();
					}
				break;
				
				
				case "stop":
					$checkLogin = $this->db_connection->prepare("UPDATE logins set logout_date = ? WHERE session_token = ? AND user_id = ?;");
					$checkLogin->bind_param('sss', $currentTime, $session_token, $user_id);
					$checkLogin->execute();
				break;
			}
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function getPasteSize($paste_id)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT CHAR_LENGTH(paste) AS paste_size FROM pastes WHERE paste_id = ?;");
			$query->bind_param('s', $paste_id);
			$query->execute();
		
			$resultSet = $query->get_result()->fetch_assoc();
			
			mysqli_close($this->db_connection);
			return $resultSet;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	function formatBytes($size, $precision = 0)
	{
		$unit = ['Byte','KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];

		for($i = 0; $size >= 1024 && $i < count($unit) - 1; $i++)
		{
			$size /= 1024;
		}
		return round($size, $precision).' '.$unit[$i];
	}
	
	public function sendForgottenPasword($email, $username)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT user_id FROM users WHERE user_email = ? AND user_username = ?");
			$query->bind_param('ss', $email, $username);
			$query->execute();
			
			$resultSet = $query->get_result()->fetch_assoc();
			
			if ($resultSet)
			{
				mysqli_close($this->db_connection);
				
				require_once("Mailer.php");
				
				$Mailer = new Mailer();
				
				// TODO Create a page to enter digits and then pick a password?
				$message = "Hey " . $username . ". <br> Your new password is : <strong>" . $this->generateNewPassword($resultSet['user_id']) . "</strong><br> Change it quickly in your control panel!";
				return $Mailer->sendMail(1, $email, "Forgotten Password", $message);
			}
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
		return false;
	}
	
	public function generateNewPassword($user_id)
	{
		$chars = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array();
		$alphaLength = strlen($chars) - 1;
		for ($i = 0; $i < 8; $i++) 
		{
			$n = rand(0, $alphaLength);
			$pass[] = $chars[$n];
		}
		$password = implode($pass);
		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		$user_password_hash = password_hash($password, PASSWORD_DEFAULT);
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
			$query->bind_param('ss', $user_password_hash, $user_id);
			$query->execute();
			
			mysqli_close($this->db_connection);
			return $password; 
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function getArchivedPastes()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM pastes WHERE is_private != 1 AND deletion_date != 0 ORDER BY entry_id DESC LIMIT 30;");
			$query->execute();
		
			$resultSet = $query->get_result();
			
			mysqli_close($this->db_connection);
			return $resultSet;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function checkPasteIdExists($paste_id)
	{		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT entry_id FROM pastes WHERE paste_id = ?");
			$query->bind_param('s', $paste_id);
			$query->execute();
			
			$resultSet = $query->get_result();
			
			if ($resultSet->num_rows == 1)
			{
				mysqli_close($this->db_connection);
				return true;
			}
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
		return false;
	}
	
	public function isUniquePasteVisit($paste_id, $visitor_ip)
	{		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		$visitor_ip = $visitor_ip;
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM paste_visitors WHERE paste_id = ? AND visitor_ip= ?");
			$query->bind_param('ss', $paste_id, $visitor_ip);
			$query->execute();
			
			$resultSet = $query->get_result();
			
			if ($resultSet->num_rows == 1)
			{
				mysqli_close($this->db_connection);
				return false;
			}
			else
			{
				$query = $this->db_connection->prepare("INSERT INTO paste_visitors (paste_id, visitor_ip) VALUES (?, ?)");
				$query->bind_param('ss', $paste_id, $visitor_ip);
				$query->execute();
				
				mysqli_close($this->db_connection);
				return true;
			}
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
		return false;
	}
	
	public function incrementPasteViews($paste_id, $user_ip)
	{	
		if ($this->checkPasteIdExists($paste_id))
		{
			if ($this->isUniquePasteVisit($paste_id, $user_ip))
			{
				$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				
				if (!$this->db_connection->connect_error)
				{
					$query = $this->db_connection->prepare("UPDATE pastes SET views = views + 1 WHERE paste_id = ?");
					$query->bind_param('s', $paste_id);
					$query->execute();
					
					mysqli_close($this->db_connection);
					return; 
				}
				else
				{
					header('HTTP/1.1 503 Service Unavailable.', true, 503);
					echo 'Database connection problem.';
					exit(1);
				}
				mysqli_close($this->db_connection);
			}
		}
	}
	
	public function updatePaste($paste_id, $content, $highlighting, $wrap, $is_private)
	{	
		if ($this->checkPasteIdExists($paste_id))
		{
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if (!$this->db_connection->connect_error)
			{
				$query = $this->db_connection->prepare("UPDATE pastes SET paste = ?, highlighting = ?, wrap = ?, is_private = ? WHERE paste_id = ?");
				$query->bind_param('siiis', $content, $highlighting, $wrap, $is_private, $paste_id);
				$query->execute();
				
				mysqli_close($this->db_connection);
				
				if ($this->rewrite_on)
				{
					header('Location: /' . $paste_id);
				}
				else
				{
					header('Location: index.php?p=' . $paste_id);
				}
				die();
			}
			else
			{
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				echo 'Database connection problem.';
				exit(1);
			}
			mysqli_close($this->db_connection);
		}
	}
	
	public function checkPasteOwner($paste_id)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT user_id FROM pastes WHERE paste_id = ? AND user_id = ?");
			$query->bind_param('ss', $paste_id, $_SESSION['user_id']);
			$query->execute();
			
			$resultSet = $query->get_result();
			
			if ($resultSet->num_rows == 1)
			{
				mysqli_close($this->db_connection);
				return true;
			}
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
		return false;
	}
	
	public function deletePaste($paste_id)
	{	
		if ($this->checkPasteIdExists($paste_id) && $this->checkPasteOwner($paste_id))
		{
			if ($this->isPrivatePaste($paste_id))
			{
				$this->incrementPrivatePasteLimit();
			}
			
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			
			if (!$this->db_connection->connect_error)
			{
				$query = $this->db_connection->prepare("DELETE FROM pastes WHERE paste_id = ?");
				$query->bind_param('s', $paste_id);
				$query->execute();
				
				mysqli_close($this->db_connection);
			}
			else
			{
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				echo 'Database connection problem.';
				exit(1);
			}
			mysqli_close($this->db_connection);
		}
	}

	public function getUserIP() 
	{
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) 
		{
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP)) 
		{ 
			$ip = $client; 
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP)) 
		{ 
			$ip = $forward; 
		}
		else
		{
			$ip = $remote; 
		}

		return $ip;
	}
	
	public function isSamePass($oldPass, $newPass, $newPassRepeat)
	{
		$user_id = $_SESSION['user_id'];

		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT user_password from users WHERE user_id = ?");
			$query->bind_param('s', $user_id);
			$query->execute();
			
			$result = $query->get_result();

			$row = $result->fetch_assoc();
			
			if (strcmp(password_hash($oldPass, PASSWORD_DEFAULT), $row['user_password']))
			{
				if (strcmp($oldPass, $newPass) == 0)
				{
					return 1;
				}
				if (strlen($newPass) < 6)
				{
					return 5;
				}
				if (!strcmp($newPass, $newPassRepeat) == 0)
				{
					return 4;
				}
				$user_password_hash = password_hash($newPass, PASSWORD_DEFAULT);
			
				$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

				if (!$this->db_connection->connect_error)
				{
					$query = $this->db_connection->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
					$query->bind_param('ss', $user_password_hash, $user_id);
					$query->execute();
					return 2;
				}
			}
			else
			{
				return 3;
			}
		}
	}
	
	private function check_spammer() 
	{
		$ip = $this->getUserIP();
		
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db->connect_error)
		{
			$query = $this->db->prepare("SELECT * FROM bans WHERE ip = ?;");
			$query->bind_param('s', $ip);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();
				
			$in_period = (!empty($result) and time() <= $result['ban_period']);
			$obvious_spam = (!isset($_POST['danonek']) or !empty($_POST['danonek']));

			$degree = $in_period ? $result['degree'] + 1 : ($obvious_spam ? 512 : 1);
			$nopaste_period = $this->nopaste_period($degree);

			$query = $this->db->prepare("REPLACE INTO bans (ip, ban_period, degree) VALUES (?, ?, ?)");
			$query->bind_param('sss', $ip, $nopaste_period, $degree);
			$query->execute();

			if ($in_period or $obvious_spam)
			{
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				echo 'Spam.';
				exit(1);
			}
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db);
	}
	
	private function generate_id()
	{
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db->connect_error)
		{
			$query = $this->db->prepare("SELECT paste_id FROM pastes WHERE paste_id = ?;");	

			do 
			{
				$uniqid = uniqid();
				$query->bind_param('s', $uniqid);
				$query->execute();
				$result = $query->get_result();
			} while ($result->num_rows != 0);
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db);
		
		return $uniqid;
	}

	private function nopaste_period($degree) 
	{
		return time() + intval(pow($degree, 2.5));
	}
	
	function getPrivateLimit()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT user_priv_limit FROM users WHERE user_id = ?");
			$query->bind_param('s', $_SESSION['user_id']);
			$query->execute();
			
			$result = $query->get_result();
		
			mysqli_close($this->db_connection);
			return $result; 
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	function isPrivatePaste($paste_id)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT is_private FROM pastes WHERE paste_id = ?");
			$query->bind_param('s', $paste_id);
			$query->execute();
			
			$result = $query->get_result()->fetch_assoc();

			mysqli_close($this->db_connection);
			
			return $result['is_private'] ? true : false;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	function incrementPrivatePasteLimit()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("UPDATE users SET user_priv_limit = user_priv_limit + 1 WHERE user_id = ?");
			$query->bind_param('s', $_SESSION['user_id']);
			$query->execute();
			
			$_SESSION['user_priv_limit'] = $_SESSION['user_priv_limit'] + 1;
			mysqli_close($this->db_connection);
			return; 
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	function canPostPrivatePaste()
	{
		if (mysqli_fetch_assoc($this->getPrivateLimit())['user_priv_limit'] > 0)
		{
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			
			if (!$this->db_connection->connect_error)
			{
				$query = $this->db_connection->prepare("UPDATE users SET user_priv_limit = user_priv_limit - 1 WHERE user_id = ?");
				$query->bind_param('s', $_SESSION['user_id']);
				$query->execute();
			
				$_SESSION['user_priv_limit'] = $_SESSION['user_priv_limit'] - 1;
				mysqli_close($this->db_connection);
				return true; 
			}
			else
			{
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				echo 'Database connection problem.';
				exit(1);
			}
			mysqli_close($this->db_connection);
		}
		return false;
	}
	
	function add_paste($paste_title, $deletion_date, $content, $highlighting, $wrap, $guest, $is_private, $is_logged_in)
	{
		$this->check_spammer();
		
		switch ($deletion_date)
		{
			default:
				$deletion_date = 600;
			break;

			case "1D":
				$deletion_date = 86400;
			break;
			
			case "BURN":
				$deletion_date = -2;
			break;	

			case "10M":
				$deletion_date = 600;
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
			
			case "E":
				$deletion_date = -1;
			break;
			
		}
		
		$deletion_date = intval($deletion_date);

		if ($deletion_date > 0)
		{
			$deletion_date += time();
		}
		
		if (strlen(trim($paste_title)) <= 0)
		{
			$paste_title = "Untitled";
		}

		$uniqid = $this->generate_id();
		
		$added = date('d/m/Y', time());
		$username = $_SESSION['user_username'];
		$user_id = $_SESSION['user_id'];
		
		$ConfigTools = new ConfigTools();
		
		if ($_SESSION['user_title'] == $ConfigTools->config_item('user_premium_title'))
		{
			if (strlen($content) > 1024000)
			{
				$_SESSION['PASTE_SIZE_LIMIT'] = true;
				
				if ($this->rewrite_on)
				{
					header('Location: info');
				}
				else
				{
					header('Location: info.php');
				}
				
				mysqli_close($this->db);
				die();
			}
		}
		else
		{
			if (strlen($content) > 512000)
			{
				$_SESSION['PASTE_SIZE_LIMIT'] = true;
				
				if ($this->rewrite_on)
				{
					header('Location: info');
				}
				else
				{
					header('Location: info.php');
				}
				
				mysqli_close($this->db);
				die();
			}
		}
		
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db->connect_error)
		{
			if ($is_logged_in)
			{
				if ($is_private && !$this->canPostPrivatePaste())
				{
					$_SESSION['PASTE_LIMIT'] = true;
					
					if ($this->rewrite_on)
					{
						header('Location: info');
					}
					else
					{
						header('Location: info.php');
					}
					mysqli_close($this->db);
					return false;
				}
				else
				{
					$query = $this->db->prepare("INSERT INTO pastes (paste_id, paste_title, user_id, deletion_date, highlighting, wrap, paste, added, username, is_private, guest) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
					$query->bind_param('ssiiiisssii', $uniqid, $paste_title, $user_id, $deletion_date, $highlighting, $wrap, $content, $added, $username, $is_private, $guest);
					$query->execute();
				}
			}
			else
			{
				$query = $this->db->prepare("INSERT INTO pastes (paste_id, paste_title, deletion_date, highlighting, wrap, paste, added) VALUES (?, ?, ?, ?, ?, ?, ?);");
				$query->bind_param('ssiiiss', $uniqid, $paste_title, $deletion_date, $highlighting, $wrap, $content, $added);
				$query->execute();
			}
			
			$_SESSION['just_created'] = true;

			if ($this->rewrite_on)
			{
				header('Location: /' . $uniqid);
			}
			else
			{
				header('Location: index.php?p=' . $uniqid);
			}
			mysqli_close($this->db);
			die();
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db);
	}
	
	function show_paste($param, $just_created)
	{
		$id = str_replace("@raw", "", $param);
		$is_raw = intval(strtolower(substr($param, -4)) == "@raw");
		$highlighting = false;
		$wrap = false;

		$fail = false;
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db->connect_error)
		{
			$query = $this->db->prepare("SELECT * FROM pastes WHERE paste_id = ?;");
			$query->bind_param('s', $id);
			$query->execute();
			$result = $query->get_result();

			if ($result->num_rows != 1) 
			{
				$fail = true;
			}
			else
			{
				$result = $result->fetch_assoc();
				if ($result['deletion_date'] < time() and $result['deletion_date'] >= 0) 
				{
					$query = $this->db->prepare("DELETE FROM pastes WHERE paste_id = ?;");
					$query->bind_param('s', $id);
					$query->execute();

					if ($result['deletion_date'] != 0)
					{
						$fail = true;
					}
				}
				elseif ($result['deletion_date'] == -2 && !$just_created)
				{
					$query = $this->db->prepare("UPDATE pastes set deletion_date = 0 WHERE paste_id = ?;");
					$query->bind_param('s', $id);
					$query->execute();
				}
			}

			if ($fail)
			{
				mysqli_close($this->db);
				return null;
			}
			else 
			{
				if ($is_raw) 
				{
					header('Content-Type: text/plain; charset=utf-8');
					print $result['paste'];
					exit();
				}
				else
				{
					if ($result['highlighting']) 
					{
						$highlighting = array('onLoad' => '<script>window.onload=function(){prettyPrint();}</script>', 'includePrettify' => '<script src="/assets\js\textarea\prettify.js"></script>');
					}

					$class = 'prettyprint linenums';
					if ($result['wrap'])
					{
						$class .= " wrap";
					}
				}
			}
			mysqli_close($this->db);
			return array('dbResult' => $result, 'highlighting' => $highlighting, 'rewriteOn' => $this->rewrite_on, 'class' => $class);
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
	}
	
	public function getMyPastes()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM pastes WHERE user_id = ?;");
			$query->bind_param("i", $_SESSION['user_id']);
			$query->execute();
		
			$resultSet = $query->get_result();
			
			mysqli_close($this->db_connection);
			return $resultSet;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function getPastesByPage($start, $limit)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM pastes WHERE user_id = ? AND guest != 1 ORDER BY entry_id DESC LIMIT ?, ?;");
			$query->bind_param("iii", $_SESSION['user_id'], $start, $limit);
			$query->execute();
		
			$resultSet = $query->get_result();
			
			mysqli_close($this->db_connection);
			return $resultSet;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function deleteAccount($user_id)
	{	
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$this->db_connection->query("DELETE FROM users WHERE user_id = $user_id;");
			$this->db_connection->query("DELETE FROM pastes WHERE user_id = $user_id;");
			$this->db_connection->query("DELETE FROM logins WHERE user_id = $user_id;");
			
			mysqli_close($this->db);
			$Login = new Login();
			$Login->doLogout();
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function getValidUpgradeCode($code)
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_error)
		{
			$query = $this->db_connection->prepare("SELECT * FROM codes WHERE code = ?;");
			$query->bind_param('s', $code);
			$query->execute();
			$result = $query->get_result()->fetch_assoc();

			mysqli_close($this->db_connection);
			return $result;
		}
		else
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Database connection problem.';
			exit(1);
		}
		mysqli_close($this->db_connection);
	}
	
	public function upgradeAccount($code)
	{	
		$valid = $this->getValidUpgradeCode($code);
		if ($valid)
		{
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			
			if (!$this->db_connection->connect_error)
			{
				$code_id = $valid['id'];
				
				$ConfigTools = new ConfigTools();
				$title = $ConfigTools->config_item('user_premium_title');
				$user_private_limit = $ConfigTools->config_item('user_priv_limit');
			
				$query = $this->db_connection->prepare("UPDATE users SET user_title = ?, user_priv_limit = ? WHERE user_id = ?;");
				$query->bind_param('sss', $title, $user_private_limit, $_SESSION['user_id']);
				$query->execute();
				
				$this->db_connection->query("DELETE FROM codes WHERE id = $code_id;");
				
				mysqli_close($this->db_connection);
				return true;
			}
			else
			{
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				echo 'Database connection problem.';
				exit(1);
			}
			mysqli_close($this->db_connection);
		}
		else
		{
			return false;
		}
	}
}