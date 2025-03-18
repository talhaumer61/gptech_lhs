<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'updated' => '1'))){
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT  c.class_id, c.class_status, c.class_code, c.class_name, c.id_classlevel
								   		FROM ".CLASSES." c  
										WHERE c.class_id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="class.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="class_id" id="class_id" value="'.cleanvars($_GET['id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Class</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Class Name <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="class_name" id="class_name" required title="Must Be Required" value="'.$rowsvalues['class_name'].'" />
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Class Numeric <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="class_code" id="class_code" required title="Must Be Required" value="'.$rowsvalues['class_code'].'" />
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Class Level<span class="required">*</span></label>
				<div class="col-md-9">
					<select data-plugin-selectTwo data-width="100%" name="id_classlevel" id="id_classlevel" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						foreach ($classlevel as $level):
							echo'<option value="'.$level['id'].'" '.($level['id']==$rowsvalues['id_classlevel'] ? 'selected' : '').'>'.$level['name'].'</option>';
						endforeach;
						echo'
					</select>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Class Subjects </label>
				<div class="col-md-9">
					<input type="file" class="form-control" name = "class_attachment" id="class_attachment" accept=".pdf">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="class_status" name="class_status" value="1"'; if($rowsvalues['class_status'] == 1) { echo 'checked';} echo'>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="class_status" name="class_status" value="2"'; if($rowsvalues['class_status'] == 2) { echo'checked';} echo'>
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_class" name="changes_class">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
?>