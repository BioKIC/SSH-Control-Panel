<?php
include_once('config/cpini.php');
header("Content-Type: text/html; charset=UTF-8");
?>
<html>
	<head>
		<title>SSH - Control Panel Index</title>
		<?php
		include_once($SERVER_ROOT.'/includes/head.php');
		include_once($SERVER_ROOT.'/includes/googleanalytics.php');
		?>
	</head>
	<body>
		<?php
		include($SERVER_ROOT.'/includes/header.php');
		?>
		<div id="innertext">


			<fieldset>
				<legend>Option Panel</legend>
				<ul>
					<li><a href="stats/index.php">Get network statistics</a></li>
				</ul>
			</fieldset>
		</div>
		<?php
		include($SERVER_ROOT.'/includes/footer.php');
		?>
	</body>
</html>
