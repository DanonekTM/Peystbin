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
		<?php
		if ($paste_info)
		{
		?>
			<title><?= $ConfigTools->config_item('view_web_title') . " - " . $paste_id; ?></title>
		<?php
		}
		else
		{
		?>
			<title><?= $ConfigTools->config_item('view_web_title') . " - Removed Paste"; ?></title>
		<?php
		}
		?>
		<link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="/assets/vendors/base/vendor.bundle.base.css">
		<?php
		if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == "style-d.css")
		{
		?>
			<link rel="stylesheet" href="/assets/css/style-d.css">
			<link rel="stylesheet" href="/assets/css/prettify-d.css">
		<?php
		}
		else
		{
		?>
			<link rel="stylesheet" href="/assets/css/style.css">
			<link rel="stylesheet" href="/assets/css/prettify.css">
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
							<div class="col-12 grid-margin stretch-card">
								<div class="card">
								<?php
								if ($paste_info)
								{
								?>
									<?= $paste_info['highlighting']['onLoad']; ?>
									<?= $paste_info['highlighting']['includePrettify']; ?>
									<div class="card-body">
										<h4 class="card-title"><?= $ConfigTools->sanitizePaste($paste_info['dbResult']['paste_title']); ?></h4>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group row">
														<ul style="list-style-type: none">
															<li><label><i class="mdi mdi-calendar-plus icon-prepend"></i>Added: <?= $paste_info['dbResult']['added']; ?></label></li>
															<li><label><i class="mdi mdi-eye icon-prepend"></i>Views: <?= $paste_info['dbResult']['views']; ?></label></li>
															<li><label><i class="mdi mdi-delete icon-prepend"></i><?= $DatabaseFunctions->remaining_time($paste_info['dbResult']['deletion_date']); ?></label></li>
															<li><label><i class="mdi mdi-file icon-prepend"></i>Size: <?= $DatabaseFunctions->formatBytes($DatabaseFunctions->getPasteSize($paste_id)['paste_size'], 2); ?></label></li>
															<?php
															if ($paste_info['rewriteOn'])
															{
															?>
															<li><label><a href="<?= $paste_id; ?>@raw">View Raw</a></label></li>
															<?php
															}
															else
															{
															?>
															<li><label><a href="index.php?p=<?= $paste_id; ?>@raw">View Raw</a></label></li>
															<?php
															}
															?>
														</ul>
													</div>
												</div>
											</div>
										<div class="form-group">
											<pre class="form-control <?= $paste_info['class']; ?>" rows="20"><?= $ConfigTools->sanitizePaste($paste_info['dbResult']['paste']); ?></pre>
										</div>
										
										<?php 
										if ($Login->isUserLoggedIn())
										{
											if ($paste_info['dbResult']['user_id'] == $_SESSION['user_id'] && !$paste_info['dbResult']['guest'])
											{
										?>
										<div class="form-group">
												<?php
												if ($ConfigTools->config_item('rewrite_on'))
												{
												?>
													<form method="POST" action="/edit" autocomplete="off">
														<input type="hidden" name="token" id="token" value="<?= $_SESSION['token'] ?>">
														<button name="delete" value="<?= $paste_id ?>" type="submit" class="btn btn-danger mr-2 btn-icon-text" style="float: right;"><i class="mdi mdi-delete btn-icon-prepend"></i>Delete</button>
													</form>
													
													<a href="/edit/<?= $paste_id ?>">
														<button name="p" value="<?= $paste_id ?>" type="submit" class="btn btn-primary mr-2 btn-icon-text" style="float: right;"><i class="mdi mdi-pencil btn-icon-prepend"></i>Edit</button>
													</a>
												<?php
												}
												else
												{
												?>
													<form method="POST" action="/edit.php" autocomplete="off">
														<input type="hidden" name="token" id="token" value="<?= $_SESSION['token'] ?>">
														<button name="delete" value="<?= $paste_id ?>" type="submit" class="btn btn-danger mr-2 btn-icon-text" style="float: right;"><i class="mdi mdi-delete btn-icon-prepend"></i>Delete</button>
													</form>
													
													<form method="GET" action="edit.php" autocomplete="off">
														<button name="p" value="<?= $paste_id ?>" type="submit" class="btn btn-primary mr-2 btn-icon-text" style="float: right;"><i class="mdi mdi-pencil btn-icon-prepend"></i>Edit</button>
													</form>
												<?php
												}
											}
										}
										?>
										</div>
									</div>
								<?php
								}
								else
								{
								?>
									<div class="card-body">
										<h4 class="card-title">This peyst has been removed!</h4>
										<p>It has either expired, been removed by its creator, or removed by one of the Peystbin staff.</p>
									</div>
								<?php
								}
								?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include "elements/footer.php"; ?>
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