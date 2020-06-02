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
		<title><?= $ConfigTools->config_item('register_web_title'); ?></title>
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
					if (isset($Registration)) 
					{
						if ($Registration->errors)
						{
							foreach ($Registration->errors as $error) 
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
						}
					}
					?>
						<div class="row mt-4">
							<div class="col-md-6 grid-margin stretch-card" style="margin: 0 auto;">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">New here?</h4>
										<p class="card-description">Signing up is easy and only takes a few steps :)</p>
										<form class="pt-3" action="register.php" method="POST" autocomplete="off">
										<div class="form-group">
											<?php 
											if (isset($username))
											{
											?>
												<input type="text" class="form-control form-control-lg" name="username" placeholder="Username" value="<?= $username;?>">
											<?php
											}
											else
											{
											?>
											<input type="text" class="form-control form-control-lg" name="username" placeholder="Username">
											<?php
											}
											?>
										</div>
										<div class="form-group">
											<?php 
											if (isset($email))
											{
											?>
												<input type="email" class="form-control form-control-lg" name="email" placeholder="Email" value="<?= $email;?>">
											<?php
											}
											else
											{
											?>
												<input type="email" class="form-control form-control-lg" name="email" placeholder="Email">
											<?php
											}
											?>
										</div>
										<div class="form-group">
											<input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
										</div>
										<div class="form-group">
											<input type="password" class="form-control form-control-lg" name="password-repeat" placeholder="Confirm Password">
										</div>
										<img src="/assets/images/captcha.php" class="captcha-img">
										<div class="form-group-captcha">
											<input type="text" class="form-control form-control-lg" name="captcha" placeholder="Captcha" maxlength="5">
										</div>
										<div class="mb-4">
											<div class="form-check">
												<label class="form-check-label text-muted">
													<input type="checkbox" name="tos" class="form-check-input">
													I agree to all Terms & Conditions
												</label>
											</div>
										</div>
										<div class="mt-3">
											<input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="register" value="SIGN UP"></input>
										</div>
										<div class="text-center mt-4 font-weight-light">
											Already have an account? 
											<?php
											if ($ConfigTools->config_item('rewrite_on'))
											{
											?>
												<a href="login" class="text-primary">Login</a>
											<?php
											}
											else
											{
											?>
												<a href="login.php" class="text-primary">Login</a>
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