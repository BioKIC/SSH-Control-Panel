<?php
include_once('config/cpini.php');
include_once($SERVER_ROOT.'/classes/SiteMapManager.php');
header('Content-Type: text/html; charset=UTF-8');

$submitAction = array_key_exists('submitaction',$_REQUEST)?$_REQUEST['submitaction']:'';
$smManager = new SiteMapManager();
?>
<html>
<head>
	<title>SSH - <?php echo $LANG['SITEMAP']; ?></title>
	<?php
	include_once($SERVER_ROOT.'/includes/head.php');
	include_once($SERVER_ROOT.'/includes/googleanalytics.php');
	?>
	<script type="text/javascript">

	</script>
</head>
<body>
	<?php
	include($SERVER_ROOT.'/includes/header.php');
	?>
	<div class="navpath">
		<a href="index.php"><?php echo $LANG['HOME']; ?></a> &gt;
		<b><?php echo $LANG['SITEMAP']; ?></b>
	</div>

	<div id="innertext">
		<h1><?php echo $LANG['SITEMAP']; ?></h1>
		<div style="margin:10px;">
			<h2><?php echo $LANG['COLLECTIONS']; ?></h2>
			<ul>
				<li><a href="collections/index.php"><?php echo $LANG['SEARCHENGINE'];?></a> - <?php echo $LANG['SEARCH_COLL'];?></li>
				<li><a href="collections/misc/collprofiles.php"><?php echo $LANG['COLLECTIONS'];?></a> - <?php echo $LANG['LISTOFCOLL'];?></li>
				<li><a href="collections/misc/collstats.php"><?php echo $LANG['COLLSTATS'];?></a></li>
			</ul>
			<fieldset style="margin:30px 0px 10px 10px;padding-left:25px;padding-right:15px;">
				<legend><b><?php echo $LANG['MANAGTOOL'];?></b></legend>
				<?php
				if($SYMB_UID){
					if($IS_ADMIN){
						?>
						<h3><?php echo $LANG['ADMIN'];?></h3>
						<ul>
							<li>
								<a href="profile/usermanagement.php"><?php echo $LANG['USERPERM'];?></a>
							</li>
						</ul>
						<?php
					}
				}
				else{
					echo ''.$LANG['PLEASE'].' <a href="'.$CLIENT_ROOT.'/profile/index.php?refurl=../sitemap.php">'.$LANG['LOGIN'].'</a>'.$LANG['TOACCESS'].'<br/>'.$LANG['CONTACTPORTAL'].'.';
				}
			?>
			</fieldset>

			<h2><?php echo $LANG['ABOUT'];?></h2>
			<ul>
				<li>
					<?php echo $LANG['SCHEMA'].' '.$smManager->getSchemaVersion(); ?>
				</li>
			</ul>
		</div>
	</div>
	<?php
	include($SERVER_ROOT.'/includes/footer.php');
	?>
</body>
</html>