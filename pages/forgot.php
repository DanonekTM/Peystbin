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
		<title><?= $ConfigTools->config_item('forgot_password_web_title'); ?></title>
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
	<body id="main-content">
		<div class="container-scroller">
			<div class="horizontal-menu">
			<?php include "elements/topbar.php"; ?>
			<?php include "elements/topmenu.php"; ?>
			</div>
			<div class="container-fluid page-body-wrapper">
				<div class="main-panel">
					<div class="content-wrapper">		
					
					<?php 
					if (isset($error)) 
					{
						?>
						<div class="row mt-4">
							<div class="col-6 grid-margin stretch-card" style="margin: 0 auto;">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Oopsie!</h4>
										<p class="text-danger"><?= $error; ?></p>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
					
					
						<div class="row mt-4">
							<div class="col-md-6 grid-margin stretch-card" style="margin: 0 auto;">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Forgot your password?</h4>
										<p class="card-description">Enter your details and we will reset it for you.</p>
										<form class="pt-3" action="forgot.php" method="POST" autocomplete="off">
											<div class="form-group">
												<input type="email" class="form-control form-control-lg" name="forgot_email" placeholder="Email">
											</div>
											<div class="form-group">
												<input type="text" class="form-control form-control-lg" name="forgot_username" placeholder="Username">
											</div>
											<img src="/assets/images/captcha.php" class="captcha-img">
											<div class="form-group-captcha">
												<input type="text" class="form-control form-control-lg" name="captcha" placeholder="Captcha">
											</div>
											<div class="mt-3">
												<input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="reset_password" value="Reset Password"></input>
											</div>
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
										</form>
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