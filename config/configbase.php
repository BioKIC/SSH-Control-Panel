<?php
header('X-Frame-Options: DENY');
header('Cache-control: private'); // IE 6 FIX
date_default_timezone_set('America/Phoenix');

set_include_path(get_include_path() . PATH_SEPARATOR . $SERVER_ROOT . PATH_SEPARATOR . $SERVER_ROOT.'/config/' . PATH_SEPARATOR . $SERVER_ROOT.'/classes/');

session_start(array('gc_maxlifetime'=>3600,'cookie_path'=>$CLIENT_ROOT,'cookie_secure'=>(isset($COOKIE_SECURE)&&$COOKIE_SECURE?true:false),'cookie_httponly'=>true));

include_once($SERVER_ROOT.'/classes/Encryption.php');
include_once($SERVER_ROOT.'/classes/ProfileManager.php');

//Check cookie to see if signed in
$PARAMS_ARR = Array();
$USER_RIGHTS = Array();
if(isset($_SESSION['userparams'])) $PARAMS_ARR = $_SESSION['userparams'];
if(isset($_SESSION['userrights'])) $USER_RIGHTS = $_SESSION['userrights'];

$USER_DISPLAY_NAME = (array_key_exists('dn',$PARAMS_ARR)?$PARAMS_ARR['dn']:'');
$USERNAME = (array_key_exists('un',$PARAMS_ARR)?$PARAMS_ARR['un']:0);
$UID = (array_key_exists('uid',$PARAMS_ARR)?$PARAMS_ARR['uid']:0);
$IS_ADMIN = (array_key_exists('SuperAdmin',$USER_RIGHTS)?1:0);
?>