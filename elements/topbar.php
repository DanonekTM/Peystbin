<?php
	if (defined('SELF_CALLED'))
	{
?>		<nav class="navbar top-navbar col-lg-12 col-12 p-0">
			<div class="container-fluid">
				<div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
					<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
					<?php
						if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == "style-d.css")
						{
						?>
							<a class="navbar-brand brand-logo" href="/"><img id="logo" src="/assets/images/style-d.css/logo.svg" alt="logo"></a>
							<a class="navbar-brand brand-logo-mini" href="/"><img id="logo-mini" src="/assets/images/style-d.css/logo-mini.svg" alt="logo"></a>
						<?php
						}
						else
						{
						?>
							<a class="navbar-brand brand-logo" href="/"><img id="logo" src="/assets/images/style.css/logo.svg" alt="logo"></a>
							<a class="navbar-brand brand-logo-mini" href="/"><img id="logo-mini" src="/assets/images/style.css/logo-mini.svg" alt="logo"></a>
						<?php
						}
						?>
					</div>
					<ul class="navbar-nav navbar-nav-right">
						<li class="nav-item nav-profile dropdown">
						<?php
						if ($Login->isUserLoggedIn())
						{
						?>
							<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
								<span class="nav-profile-name"><i class="mdi mdi-account"></i><?= $_SESSION['user_username']; ?></span>
							</a>
							<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
							<?php
							if ($ConfigTools->config_item('rewrite_on'))
							{
							?>
								<a href="cp" class="dropdown-item">
							<?php
							}
							else
							{
							?>
								<a href="cp.php" class="dropdown-item">
							<?php
							}
							?>
									<i class="mdi mdi-settings text-primary"></i>
									Settings
								</a>
							<?php
							if ($ConfigTools->config_item('rewrite_on'))
							{
							?>
								<a href="logout" class="dropdown-item">
							<?php
							}
							else
							{
							?>
								<a href="logout.php" class="dropdown-item">
							<?php
							}
							?>
									<i class="mdi mdi-logout text-primary"></i>
									Logout
								</a>
							</div>
						<?php
						}
						else
						{
						?>
							<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
								<span class="nav-profile-name"><i class="mdi mdi-account"></i>Sign in!</span>
							</a>
							<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
							<?php
							if ($ConfigTools->config_item('rewrite_on'))
							{
							?>
								<a href="login" class="dropdown-item">
							<?php
							}
							else
							{
							?>
								<a href="login.php" class="dropdown-item">
							<?php
							}
							?>
									<i class="mdi mdi-account text-primary"></i>
									Login in
								</a>
								
							<?php
							if ($ConfigTools->config_item('rewrite_on'))
							{
							?>
								<a href="register" class="dropdown-item">
							<?php
							}
							else
							{
							?>
								<a href="register.php" class="dropdown-item">
							<?php
							}
							?>
									<i class="mdi mdi-account-plus text-primary"></i>
									Sign up
								</a>
							</div>
						<?php
						}
						?>						
						</li>
					</ul>
				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
					<span class="mdi mdi-menu"></span>
				</button>
				</div>
			</div>
		</nav>
<?php
	}
	else
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
?>