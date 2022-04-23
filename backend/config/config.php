<?php

/*
|--------------------------------------------------------------------------
| Miscellaneous / SEO
|--------------------------------------------------------------------------
*/
$config['WEBMASTER_EMAIL'] = "admin@danonek.dev";
$config['PROJECT_NAME'] = "Peystbin";

$config['BASE_URL'] = "http://localhost/";
$config['FORUM_URL'] = "http://localhost/";
$config['SITE_LOGO_URL'] = "https://danonek.dev/assets/logo.png";
$config['SERVER_NAME'] = "Peystbin";

$config['SEO_SITE_TITLE'] = "Peystbin ::";
$config['SEO_DESCRIPTION'] = "Peystbin - A Text Storage Site";
$config['SEO_SITE_NAME'] = "Peystbin";
$config['SEO_SITE_IMAGE_URL'] = "http://localhost/assets/img/logo.svg";
$config['SEO_KEYWORDS'] = "pastebin, pastes, text storage, secure pastebin, encrypted pastes";
$config['SEO_INDEXING'] = false;

/*
|--------------------------------------------------------------------------
| Enviroment
|--------------------------------------------------------------------------
*/
$config['MAINTENANCE_MODE'] = false;
$config['BASE_PATH'] = '/';
$config['ENVIROMENT'] = 'dev';
$config['SESSION_NAME'] = 'DANONEK';
$config['SITE_REGION'] = 'Europe/Dublin';
$config['SESSION_DAYS_EXPIRY'] = 10;
$config['PURGE_CACHE_TIME'] = 1;
$config['PURGE_HISTORY_AFTER_DAYS'] = 30;
$config['REQUIRE_EMAIL_CONFIRMATION'] = true;

/*
|--------------------------------------------------------------------------
| Encryption Config
|--------------------------------------------------------------------------
| Generate PASTE_MASTER_KEY & PASSWORD_PEPPER using: base64_encode(openssl_random_pseudo_bytes(64))
*/
$config['HMAC_HASH_METHOD'] = "sha3-512";
$config['KEY_LENGTH'] = 64;
$config['ENCRYPTION_METHOD'] = "aes-256-cbc";
$config['PASSWORD_PEPPER'] = 'JhNdCY3NXdyCZIxRfhJUXFdnKrOxiJ903D3Pn+8z2nuB4dMMR3tTumDrVI+2ly1Rl1JAW1NHpL6kJ7ItQruupA==';
$config['PASTE_MASTER_KEY'] = '4INxB/32w+i6B0eGEKfETinKInNPGaHu1x4+iWf78UgT/Wkrrnp7feU0dxMRcCBvpXfvzeczKIMlQaRrJklRcg==';

/*
|--------------------------------------------------------------------------
| JWT config
|--------------------------------------------------------------------------
| Generate secret using: base64_encode(openssl_random_pseudo_bytes(64))
*/
$config['TOKEN_EXPIRE_TIME'] = 3600;
$config['SERVER_SECRET'] = 'kPUrosY5EMlAgr0Msz2gUc4QkL/qxLgKmdi0uLJ6UfYCVoQx7Jg8epnC9D8kov4Om95QsBJIxr8+VZTXEx9kDQ==';
$config['JWT_ISSUER'] = 'Danonek';

/*
|--------------------------------------------------------------------------
| Google Recaptcha + API Key
|--------------------------------------------------------------------------
*/
$config['RECAPTCHA_SECRET'] = '';
$config['API_KEY_LENGTH'] = 64;

/*
|--------------------------------------------------------------------------
| Database connection
|--------------------------------------------------------------------------
*/
$config['MYSQL_HOST'] = 'localhost';
$config['MYSQL_PORT'] = 3306;
$config['MYSQL_LOGIN'] = 'root';
$config['MYSQL_PASSWORD'] = 'localhost';
$config['MYSQL_DATABASE_NAME'] = 'peystbin';

/*
|--------------------------------------------------------------------------
| Redis connection
|--------------------------------------------------------------------------
*/
$config['REDIS_HOST'] = 'localhost';
$config['REDIS_PORT'] = 6379;
$config['REDIS_PASSWORD'] = null;

/*
|--------------------------------------------------------------------------
| Throttler & Limit configuration
|--------------------------------------------------------------------------
*/
$config['THROTTLE_REQUESTS_PERMITTED'] = 10;
$config['THROTTLE_TIME_PERIOD'] = 30;
$config['PASTE_LIMIT_SIZE'] = 512; //Kilobytes
$config['PASTE_BURN_CODE_LENGTH'] = 24;

/*
|--------------------------------------------------------------------------
| Mailer
|--------------------------------------------------------------------------
*/
$config['SEND_MAIL'] = true;
$config['MAIL_SMTP_DEBUG'] = 0; // https://github.com/PHPMailer/PHPMailer/wiki/SMTP-Debugging
$config['MAIL_SMTP_AUTH'] = true;
$config['MAIL_SMTP_SECURE'] = 'ssl';
$config['MAIL_CHARSET'] = 'utf8';
$config['MAIL_PORT'] = 465; // 587 || 465 for ssl
$config['MAIL_HOST'] = '';
$config['MAIL_USERNAME'] = '';
$config['MAIL_PASSWORD'] = '';

/*
|--------------------------------------------------------------------------
| Form Validation
|--------------------------------------------------------------------------
*/
$config['MINIMUM_NICKNAME_LENGTH'] = 2;
$config['MAXIMUM_NICKNAME_LENGTH'] = 16;
$config['MAXIMUM_EMAIL_LENGTH'] = 40;
$config['MINIMUM_PASSWORD_LENGTH'] = 7;
$config['MAXIMUM_PASSWORD_LENGTH'] = 24;
$config['ACTIVATION_CODE_LENGTH'] = 64;
$config['RESET_PASSWORD_CODE_LENGTH'] = 60;
$config['EMAIL_CHANGE_CODE_LENGTH'] = 48;

$config['MAXIMUM_PASTE_TITLE_LENGTH'] = 32;

/*
|--------------------------------------------------------------------------
| Template Pages
|--------------------------------------------------------------------------
*/
$config['APP_TEMPLATE'] = 'App.php';
$config['MAINTENANCE_TEMPLATE'] = 'Maintenance.php';

?>