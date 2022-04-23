<?php

namespace Danonek\Kernel\Tools;

use Danonek\Kernel\Tools\Configurator as Config;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
	private $mailer = null;

	public function __construct()
	{
		$this->mailer = new PHPMailer();
		$this->mailer->IsSMTP();
		$this->mailer->SMTPDebug = Config::getValue('MAIL_SMTP_DEBUG');
		$this->mailer->SMTPAuth = Config::getValue('MAIL_SMTP_AUTH');
		$this->mailer->SMTPSecure = Config::getValue('MAIL_SMTP_SECURE');
		$this->mailer->Host = Config::getValue('MAIL_HOST');
		$this->mailer->Username = Config::getValue('MAIL_USERNAME');
		$this->mailer->Password = Config::getValue('MAIL_PASSWORD');
		$this->mailer->Port = Config::getValue('MAIL_PORT');
		$this->mailer->CharSet = Config::getValue('MAIL_CHARSET');
		$this->mailer->IsHTML(true);
		$this->mailer->SetFrom(Config::getValue('MAIL_USERNAME'));
	}

	public function sendMail($data, $template)
	{
		$this->mailer->AddAddress($data['user_email']);

		switch($template)
		{
			case 'RESET':
				$this->mailer->Subject = Config::getValue("SERVER_NAME") . " - Reset Password";
				$template = file_get_contents(EMAIL_TEMPLATE_DIR . "reset_password.html");
				$template = str_replace("{{LINK}}", (Config::getValue("BASE_URL") . "reset-password/" . $data['code']), $template);
			break;

			case 'CONFIRM':
				$this->mailer->Subject = Config::getValue("SERVER_NAME") . " - Confirm your account";
				$template = file_get_contents(EMAIL_TEMPLATE_DIR . "account_confirmation.html");
				$template = str_replace("{{LINK}}", (Config::getValue("BASE_URL") . "confirm-email/" . $data['user_code']), $template);
			break;
		}

		$template = str_replace("{{LOGIN}}", $data['user_nickname'] ?? "user!", $template);
		$template = str_replace("{{BASE_URL}}", Config::getValue("BASE_URL"), $template);
		$template = str_replace("{{LOGO_URL}}", Config::getValue("SITE_LOGO_URL"), $template);
		$template = str_replace("{{SERVER_NAME}}", Config::getValue("SERVER_NAME"), $template);

		$this->mailer->Body = $template;
		return $this->mailer->Send();
	}
}
?>