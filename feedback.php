<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

$sqlFeedback  	= $dblms->querylms("SELECT * FROM ".FEEDBACK."
									WHERE id_added = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
									AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
									ORDER BY id DESC LIMIT 1");
if(mysqli_num_rows($sqlFeedback)>0){	
	$_SESSION['msg']['title'] 	= 'Message';
	$_SESSION['msg']['text'] 	= 'Feedback already shared';
	$_SESSION['msg']['type'] 	= 'info';
	header("Location: dashboard.php", true, 301);
}else{
	echo'
	<!doctype html>
	<html class=" sidebar-light sidebar-left-big-icons">
	<head>
	<meta charset="UTF-8">';
	include_once("include/header-css.php");
	echo '
	</head>
	<body class="" data-loading-overlay>
	<section class="body">';
	include_once("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/header-top.php");
	echo'
	<div class="inner-wrapper">';

	// ADD FEEDBACK
	if(isset($_POST['submit_feedback'])) { 
		
		$sqllmscheck  = $dblms->querylms("SELECT id
											FROM ".FEEDBACK." 
											WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
											AND id_added	= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' LIMIT 1");
		if(mysqli_num_rows($sqllmscheck)) {
			$_SESSION['msg']['title'] 	= 'Error';
			$_SESSION['msg']['text'] 	= 'Record Already Exists';
			$_SESSION['msg']['type'] 	= 'error';
			header("Location: feedback.php", true, 301);
			exit();
		}else{
			$sqllms  = $dblms->querylms("INSERT INTO ".FEEDBACK."(
																user_friendly 
																, content_efficiency
																, meet_needs  
																, security_measure
																, percentage  
																, remarks
																, id_campus
																, id_added  
																, date_added															
															) VALUES (
																'".cleanvars($_POST['user_friendly'])."'
																, '".cleanvars($_POST['content_efficiency'])."'
																, '".cleanvars($_POST['meet_needs'])."'
																, '".cleanvars($_POST['security_measure'])."'
																, '".cleanvars($_POST['percentage'])."'
																, '".cleanvars($_POST['remarks'])."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, NOW()
															)"
									);
			$latestId = $dblms->lastestid();
			// MAKE LOG
			if($sqllms){
				$remarks = 'Feedback Shared on behalf of: "'.cleanvars($latestId).'" detail';
				$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																	id_user 
																	, filename 
																	, action
																	, dated
																	, ip
																	, remarks 
																	, id_campus				
																)
				
															VALUES(
																	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																	, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																	, '1'
																	, NOW()
																	, '".cleanvars($ip)."'
																	, '".cleanvars($remarks)."'
																	, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																)
											");
				$_SESSION['msg']['title'] 	= 'Successfully';
				$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
				$_SESSION['msg']['type'] 	= 'success';
				header("Location: dashboard.php", true, 301);
				exit();
			}else{			
				$_SESSION['msg']['title'] 	= 'Error';
				$_SESSION['msg']['text'] 	= 'Record Not Added.';
				$_SESSION['msg']['type'] 	= 'error';
				header("Location: feedback.php", true, 301);
			}
		}
	}

	echo'
	<title>CMS Feedback | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>CMS Feedback</h2>
		</header>
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<form action="feedback.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-lightbulb-o"></i> Feedback</h4>
						</div>
						<div class="panel-body">					
							<div class="row">
								<div class="form-group">
									<div class="col-sm-6">
										<label class="control-label"><b>1) Did you find the interface easy to use and user-friendly? </b></label>
										<div class="row">
											<div class="col-sm-12">
												<div class="radio-custom radio-inline mt-md">
													<input type="radio" id="user_friendly" name="user_friendly" required value="1">
													<label for="radioExample1">Yes</label>
												</div>
												<div class="radio-custom radio-inline">
													<input type="radio" id="user_friendly" name="user_friendly" required value="2">
													<label for="radioExample2">No</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<label class="control-label"><b>2) Do All modules work efficiently? </b></label>
										<div class="row">
											<div class="col-sm-12">
												<div class="radio-custom radio-inline mt-md">
													<input type="radio" id="content_efficiency" name="content_efficiency" required value="1">
													<label for="radioExample1">Yes</label>
												</div>
												<div class="radio-custom radio-inline">
													<input type="radio" id="content_efficiency" name="content_efficiency" required value="2">
													<label for="radioExample2">No</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-6">
										<label class="control-label"><b>3) IS CMS software flexibile enough to meet your needs? </b></label>
										<div class="row">
											<div class="col-sm-12">
												<div class="radio-custom radio-inline mt-md">
													<input type="radio" id="meet_needs" name="meet_needs" required value="1">
													<label for="radioExample1">Yes</label>
												</div>
												<div class="radio-custom radio-inline">
													<input type="radio" id="meet_needs" name="meet_needs" required value="2">
													<label for="radioExample2">No</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<label class="control-label"><b>4) Did you feel confident about the security measures implemented by the CMS software? </b></label>
										<div class="row">
											<div class="col-sm-12">
												<div class="radio-custom radio-inline mt-md">
													<input type="radio" id="security_measure" name="security_measure" required value="1">
													<label for="radioExample1">Yes</label>
												</div>
												<div class="radio-custom radio-inline">
													<input type="radio" id="security_measure" name="security_measure" required value="2">
													<label for="radioExample2">No</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-6">
										<label class="control-label"><b>5) On a scale percentage, how satisfied are you with the CMS software? </b></label>
										<div class="row">
											<div class="col-sm-12">
												<div class="radio-custom radio-inline mt-md">
													<input type="radio" id="percentage" name="percentage" required value="1">
													<label for="radioExample1">50%</label>
												</div>
												<div class="radio-custom radio-inline mt-md">
													<input type="radio" id="percentage" name="percentage" required value="2">
													<label for="radioExample2">70%</label>
												</div>
												<div class="radio-custom radio-inline mt-md">
													<input type="radio" id="percentage" name="percentage" required value="3">
													<label for="radioExample2">90%</label>
												</div>
												<div class="radio-custom radio-inline">
													<input type="radio" id="percentage" name="percentage" required value="4">
													<label for="radioExample2">100%</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<label class="control-label"><b>6) Any suggestions or complaints about the CMS Software. </b></label>
										<textarea type="text" rows="5" class="form-control" name="remarks" id="remarks"></textarea>
									</div>
								</div>
							</div>
						</div>
						<footer class="panel-footer">
							<div class="row">
								<div class="col-md-12 text-right">
									<button type="submit" id="submit_feedback" name="submit_feedback" class="mr-xs btn btn-primary">Share Feedback</button>
									<button type="reset" class="btn btn-default">Reset</button>
								</div>
							</div>
						</footer>
					</form>
				</section>
			</div>
		</div>';
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				<?php 
				if(isset($_SESSION['msg'])) { 
					echo 'new PNotify({
						title	: "'.$_SESSION['msg']['title'].'"	,
						text	: "'.$_SESSION['msg']['text'].'"	,
						type	: "'.$_SESSION['msg']['type'].'"	,
						hide	: true	,
						buttons: {
							closer	: true	,
							sticker	: false
						}
					});';
					unset($_SESSION['msg']);
				}
				?>	
				var datatable = $('#table_export').dataTable({
					bAutoWidth : false,
					ordering: false,
				});
			});
		</script>
		<?php
		echo'
	</section>';
	include_once("include/footer.php");
}
?>