<?php

if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

class Registration
{
	private $db_connection = null;
	public $rewrite_on;
	public $errors = array();
	public $messages = array();
	public function __construct()
	{	
		if (!isset($_SESSION))
		{
			session_start();
		}
	
		if (isset($_POST['register']))
		{
			$this->registerNewUser();
		}
	}

	private function registerNewUser()
	{	
		if (empty($_POST['username']) || empty ($_POST['email']) || empty($_POST['password']) || empty($_POST['password-repeat']) || empty($_POST['captcha']))
		{
			$this->errors[] = "All fields are required!";
		}		
		elseif ($_POST['password'] !== $_POST['password-repeat'])
		{
			$this->errors[] = "Passwords didn't match. Try again.";
		}	
		elseif ($_POST['password'] === $_POST['email'])
		{
			$this->errors[] = "Password can't be the same as the email address!";
		}
		elseif (!isset($_POST['tos']))
		{
			$this->errors[] = "You need to agree to our terms and conditions.";
		}
		elseif (empty($_POST['captcha']))
		{
			$this->errors[] = "Captcha field was empty.";
		}
		elseif ($_POST['captcha'] != $_SESSION['digit'])
		{
			$this->errors[] = "Wrong captcha code.";
		}
		elseif (strlen($_POST['password']) < 6)
		{
			$this->errors[] = "Password has a minimum length of 6 characters.";
		}		
		elseif (strlen($_POST['username']) > 64 || strlen($_POST['username']) < 2)
		{
			$this->errors[] = "Username cannot be shorter than 2 characters or longer than 64 characters.";
		}
		elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$this->errors[] = "Email address is not in a valid format.";
		}
		elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['username']))
		{
			$this->errors[] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.";
		}
		elseif (
			!empty($_POST['username'])
			&& !empty($_POST['email'])
			&& !empty($_POST['password'])
			&& strlen($_POST['username']) <= 64
			&& strlen($_POST['username']) >= 2
			&& preg_match('/^[a-z\d]{2,64}$/i', $_POST['username'])
			&& !empty($_POST['password'])
			&& !empty($_POST['password-repeat'])
			&& ($_POST['password'] === $_POST['password-repeat'])
			&& isset($_POST['tos'])
			&& ($_POST['password'] !== $_POST['email'])
		)
		{
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			if (!$this->db_connection->connect_error)
			{
				$user_username = $this->db_connection->real_escape_string(strip_tags($_POST['username'], ENT_QUOTES));
				$user_password = $_POST['password'];
				$join_date = date('d/m/Y');
				$user_email = strip_tags($_POST['email'], ENT_QUOTES);
				
				$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
				
				$query = $this->db_connection->prepare("SELECT * FROM users WHERE user_username = ? OR user_email = ?;");
				$query->bind_param('ss', $user_username, $user_email);
				$query->execute();
				$query_check_user_name = $query->get_result();

				if ($query_check_user_name->num_rows == 1)
				{
					$this->errors[] = "Sorry, that username or email is already taken.";
				}
				else
				{
					$query = $this->db_connection->prepare("INSERT INTO users (user_username, user_password, user_email, user_join_date) VALUES (?, ?, ?, ?);");
					$query->bind_param('ssss', $user_username, $user_password_hash, $user_email,  $join_date);
					$result = $query->execute();
					
					if ($result)
					{
						$_SESSION['REGISTRATION_SUCCESS'] = true;
						if ($this->rewrite_on)
						{
							header('Location: info');
						}
						else
						{
							header('Location: info.php');
						}
						mysqli_close($this->db_connection);
						die();
					}
					else 
					{
						$_SESSION['REGISTRATION_SUCCESS'] = false;	
						
						if ($this->rewrite_on)
						{
							header('Location: ./info');
						}
						else
						{
							header('Location: ./info.php');
						}
						mysqli_close($this->db_connection);
						die();
					}
				}
			}
			else
			{
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				echo 'Database connection problem.';
				exit(1);
			}
		}
		else 
		{
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'Unknown Error. Please try again.';
			exit(1);
		}
	}
}
