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
		<title><?= $ConfigTools->config_item('edit_web_title') . " - " . $paste_id; ?></title>
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
								if ($paste_info && $paste_info['dbResult']['user_id'] == $_SESSION['user_id'] && !$paste_info['dbResult']['guest'])
								{
								?>
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
													</ul>
												</div>
											</div>
										</div>
										<form action="/edit.php" method="POST" autocomplete="off">
										
											<div class="form-group">
												<textarea class="form-control paste-content" name="p" rows="25" autofocus><?= $ConfigTools->sanitizePaste($paste_info['dbResult']['paste']); ?></textarea>
											</div>
											
											<div class="form-group">
												<div class="form-check form-check-primary">
													<label class="form-check-label">
														<input type="checkbox" name="wrap" class="form-check-input" <?php if ($paste_info['dbResult']['wrap']) { echo "checked='checked'"; } ?>>
														Wrap Long Lines
													<i class="input-helper"></i></label>
												</div>
												<input type="hidden" name="danonek">
												<div class="form-check form-check-primary">
													<label class="form-check-label">
														<input type="checkbox" name="highlighting" class="form-check-input" <?php if ($paste_info['dbResult']['highlighting']) { echo "checked='checked'"; } ?>>
														Syntax Highlighting
													<i class="input-helper"></i></label>
												</div>
												<div class="form-check form-check-primary">
													<label class="form-check-label">
														<input type="checkbox" name="private" class="form-check-input" <?php if ($paste_info['dbResult']['is_private']) { echo "checked='checked'"; } ?>>
														Private Peyst
													<i class="input-helper"></i></label>
												</div>

												<input type="hidden" name="token" id="token" value="<?= $_SESSION['token'] ?>">
												
												<button name="delete" value="<?= $paste_id ?>" type="submit" class="btn btn-danger mr-2 btn-icon-text" style="float: right;">
													<i class="mdi mdi-delete btn-icon-prepend"></i>
													Delete
												</button>
												
												<button name="save" value="<?= $paste_id ?>" type="submit" class="btn btn-primary mr-2 btn-icon-text" style="float: right;">
													<i class="mdi mdi-content-save btn-icon-prepend"></i>
													Save
												</button>
											</div>
										</form>
								<?php
								}
								else
								{
									if ($ConfigTools->config_item('rewrite_on'))
									{
										header('Location: /info');
										die();
									}
									else
									{
										header('Location: /info.php');
										die();
									}
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
		<script src="/assets/js/autosize.js"></script>
		<script src="/assets/js/textarea/textarea.js"></script>
		<script src="/assets/js/template.js"></script>
	</body>
</html>
<?php
}
?>