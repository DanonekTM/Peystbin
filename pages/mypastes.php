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
		<title><?= $ConfigTools->config_item('mypastes_web_title'); ?></title>
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
									if (mysqli_num_rows($result))
									{
									?>
										<h4 class="card-title">My Pastes <?= '<div class="btn-group" style="float:right;" role="group">' . $pagelist . '</div>'; ?></h4>
										<div class="table-responsive">
											<table class="table table-striped" style="text-align: center;">
												<thead>
													<tr>
														<th><i class="mdi mdi-format-text icon-prepend"></i>Paste Name / Title</th>
														<th><i class="mdi mdi-calendar-plus icon-prepend"></i>Posted</th>
														<th><i class="mdi mdi-link icon-prepend"></i>Link</th>
														<th><i class="mdi mdi-eye icon-prepend"></i>Views</th>
														<th><i class="mdi mdi mdi-pencil icon-prepend"></i>Edit</th>
														<th><i class="mdi mdi mdi-delete icon-prepend"></i>Delete</th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($result as $paste)
													{
													?>
													<tr>
														<td><?= $paste['paste_title']; ?></td>
														<td><?= $paste['added']; ?></td>
														<?php
														if ($ConfigTools->config_item('rewrite_on'))
														{
														?>
															<td><a href="/<?= $paste['paste_id']; ?>"><?= $paste['paste_id'] ?></a></td>
														<?php
														}
														else
														{
														?>
															<td><a href="index.php?p=<?= $paste['paste_id']; ?>"><?= $paste['paste_id'] ?></a></td>
														<?php
														}
														?>
														<td><?= $paste['views']; ?></td>
														<td>
															<?php
															if ($ConfigTools->config_item('rewrite_on'))
															{
															?>
															<a href="/edit/<?= $paste['paste_id']; ?>">
																<button class="btn btn-primary">
																	<i class="mdi mdi-pencil"></i>
																</button>
															</a>
															<?php
															}
															else
															{
															?>
															<a href="/edit.php?p=<?= $paste['paste_id']; ?>">
																<button class="btn btn-primary">
																	<i class="mdi mdi-pencil"></i>
																</button>
															</a>
															<?php
															}
															?>
														</td>
														<td>
															<form method="POST" action="mypastes.php" style="margin: 0px;">
																<input type="hidden" name="token" id="token" value="<?= $_SESSION['token'] ?>">
															
																<button type="submit" class="btn btn-danger" name="delete" value="<?= $paste['paste_id']; ?>">
																	<i class="mdi mdi-delete"></i>
																</button>
															</form>
														</td>
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
										<h4 class="card-title">Nothing here :/</h4>
										<p>You currently don't have any peysts.</p>
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