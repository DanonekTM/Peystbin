<?php

if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

class Login
{
	private $db_connection = null;
	public $errors = array();
	
	public function __construct()
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		
		if (isset($_POST["login"], $_POST["token"], $_POST["captcha"]))
		{
			$CSRF = new CSRF();
			$token = $_POST["token"];
			
			if ($CSRF->Check($token))
			{
				$this->loginWithPostData();
			}
			else
			{
				$this->errors[] = "Request failed, please try again.";
			}
		}
	}

	private function loginWithPostData()
	{
		if (empty($_POST['user_username']) || empty($_POST['user_password']) || empty($_POST['captcha']))
		{
			$this->errors[] = "All fields are required!";
		}	
		elseif (empty($_POST['user_username']))
		{
			$this->errors[] = "Username field was empty.";
		}
		elseif (empty($_POST['user_password']))
		{
			$this->errors[] = "Password field was empty.";
		}
		elseif (empty($_POST['captcha']))
		{
			$this->errors[] = "Captcha field was empty.";
		}
		elseif ($_POST['captcha'] != $_SESSION['digit'])
		{
			$this->errors[] = "Wrong captcha code.";
		}
		elseif (!empty($_POST['user_username']) && !empty($_POST['user_password']) && !empty($_POST['captcha']))
		{
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if (!$this->db_connection->set_charset("utf8"))
			{
				$this->errors[] = $this->db_connection->error;
			}

			if (!$this->db_connection->connect_error)
			{
				$credential = $this->db_connection->real_escape_string($_POST['user_username']);
				$query = $this->db_connection->prepare("SELECT user_id, user_username, user_password, user_email, user_title, user_priv_limit, user_join_date FROM users WHERE user_username = ? OR user_email = ?;");
				$query->bind_param('ss', $credential, $credential);
				$query->execute();
				$result_of_login_check = $query->get_result();
				
				if ($result_of_login_check->num_rows == 1)
				{
					$result_row = $result_of_login_check->fetch_object();

					if (password_verify($_POST['user_password'], $result_row->user_password))
					{
						$_SESSION['user_id'] = $result_row->user_id;
						$_SESSION['user_username'] = $result_row->user_username;
						$_SESSION['user_email'] = $result_row->user_email;
						$_SESSION['user_title'] = $result_row->user_title;
						$_SESSION['user_priv_limit'] = $result_row->user_priv_limit;
						$_SESSION['user_join_date'] = $result_row->user_join_date;
						$_SESSION['user_login_status'] = 1;
						$_SESSION['session_started'] = true;
					}
					else
					{		
						$this->errors[] = "Wrong password or username. Try again.";
					}
				}
				else
				{
					$this->errors[] = "Wrong password or username. Try again.";
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
	}
		
	public function doLogout()
	{
		$_SESSION = array();
		session_destroy();
		$ConfigTools = new ConfigTools();
		
		if ($ConfigTools->config_item('rewrite_on'))
		{
			header("Location: /");
		}
		else
		{
			header("Location: index.php");
		}
		die();
	}
	
	public function isUserLoggedIn()
	{
		if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1)
		{						
			return true;
		}
		return false;
	}
}
