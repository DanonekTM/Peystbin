<?php
require_once 'libraries/PHPMailer.php';
require_once 'libraries/Exception.php';
require_once 'libraries/SMTP.php';
require_once 'ConfigTools.php';

if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

class Mailer 
{
	public function sendMail($reason, $email_to, $subject, $message)
	{
		$ConfigTools = new ConfigTools();
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = $ConfigTools->config_item('mail_smtp_debug');
		$mail->SMTPAuth = $ConfigTools->config_item('mail_smtp_auth');
		$mail->SMTPSecure = $ConfigTools->config_item('mail_smtp_secure');
		$mail->Host = $ConfigTools->config_item('mail_host');
		$mail->Username = $ConfigTools->config_item('mail_username');
		$mail->Password = $ConfigTools->config_item('mail_password');
		$mail->Port = $ConfigTools->config_item('mail_port');
		$mail->IsHTML(true);
		
		if ($reason == 1)
		{
			$mail->SetFrom($ConfigTools->config_item('mail_from'), "Peystbin | Forgot Password");
		}
		else
		{
			$mail->SetFrom($ConfigTools->config_item('mail_from'), "Peystbin");
		}
		$mail->AddAddress($email_to);
		
		$mail->Subject = $subject;
		$mail->Body = $message;
		
		if (!$mail->Send())
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		
		return $result;
	}
}
?>