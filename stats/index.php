<?php
include_once('../config/cpini.php');
include_once($SERVER_ROOT.'/classes/StatisticManager.php');
header("Content-Type: text/html; charset=UTF-8");
//if(!$UID) header('Location: '.$CLIENT_ROOT.'/profile/index.php?refurl=../stats/index.php?');

$formSubmit = array_key_exists('formsubmit',$_REQUEST)?$_REQUEST['formsubmit']:'';

//Sanitation
$formSubmit =  filter_var($formSubmit, FILTER_SANITIZE_STRING);

$statsManager = new StatisticManager();

//$isEditor = 0;
$isEditor = 1;
if($UID){
	if($IS_ADMIN){
		$isEditor = 1;
	}
}
?>
<html>
	<head>
		<title>Portal Statisitc</title>
		<?php
		include_once($SERVER_ROOT.'/includes/head.php');
		?>
		<script src="<?php echo $CLIENT_ROOT; ?>/js/jquery.js" type="text/javascript"></script>
		<script src="<?php echo $CLIENT_ROOT; ?>/js/jquery-ui.js" type="text/javascript"></script>
		<script type="text/javascript">

		</script>
	</head>
	<body>
		<?php
		include($SERVER_ROOT.'/includes/header.php');
		?>
		<div class="navpath">
			<a href="<?php echo $CLIENT_ROOT; ?>/index.php">Home</a> &gt;&gt;
			<b>Statistics Page</b>
		</div>
		<div id="innertext">
			<?php
			if($isEditor){
				if($formSubmit == 'displayStats'){
					$statsManager->buildStats($_POST);
					$reportArr = $statsManager->getPortalMeta();
					if($reportArr){
						?>
						<table class="styledtable">
							<tr><th>Name</th><th>Status</th><th># Frontends</th><th colspan="2">Collections</th><th colspan="4">Occurrences</th><th colspan="3">Images</th><th></th><th></th><th>URL</th></tr>

Active/retired TCN
Central URL
Number of front-end installations
Collections	Live Data:
	Snapshot:
	Total:

Occurrences	Live Data:
	Snapshot:
	Research observations:
	Field collections:
	Total occurrences:

Images	Live Data:
	Snapshot collections:
	Observation images:
	Field Collection Images:
	Total images:
						</table>
						<?php
					}
				}
			}
			?>
			<fieldset>
				<legend>Action Panel</legend>
				<form name="statisticForm" method="post" action="index.php">
					<div>
						<input name="" type="checkbox" value="" />
					</div>
					<button name="formsubmit" type="submit" value="displayStats">Display Statistics</button>
				</form>
			</fieldset>
		</div>
		<?php
		include($SERVER_ROOT.'/includes/footer.php');
		?>
	</body>
</html>