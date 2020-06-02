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
		<title><?= $ConfigTools->config_item('cp_web_title'); ?></title>
		<link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
		<link rel="stylesheet" href="/assets/vendors/base/vendor.bundle.base.css">
		<?php
		if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == "style-d.css")
		{
		?>
			<link rel="stylesheet" id="pagestyle" href="/assets/css/style-d.css">
		<?php
		}
		else
		{
		?>
			<link rel="stylesheet" id="pagestyle" href="/assets/css/style.css">
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
							<div class="col-md-6 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">About Your Account</h4>
										<table class="table mb-0">
											<tbody>
												<tr>
													<td class="pl-0">Username</td>
													<td class="pr-0 text-right">
														<?= $_SESSION['user_username']; ?>
													</td>
												</tr>
												<tr>
													<td class="pl-0">Email Address</td>
													<td class="pr-0 text-right">
														<?= $_SESSION['user_email']; ?>
													</td>
												</tr>	
												<tr>
													<td class="pl-0">Join Date</td>
													<td class="pr-0 text-right">
														<?= $_SESSION['user_join_date']; ?>
													</td>
												</tr>
												<tr>
													<td class="pl-0">Member Status</td>
													<td class="pr-0 text-right">
														<?php if ($_SESSION['user_title'] == "Free")
														{
														?>
															<mark class="bg-dark text-white" style="border-radius: 0.25rem;"><?= $_SESSION['user_title']; ?></mark>
														<?php
														}
														else
														{
														?>
															<mark class="bg-primary text-white" style="border-radius: 0.25rem;"><?= $_SESSION['user_title']; ?></mark>
														<?php
														}
														?>
													</td>
												</tr>
												<tr>
													<td class="pl-0">Private Peysts Left</td>
													<td class="pr-0 text-right">
														<mark class="bg-danger text-white" style="border-radius: 0.25rem;"><?= $_SESSION['user_priv_limit']; ?></mark>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Change Password</h4>
										<form action="cp.php" method="POST" autocomplete="off">
											<div class="form-group row">
												<label class="col-sm-3 col-form-label">Current Password</label>
												<div class="col-sm-9">
													<input type="password" class="form-control" name="old_password" placeholder="Current Password">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-3 col-form-label">New Password</label>
												<div class="col-sm-9">
													<input type="password" class="form-control" name="new_password" placeholder="New Password">
												</div>
											</div>
											<div class="form-group row">
												<label class="col-sm-3 col-form-label">Repeat New Password</label>
												<div class="col-sm-9">
													<input type="password" class="form-control" name="new_password_repeat" placeholder="Repeat New Password">
												</div>
											</div>
											<br>
											<?php
												if (isset($samePass))
												{
													switch($samePass)
													{
														case 1:
														?>
															<p class="small-error">	
															<?php
															echo "Password must be different from the password already used.";
															?>
															</p>
														<?php
														break;	
														
														case 2:
														?>
															<p style="color: green;">	
															<?php
															echo "Password changed successfully!";
															?>
															</p>
															<?php
														break;	

														case 3:
														?>
															<p class="small-error">	
															<?php
															echo "Incorrect Password!";
															?>
															</p>
															<?php
														break;
														
														case 4:
														?>
															<p class="small-error">	
															<?php
															echo "Repeat password doesn't match!";
															?>
															</p>
															<?php
														break;
														
														case 5:
														?>
															<p class="small-error">	
															<?php
															echo "Password has a minimum length of 6 characters.";
															?>
															</p>
															<?php
														break;
													}
												}
												else
												{
													?>
													<div></div>
													<?php
												}
											?>
											
											<input type="hidden" name="token" id="token" value="<?= $_SESSION['token'] ?>">
											
											<input class="btn btn-primary mr-2" type="submit" name="login" value="Change Password"></input>
										</form>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xl-6 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Other</h4>
										<table class="table mb-0">
											<tbody>
												<tr>
													<td class="pl-0">Dark Mode</td>
													<td class="pr-0 text-right">
														<label class="toggle-switch toggle-switch-dark">
														<?php
														if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == "style-d.css") 
														{
														?>
															<input type="checkbox" id="themeToggle" name="themeToggle" checked>
														<?php
														}
														else
														{
														?>
															<input type="checkbox" id="themeToggle" name="themeToggle">
														<?php
														}
														?>
															<span class="toggle-slider round"></span>
														</label>
													</td>
												</tr>
												<tr>
													<td class="pl-0">Upgrade Account</td>
													<td class="pr-0 text-right">
													<?php
													if ($_SESSION['user_title'] == "Premium")
													{
													?>
														<button class="btn btn-primary btn-icon-text" name="upgrade_account" disabled="">
															<i class="mdi mdi-crown btn-icon-prepend"></i>
														</button>
													<?php
													}
													else
													{
													?>
														<a href = "?upgrade_account">
															<button class="btn btn-primary btn-icon-text" name="upgrade_account">
																<i class="mdi mdi-crown btn-icon-prepend"></i>
															</button>
														</a>
													<?php
													}
													?>
													</td>
												</tr>
												<tr>
													<td class="pl-0">Delete Account</td>
													<td class="pr-0 text-right">
														<a href = "?delete_account">
															<button class="btn btn-danger btn-icon-text">
																<i class="mdi mdi-delete btn-icon-prepend"></i>
															</button>
														</a>
													</td>
												</tr>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xl-6 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Login History
											<div class="btn-group" style="float:right;" role="group">
												<a href = "?login_history">	
													<button type="button" class="btn btn-primary">View More</button>
												</a>
											</div>
										</h4>
										<div class="table-responsive">
											<table class="table" style="text-align: center;">
												<thead>
													<tr>
														<th><i class="mdi mdi-calendar icon-prepend"></i>Date</th>
														<th><i class="mdi mdi-map-marker-radius icon-prepend"></i>IP Address</th>
														<th><i class="mdi mdi mdi-earth icon-prepend"></i>Browser</th>
													</tr>
												</thead>
												<tbody>
												<?php
												if (mysqli_num_rows($loginHistory))
												{
													foreach ($loginHistory as $login_result)
													{
													?>
														<tr>
															<td><?= $login_result['login_date']; ?></td>
															<td><?= $login_result['ip_address']; ?></td>
															<td><?= $login_result['browser_agent']; ?></td>
														</tr>
													<?php
													}
												}
												else
												{
													?>
														<tr>
															<td>No Data</td>
															<td>No Data</td>
															<td>No Data</td>
														</tr>
													<?php
												}
												?>
												</tbody>
											</table>
										</div>
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
		<script src="/assets/js/switcher.js"></script>
	</body>
</html>
<?php
}
?>