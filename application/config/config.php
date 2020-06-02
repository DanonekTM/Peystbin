<?php

if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

// Mail Config
$config['use_mail'] = false; // Set to true when u configure this block
$config['mail_smtp_debug'] = 0; // Debug Off
$config['mail_smtp_auth'] = true;
$config['mail_smtp_secure'] = "ssl";
$config['mail_host'] = "smtp.sendgrid.net";
$config['mail_username'] = "";
$config['mail_password'] = "";
$config['mail_port'] = 465; // or 587 | 465 for ssl
$config['mail_from'] = "";

// Web server config
$config['check_https'] = false;
$config['rewrite_on'] = false;

// Titles
$config['home_web_title'] = "Peystbin :: Home";
$config['login_web_title'] = "Peystbin :: Login";
$config['register_web_title'] = "Peystbin :: Register";
$config['info_web_title'] = "Peystbin :: Info";
$config['error_web_title'] = "Peystbin :: Error";
$config['edit_web_title'] = "Peystbin :: Editing";
$config['view_web_title'] = "Peystbin :: Viewing";
$config['archive_web_title'] = "Peystbin :: Recent Peysts";
$config['mypastes_web_title'] = "Peystbin :: My Peysts";
$config['faq_web_title'] = "Peystbin :: FAQ";
$config['cp_web_title'] = "Peystbin :: Control Panel";
$config['forgot_password_web_title'] = "Peystbin :: Forgot Password";
$config['delete_account_web_title'] = "Peystbin :: Delete Account";
$config['upgrade_account_web_title'] = "Peystbin :: Upgrade Account";
$config['login_history_web_title'] = "Peystbin :: Login History";

// Premium Users
$config['user_premium_title'] = "Premium";
$config['user_priv_limit'] = 1000;


// Web content
$config['title'] = "Peystbin";
$config['footer'] = "Copyright © 2020 Peystbin. All rights reserved.";
$config['footer_right'] = "Hand-crafted & Made with <i class='mdi mdi-heart' style='color: red'></i>";


?>