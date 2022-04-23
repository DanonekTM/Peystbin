<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<base href="<?= \Danonek\Kernel\Tools\Configurator::getValue('BASE_URL'); ?>"/>
		<title><?= \Danonek\Kernel\Tools\Configurator::getValue('SEO_SITE_TITLE'); ?> Maintenance</title>
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
		<div class="min-h-screen pt-16 pb-12 flex flex-col bg-white">
			<main class="flex-grow flex flex-col justify-center max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex-shrink-0 flex justify-center">
					<a href="/" class="inline-flex">
						<span class="sr-only"><?= \Danonek\Kernel\Tools\Configurator::getValue('PROJECT_NAME'); ?></span>
						<img class="h-12 w-auto" src="/assets/img/logo.svg" alt="">
					</a>
				</div>
				<div class="py-16">
					<div class="text-center">
						<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">Yikes!</h1>
						<p class="mt-2 text-base text-gray-500">We're under maintenance.</p>
						<p class="mt-2 text-base text-gray-500">We'll be back shortly!</p>
					</div>
				</div>
			</main>
			<footer class="flex-shrink-0 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
				<nav class="flex justify-center space-x-4">
					<a href="mailto:<?= \Danonek\Kernel\Tools\Configurator::getValue('WEBMASTER_EMAIL'); ?>" class="text-sm font-medium text-gray-500 hover:text-gray-600">Contact Support</a>
				</nav>
			</footer>
		</div>
	</body>
</html>