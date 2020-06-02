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
		<title><?= $ConfigTools->config_item('delete_account_web_title'); ?></title>
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
						<?php 
						if (isset($_SESSION['delete_account_error']))
						{
						?>
						<div class="row mt-4">
							<div class="col-6 grid-margin stretch-card" style="margin: 0 auto;">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Oopsie!</h4>
										<p class="text-danger"><?= $_SESSION['delete_account_error']; ?></p>
									</div>
								</div>
							</div>
						</div>
						<?php
							unset($_SESSION['delete_account_error']);
						}
						?>
						<div class="row mt-4">
							<div class="col-6 grid-margin stretch-card" style="margin: 0 auto">	
								<div class="card">
									<div class="card-body">								
										<h4 class="card-title text-danger">Are you sure you want to delete your account?</h4>
										<p class="card-description">This is extremely important!</p>
										<p>
											<ol>
												<li>You will <strong>NOT</strong> be able to login again!</li>
												<li>You will <strong>NOT</strong> be able to register another account with this same username!</li>
												<li>All your pastes will be permanently deleted! <strong>NO UNDO!</strong></li>
												<li>You will be immediately logged out of your account!</li>
											</ol>
										</p>
										<p class="font-weight-bold">DELETING YOUR ACCOUNT IS PERMANENT!</p>
										<p class="font-weight-bold">THIS ACTION CANNOT BE UNDONE!</p>
										<hr>
										<form method="POST" action="cp.php">
											<img src="/assets/images/captcha.php" class="captcha-img">
											
											<div class="form-group-captcha">
												<input type="text" class="form-control form-control-lg" name="captcha" placeholder="Captcha" maxlength="5">
											</div>
											
											<input type="hidden" name="token" id="token" value="<?= $_SESSION['token'] ?>">
											
											<div class="text-center mt-4 font-weight-light">
												<div class="form-group">
													<button name="delete_confirm" type="submit" class="btn btn-danger mr-2 btn-icon-text" style="float: right;">
														<i class="mdi mdi-delete btn-icon-prepend"></i>
														Delete Account
													</button>
												</div>
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