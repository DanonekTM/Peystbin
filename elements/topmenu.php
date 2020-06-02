<?php
	if (defined('SELF_CALLED'))
	{
?>
		<nav class="bottom-navbar">
			<div class="container">
				<ul class="nav page-navigation">
					<li class="nav-item" id="index">
						<a href="/" class="nav-link">
							<i class="mdi mdi-content-paste menu-icon"></i>
							<span class="menu-title">New Peyst</span>
						</a>
					</li>
					<li class="nav-item">
					<?php
					if ($ConfigTools->config_item('rewrite_on'))
					{
					?>
						<a href="/archive" class="nav-link">
					<?php
					}
					else
					{
					?>
						<a href="/archive.php" class="nav-link">
					<?php
					}
					?>
							<i class="mdi mdi-file-document-box-outline menu-icon"></i>
							<span class="menu-title">Recent Peysts</span>
						</a>
					</li>
					
					<?php
					if ($Login->isUserLoggedIn())
					{
					?>
					<li class="nav-item">
					<?php
					if ($ConfigTools->config_item('rewrite_on'))
					{
					?>
						<a href="/mypastes" class="nav-link">
					<?php
					}
					else
					{
					?>
						<a href="/mypastes.php" class="nav-link">
					<?php
					}
					?>
							<i class="mdi mdi-eye menu-icon"></i>
							<span class="menu-title">My Peysts</span>
						</a>
					</li>
					
					<?php
					}
					?>
					<li class="nav-item">
					<?php
					if ($ConfigTools->config_item('rewrite_on'))
					{
					?>
						<a href="/faq" class="nav-link">
					<?php
					}
					else
					{
					?>
						<a href="/faq.php" class="nav-link">
					<?php
					}
					?>
							<i class="mdi mdi-comment-question-outline menu-icon"></i>
							<span class="menu-title">FAQ</span>
						</a>
					</li>
				</ul>
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