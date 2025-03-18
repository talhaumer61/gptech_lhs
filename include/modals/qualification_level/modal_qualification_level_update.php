<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1)){ 
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT  q.q_l_id, q.q_l_name, q.q_l_code, q.q_l_status
								   		FROM ".QUALIFICATION_LEVELS." q  
										WHERE  q.q_l_id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="qualification_level.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="q_l_id" id="q_l_id" value="'.cleanvars($_GET['id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Qualification Level</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-4 control-label">Qualification Level Name <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="q_l_name" id="q_l_name" required title="Must Be Required" value="'.$rowsvalues['q_l_name'].'" />
				</div>
			</div>
			
			<div class="form-group mt-sm">
				<label class="col-md-4 control-label">Qualification Level Code</label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="q_l_code" id="q_l_code" value="'.$rowsvalues['q_l_code'].'" />
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Status <span class="required">*</span></label>
				<div class="col-md-8">';
					if($rowsvalues['q_l_status'] == 1) { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="q_l_status" name="q_l_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>';
					} else { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="q_l_status" name="q_l_status" value="1">
								<label for="radioExample1">Active</label>
							</div>';
					}
					if($rowsvalues['q_l_status'] == 2) { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="q_l_status" name="q_l_status" checked value="2">
								<label for="radioExample2">Inactive</label>
							</div>';
					} else { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="q_l_status" name="q_l_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>';
					}
			echo '
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_qualification_level" name="changes_qualification_level">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
//---------------------------------------------------------