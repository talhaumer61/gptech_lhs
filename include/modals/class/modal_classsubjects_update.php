<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'updated' => '1'))){
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT sub.subject_id, sub.subject_status, sub.subject_code, sub.subject_name, sub.weekly_period, sub.monthly_totalmarks, sub.monthly_passmarks, sub.term_totalmarks, sub.term_passmarks, sub.subject_type, sub.instruction_medium, sub.id_cat, sub.id_class
									FROM ".CLASS_SUBJECTS." sub  
									WHERE sub.subject_id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="classsubjects.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="subject_id" id="subject_id" value="'.cleanvars($_GET['id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Subject</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-4 control-label">Subject Code <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="subject_code" id="subject_code" required title="Must Be Required" value="'.$rowsvalues['subject_code'].'" />
				</div>
			</div>
			
			<div class="form-group mt-sm">
				<label class="col-md-4 control-label">Subject Name <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="subject_name" id="subject_name" required title="Must Be Required" value="'.$rowsvalues['subject_name'].'" />
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Subject Type <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="subject_type" required title="Must Be Required">
						<option value="">Select</option>';
						foreach($subjecttype as $type) {
							echo'<option value="'.$type['id'].'"'; if($rowsvalues['subject_type'] == $type['id']){echo'selected';} echo '>'.$type['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Subject Category <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_cat" required title="Must Be Required">
						<option value="">Select</option>';
						foreach($subjectcat as $cat) {
							echo'<option value="'.$cat['id'].'"'; if($rowsvalues['id_cat'] == $cat['id']){echo'selected';} echo '>'.$cat['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			
			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Instruction Medium <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="instruction_medium" required title="Must Be Required">
						<option value="">Select</option>';
						foreach($instrmedium as $med) {
							echo'<option value="'.$med['id'].'"'; if($rowsvalues['instruction_medium'] == $med['id']){echo'selected';} echo '>'.$med['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>

			<div class="form-group mt-sm">
				<label class="col-md-4 control-label">Class <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class">
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
												FROM ".CLASSES." 
												WHERE class_status = '1'
												ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'"'; if($rowsvalues['id_class'] == $valuecls['class_id']){ echo'selected';} echo'>'.$valuecls['class_name'].'</option>';
							}
						echo '
					</select>
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Weekly Lectures <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" name = "weekly_period" id="weekly_period" required title="Must Be Required" min="0" max="10" value="'.$rowsvalues['weekly_period'].'" />
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Monthly Total Marks <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" name = "monthly_totalmarks" id="monthly_totalmarks" required title="Must Be Required" value="'.$rowsvalues['monthly_totalmarks'].'" />
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Monthly Passing Marks <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" name = "monthly_passmarks" id="monthly_passmarks" required title="Must Be Required" value="'.$rowsvalues['monthly_passmarks'].'" />
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Term Total Marks <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" name = "term_totalmarks" id="term_totalmarks" required title="Must Be Required" value="'.$rowsvalues['term_totalmarks'].'" />
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Term Passing Marks <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" name = "term_passmarks" id="term_passmarks" required title="Must Be Required" value="'.$rowsvalues['term_passmarks'].'" />
				</div>
			</div>	

			<div class="form-group">
				<label class="col-md-4 control-label">Status <span class="required">*</span></label>
				<div class="col-md-8">
					<div class="radio-custom radio-inline">
						<input type="radio" id="subject_status" name="subject_status" value="1"'; if($rowsvalues['subject_status'] == 1) {echo'checked';} echo'>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="subject_status" name="subject_status" value="2"'; if($rowsvalues['subject_status'] == 2) {echo'checked';} echo'>
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_subject" name="changes_subject">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
else{
	header("Location: dashboard.php");
}
?>