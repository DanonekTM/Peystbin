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
		<title><?= $ConfigTools->config_item('faq_web_title'); ?></title>
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
							<div class="col-md-12 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">FAQ [Frequently Asked Questions]</h4>
										<p class="card-description">Last Updated 06-05-2020</p>
										<hr>
										<p>This is our help page, below you can find a list of the most frequently asked questions:</p>
										<p>- Under construction....</p>
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