<?php 
//-----------------------------------------------
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
//-----------------------------------------------
	include_once("include/header.php");
//-----------------------------------------------
	if($_SESSION['userlogininfo']['LOGINAFOR'] == 1){
		include_once("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/designation.php");
	}
	else{
		header('location: dashboard.php');
	}
//-----------------------------------------------
	include_once("include/footer.php");
//-----------------------------------------------
?>