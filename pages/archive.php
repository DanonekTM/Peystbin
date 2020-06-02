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
		<title><?= $ConfigTools->config_item('archive_web_title'); ?></title>
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
							<div class="col-12 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<?php
										if (mysqli_num_rows($archivedPastes))
										{
										?>
										<h4 class="card-title">Peysts Archive</h4>
										<div class="table-responsive">
											<table class="table" style="text-align: center;">
												<thead>
													<tr>
														<th><i class="mdi mdi-account icon-prepend"></i>Username</th>
														<th><i class="mdi mdi-format-text icon-prepend"></i>Paste Name / Title</th>
														<th><i class="mdi mdi-calendar-plus icon-prepend"></i>Posted</th>
														<th><i class="mdi mdi-link icon-prepend"></i>Link</th>
														<th><i class="mdi mdi-eye icon-prepend"></i>Views</th>
													</tr>
												</thead>
												<tbody>
												<?php
												foreach ($archivedPastes as $paste)
												{
												?>
													<tr>
														<td><?php if ($paste['username'] == null) { echo "Unknown"; } elseif ($paste['guest']) { echo "Unknown"; } else { echo $paste['username']; } ?></td>
														<td><?= $paste['paste_title']; ?></td>
														<td><?= $paste['added']; ?></td>
														<?php
														if ($ConfigTools->config_item('rewrite_on'))
														{
														?>
															<td><a href="/<?= $paste['paste_id']; ?>"><?= $paste['paste_id']; ?></a></td>
														<?php
														}
														else
														{
														?>
															<td><a href="index.php?p=<?= $paste['paste_id']; ?>"><?= $paste['paste_id']; ?></a></td>
														<?php
														}
														?>
														<td><?= $paste['views']; ?></td>
													</tr>	
												<?php
												}
												?>
												</tbody>
											</table>
										</div>
										<?php
										}
										else
										{
										?>
										<h4 class="card-title">Nothing archived!</h4>
										<p>There is currently nothing in our archives.</p>
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