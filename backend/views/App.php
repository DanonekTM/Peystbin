<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<base href="<?= \Danonek\Kernel\Tools\Configurator::getValue('BASE_URL'); ?>"/>
		<title><?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_SITE_TITLE'); ?></title>
		<meta property="og:title" content="<?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_SITE_TITLE'); ?>"/>
		<meta property="og:url" content="<?= \Danonek\Kernel\Tools\Configurator::getValue('BASE_URL'); ?>"/>
		<meta property="og:description" content="<?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_DESCRIPTION'); ?>"/>
		<meta property="og:site_name" content="<?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_SITE_NAME'); ?>"/>
		<meta property="og:image" content="<?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_SITE_IMAGE_URL'); ?>"/>
		<meta name="description" content="<?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_DESCRIPTION'); ?>"/>
		<meta name="keywords" content="<?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_KEYWORDS'); ?>"/>

		<?php if (\Danonek\Kernel\Tools\Configurator::getValue('SEO_INDEXING')) { ?>
		<meta name="googlebot" content="index, follow" />
		<meta name="robots" content="index, follow" />
		<meta name="distribution" content="global" />
		<?php } ?>

		<link href="/assets/js/confirm-email.js" rel="prefetch" />
		<link href="/assets/js/error.js" rel="prefetch" />
		<link href="/assets/js/faq.js" rel="prefetch" />
		<link href="/assets/js/forgot-password.js" rel="prefetch" />
		<link href="/assets/js/login.js" rel="prefetch" />
		<link href="/assets/js/my-pastes.js" rel="prefetch" />
		<link href="/assets/js/paste-editor.js" rel="prefetch" />
		<link href="/assets/js/paste-view.js" rel="prefetch" />
		<link href="/assets/js/recents.js" rel="prefetch" />
		<link href="/assets/js/register.js" rel="prefetch" />
		<link href="/assets/js/reset-password.js" rel="prefetch" />
		<link href="/assets/js/settings.js" rel="prefetch" />
		<link href="/assets/js/terms.js" rel="prefetch" />
		<link href="/assets/js/app.js" rel="preload" as="script" />
		<link href="/assets/js/chunk-vendors.js" rel="preload" as="script" />
		<link href="/assets/css/app.css" rel="preload" as="style" />
		<link href="/assets/css/app.css" rel="stylesheet" />
	</head>

	<!--
	 ____                _   _     _
	|  _ \ ___ _   _ ___| |_| |__ (_)_ __
	| |_) / _ \ | | / __| __| '_ \| | '_ \
	|  __/  __/ |_| \__ \ |_| |_) | | | | |
	|_|   \___|\__, |___/\__|_.__/|_|_| |_|
	           |___/

	Copyright © Danonek (admin@danonek.dev)
	-->
	<body>
		<noscript>
			<strong>We're sorry but <?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_SITE_NAME'); ?> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
		</noscript>
		<div id="danonek"></div>
	</body>
	
	<script src="/assets/js/chunk-vendors.js"></script>
	<script src="/assets/js/app.js"></script>
</html>