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
		<title><?= $ConfigTools->config_item('home_web_title'); ?></title>
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
						<div class="row mt-4">
							<div class="col-12 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">New Paste</h4>
										<form method="POST" action="index.php">
											<div class="form-group">
												<textarea class="form-control paste-content" name="p" rows="25" autofocus></textarea>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group row">
														<label class="col-sm-3 col-form-label">Paste Name :</label>
														<div class="col-sm-9">
															<input type="text" name="t" class="form-control">
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group row">
														<label class="col-sm-3 col-form-label">Paste Expiration :</label>
														<div class="col-sm-9">
															<select name="d" class="form-control">
																 <option value="1D" selected hidden>Expiration (1 day)</option>
																 <option value="BURN">burn after reading</option>
																 <option value="10M">10 minutes</option>
																 <option value="1H">1 hour</option>
																 <option value="1D">1 day</option>
																 <option value="1W">1 week</option>
																 <option value="E">Eternal</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="form-check form-check-primary">
													<label class="form-check-label">
														<input type="checkbox" name="wrap" class="form-check-input">
														Wrap Long Lines
													<i class="input-helper"></i></label>
												</div>
												<input type="hidden" name="danonek">
												<div class="form-check form-check-primary">
													<label class="form-check-label">
														<input type="checkbox" name="highlighting" class="form-check-input">
														Syntax Highlighting
													<i class="input-helper"></i></label>
												</div>
												<?php
												if ($Login->isUserLoggedIn())
												{
												?>
												<div class="form-check form-check-primary">
													<label class="form-check-label">
														<input type="checkbox" name="guest" class="form-check-input">
														Paste As Guest
													<i class="input-helper"></i></label>
												</div>
												<div class="form-check form-check-primary">
													<label class="form-check-label">
														<input type="checkbox" name="private" class="form-check-input">
														Private Peyst
													<i class="input-helper"></i></label>
												</div>
												<?php
												}
												?>
												<button type="submit" class="btn btn-primary mr-2 btn-icon-text" style="float: right;"><i class="mdi mdi-plus btn-icon-prepend"></i>Create New Paste</button>
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
		<script src="/assets/js/autosize.js"></script>
		<script src="/assets/js/textarea/textarea.js"></script>
		<script src="/assets/js/template.js"></script>
	</body>
</html>
<?php
}
?>