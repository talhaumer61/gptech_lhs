<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'edit' => '1'))){ 
	$sqllms	= $dblms->querylms("SELECT id, status, id_exam, file, id_sbuject_cat, note, id_session, id_class
									FROM ".EXAM_DOWNLOADS." 
									WHERE id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="exam_scheme.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Assessment Scheme</h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Session <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_session" name="id_session">
							<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT session_id, session_name 
														FROM ".SESSIONS."
														WHERE session_status = '1'
														ORDER BY session_name DESC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['session_id'].'" '.($valuecls['session_id'] == $rowsvalues['id_session']? 'selected': '').'>'.$valuecls['session_name'].'</option>';
								}
								echo '
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Type <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_exam" name="id_exam">
							<option value="">Select</option>';
							foreach(get_scheme_type() as $key => $value) {
								echo '<option value="'.$key.'" '.($rowsvalues['id_exam'] == $key ? 'selected' : '').'>'.$value.'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Class <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class">
							<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
														FROM ".CLASSES."
														WHERE class_status = '1'
														ORDER BY class_name DESC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id'] == $rowsvalues['id_class']? 'selected': '').'>'.$valuecls['class_name'].'</option>';
								}
								echo '
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_sbuject_cat" name="id_sbuject_cat">
							<option value="">Select</option>';
							foreach($subjectcat as $cat) {
								echo '<option value="'.$cat['id'].'" '.($rowsvalues['id_sbuject_cat'] == $cat['id'] ? 'selected' : '').'>'.$cat['name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">File </label>
					<div class="col-md-9">
						<input type="file" class="form-control" name="file" id="file" value="'.$rowsvalues['file'].'"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Note</label>
					<div class="col-md-9">
						<textarea class="form-control" rows="2" name="note" id="note">'.$rowsvalues['note'].'</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="1" '.($rowsvalues['status'] == 1 ? 'checked' : '').'>
							<label for="radioExample1">Active</label>
						</div>
				
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="2" '.($rowsvalues['status'] == 2 ? 'checked' : '').'>
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="changes_scheme" name="changes_scheme">Update</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}
?>