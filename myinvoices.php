<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("api/db_functions.php");
	require_once("include/functions/functions.php");
	require_once("include/functions/login_func.php");

	$api 	= new main();
	$dblms 	= new dblms();

	checkCpanelLMSALogin();
	include_once("include/header.php");
	include_once("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/myinvoices.php");
	include_once("include/footer.php");
?>