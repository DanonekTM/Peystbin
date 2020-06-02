<?php
if (!defined('SELF_CALLED'))
{
?>
	<html>
	<head><title>404 Not Found</title></head>
	<body bgcolor="white">
	<center><h1>404 Not Found</h1></center>
	<hr><center>nginx</center>
	</body>
	</html>
	<!-- a padding to disable MSIE and Chrome friendly error page -->
	<!-- a padding to disable MSIE and Chrome friendly error page -->
	<!-- a padding to disable MSIE and Chrome friendly error page -->
	<!-- a padding to disable MSIE and Chrome friendly error page -->
	<!-- a padding to disable MSIE and Chrome friendly error page -->
	<!-- a padding to disable MSIE and Chrome friendly error page -->
<?php
}
else
{
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?= $ConfigTools->config_item('info_web_title'); ?></title>
		<link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="/assets/vendors/base/vendor.bundle.base.css">
		<?php
		if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == "style-d.css")
		{
		?>
			<link rel="stylesheet" href="/assets/css/style-d.css">
		<?php
		}
		else
		{
		?>
			<link rel="stylesheet" href="/assets/css/style.css">
		<?php
		}
		?>
		<link rel="shortcut icon" href="/assets/images/favicon.png">
	</head>
	<body>
		<div class="container-scroller">
			<div class="horizontal-menu">
			<?php include "elements/topbar.php"; ?>
			<?php include "elements/topmenu.php"; ?>
			</div>
			<div class="container-fluid page-body-wrapper">
				<div class="main-panel">
					<div class="content-wrapper">
						<div class="row mt-4">
							<div class="col-6 grid-margin stretch-card" style="margin: 0 auto">
								<div class="card">
									<div class="card-body">
										
									<?php
									if (isset($_SESSION['REGISTRATION_SUCCESS']) && $_SESSION['REGISTRATION_SUCCESS'] == true)
									{
										unset($_SESSION['REGISTRATION_SUCCESS']);
									?>
									<h4 class="card-title">Success!</h4>
									<p>You've successfully signed up on Peystbin.</p>
									<hr>
									<div class="text-center mt-4 font-weight-light">
									
									<?php
										if ($ConfigTools->config_item('rewrite_on'))
										{
										?>
											You can <a href="login" class="text-primary">Login</a> now.
										<?php
										}
										else
										{
										?>
											You can <a href="login.php" class="text-primary">Login</a> now.
										<?php
										}
										?>
										</div>
									<?php
									}
									?>
									
									<?php
									if (isset($_SESSION['REGISTRATION_SUCCESS']) && $_SESSION['REGISTRATION_SUCCESS'] == false)
									{
										unset($_SESSION['REGISTRATION_SUCCESS']);
									?>
									<h4 class="card-title">Failed!</h4>
									<p>Something went wrong on our side, please try signing up later.</p>
									<hr>
									<div class="text-center mt-4 font-weight-light">
										<?php
										if ($ConfigTools->config_item('rewrite_on'))
										{
										?>
											Back to <a href="register" class="text-primary">Registration</a> page.
										<?php
										}
										else
										{
										?>
											Back to <a href="register.php" class="text-primary">Registration</a> page.
										<?php
										}
										?>
									</div>
									<?php
									}
									?>
									
									<?php
									if (isset($_SESSION['FORGOT_PASSWORD']) && $_SESSION['FORGOT_PASSWORD'] == true)
									{
										unset($_SESSION['FORGOT_PASSWORD']);
									?>
									<h4 class="card-title">Success!</h4>
									<p>We've sent you an email containing your new password.</p>
									<hr>
									<div class="text-center mt-4 font-weight-light">
									<?php
										if ($ConfigTools->config_item('rewrite_on'))
										{
										?>
											Back to <a href="login" class="text-primary">Login</a> page.
										<?php
										}
										else
										{
										?>
											Back to <a href="login.php" class="text-primary">Login</a> page.
										<?php
										}
										?>
										</div>
									<?php
									}
									?>
									
									<?php
									if (isset($_SESSION['FORGOT_PASSWORD']) && $_SESSION['FORGOT_PASSWORD'] == false)
									{
										unset($_SESSION['FORGOT_PASSWORD']);
									?>
									<h4 class="card-title">Failed!</h4>
									<p>The entered username or email was not found.</p>
									<hr>
									<div class="text-center mt-4 font-weight-light">
									<?php
										if ($ConfigTools->config_item('rewrite_on'))
										{
										?>
											<a href="forgot" class="text-primary">Try again</a>
										<?php
										}
										else
										{
										?>
											<a href="forgot.php" class="text-primary">Try again</a>
										<?php
										}
										?>
									</div>
									<?php
									}
									?>
									
									<?php
									if (isset($_SESSION['PASTE_LIMIT']) && $_SESSION['PASTE_LIMIT'] == true)
									{
										unset($_SESSION['PASTE_LIMIT']);
									?>
									<h4 class="card-title">Your Private Peyst Limit has exceeded!</h4>
									<p>Upgrade to a Premium account for more Private Peysts or Delete some of your current private Peysts.</p>
									<hr>
									<div class="text-center mt-4 font-weight-light">
									<?php
										if ($ConfigTools->config_item('rewrite_on'))
										{
										?>
											<a href="/" class="text-primary">Home</a>
										<?php
										}
										else
										{
										?>
											<a href="/" class="text-primary">Home</a>
										<?php
										}
										?>
									</div>
									<?php
									}
									?>

									<?php
									if (isset($_SESSION['PASTE_SIZE_LIMIT']) && $_SESSION['PASTE_SIZE_LIMIT'] == true)
									{
										unset($_SESSION['PASTE_SIZE_LIMIT']);
									?>
									<h4 class="card-title">You have exceeded the maximum paste size of 512 kilobytes per paste!</h4>
									<p>Premium users don't have this limit!</p>
									<hr>
									<div class="text-center mt-4 font-weight-light">
									<?php
										if ($ConfigTools->config_item('rewrite_on'))
										{
										?>
											<a href="/" class="text-primary">Home</a>
										<?php
										}
										else
										{
										?>
											<a href="/" class="text-primary">Home</a>
										<?php
										}
										?>
									</div>
									<?php
									}
									?>
									
									<?php
									if (isset($_SESSION['UPGRADE_SUCCESS']) && $_SESSION['UPGRADE_SUCCESS'] == true)
									{
										unset($_SESSION['UPGRADE_SUCCESS']);
									?>
									<h4 class="card-title">Congratulations!</h4>
									<p>You've just upgraded to <mark class="bg-primary text-white" style="border-radius: 0.25rem;">Premium</mark></p>
									<p>Please log back in to enjoy your new perks :)</p>
									<hr>
									<div class="text-center mt-4 font-weight-light">
										<script>
											function startTimer() 
											{
												var timer = 3, seconds;
												var end = setInterval(function () 
												{
													seconds = parseInt(timer % 60, 10);

													document.querySelector('#time').textContent = seconds;

													if (--timer < 0) 
													{
														window.location = "logout.php";
														clearInterval(end);
													}
												}, 1000);
											}

											window.onload = function()
											{
												display = document.querySelector('#time');
												startTimer();
											};
										</script>
										<p>You will be logged out in <span id="time">3</span> seconds</p>
									</div>
									<?php
									}
									?>
									
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php include "elements/footer.php"; ?>
				</div>
			</div>
		</div>
		<script src="/assets/vendors/base/vendor.bundle.base.js"></script>
		<script src="/assets/vendors/topbar-loader/topbar.min.js"></script>
		<script src="/assets/vendors/topbar-loader/topbar.js"></script>
		<script src="/assets/js/template.js"></script>
	</body>
</html>
<?php
}
?>