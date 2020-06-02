<?php
	if (defined('SELF_CALLED'))
	{
?>
		<footer class="footer">
			<div class="footer-wrap">
				<div class="w-100 clearfix">
					<span class="d-block text-center text-sm-left d-sm-inline-block"><?= $ConfigTools->config_item('footer'); ?></span>
					<span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><?= $ConfigTools->config_item('footer_right'); ?></span>
				</div>
			</div>
		</footer>
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