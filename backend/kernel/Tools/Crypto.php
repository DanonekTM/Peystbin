<?php

namespace Danonek\Kernel\Tools;

use Danonek\Kernel\Tools\Configurator as Config;

class Crypto
{
	public static function encrypt($input, $password) : string
	{
		$user_key = base64_decode($password);
		$server_key = base64_decode(Config::getValue("PASTE_MASTER_KEY"));

		$iv_length = openssl_cipher_iv_length(Config::getValue("ENCRYPTION_METHOD"));
		$iv = openssl_random_pseudo_bytes($iv_length);

		$first_encrypted = openssl_encrypt($input, Config::getValue("ENCRYPTION_METHOD"), $user_key, OPENSSL_RAW_DATA, $iv);
		$second_encrypted = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $first_encrypted, $server_key, TRUE);

		return base64_encode($iv . $second_encrypted . $first_encrypted);
	}
	
	public static function decrypt($input, $password) : ?string
	{
		$user_key = base64_decode($password);
		$server_key = base64_decode(Config::getValue("PASTE_MASTER_KEY"));
		$mix = base64_decode($input);
			
		$iv_length = openssl_cipher_iv_length(Config::getValue("ENCRYPTION_METHOD"));

		$iv = substr($mix, 0, $iv_length);
		$second_encrypted = substr($mix, $iv_length, Config::getValue("KEY_LENGTH"));
		$first_encrypted = substr($mix, $iv_length + Config::getValue("KEY_LENGTH"));

		$data = openssl_decrypt($first_encrypted, Config::getValue("ENCRYPTION_METHOD"), $user_key, OPENSSL_RAW_DATA, $iv);
		$second_encrypted_new = hash_hmac(Config::getValue("HMAC_HASH_METHOD"), $first_encrypted, $server_key, TRUE);

		return hash_equals($second_encrypted, $second_encrypted_new) ? $data : null;
	}
}
?>