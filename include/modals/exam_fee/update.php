<?php 

	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
	
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) ||($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '21', 'updated' => '1'))){ 
	
	$sqllms	= $dblms->querylms("SELECT  *
								FROM ".EXAM_FEE."
								WHERE id = '".cleanvars($_GET['id'])."' 
								LIMIT 1
							  ");
	$row = mysqli_fetch_array($sqllms);
	
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="campuses.php?id='.cleanvars($row['id_campus']).'"  class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Exam Fee</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-4 control-label">Exam Type <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
					<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($row['id_campus']).'">
					<select name="id_exam_type" data-plugin-selectTwo data-width="100%" id="id_exam_type" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
							$sqllmsexam	= $dblms->querylms("SELECT type_id, type_status, type_name 
															FROM ".EXAM_TYPES."
															WHERE type_status = '1' 
															AND is_deleted = '0'
															AND type_status = '1'
															ORDER BY type_id ASC");
							while($valueexam = mysqli_fetch_array($sqllmsexam)) {
								echo '<option value="'.$valueexam['type_id'].'" '.($row['id_exam_type'] == $valueexam['type_id'] ? 'selected' : '').'>'.$valueexam['type_name'].'</option>';
							}
						echo'
					</select>
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Fee Per Student  <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" required name="fee_per_std" value="'.$row['fee_per_std'].'" id="fee_per_std"></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-4 control-label">Status <span class="required">*</span></label>
				<div class="col-md-8">
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" '.($row['status'] == '1' ? 'checked' : '').' value="1">
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" '.($row['status'] == '2' ? 'checked' : '').' value="2">
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="update_exam_fee" name="update_exam_fee">Update</button>
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