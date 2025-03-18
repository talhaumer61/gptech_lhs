<?php 
echo'
<!doctype html>
<html class=" sidebar-light sidebar-left-big-icons">
<head>
<meta charset="UTF-8">';
include_once("header-css.php");
echo '
</head>
<body class="" data-loading-overlay>
<section class="body">';
include_once(get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/header-top.php");
echo'
<div class="inner-wrapper">
<!-- INCLUDEING NAVIGATION -->';
include_once(get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/sidebar-left.php");

$sqlstring	= "";
$adjacents	= 2;
if(!($Limit)) 	{ $Limit = 20; } 
if($page)		{ $start = ($page - 1) * $Limit; } else { $start = 0; }
?>