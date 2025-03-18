<?php 
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'edit' => '1'))){

		$sqllms	= $dblms->querylms("SELECT  cir_id, cir_status, cir_subject, cir_dated, cir_refrence, cir_addressto, cir_dated, cir_details, cir_regards, id_designation
											FROM ".CIRCULARS."
											WHERE cir_id != '' AND id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
											AND cir_id = '".cleanvars($_GET['id'])."' LIMIT 1");
		$rowsvalues = mysqli_fetch_array($sqllms);
		
		$dated = date('m/d/Y', strtotime($rowsvalues['cir_dated']));

		echo '
		<script src="assets/javascripts/user_config/forms_validation.js"></script>
		<script src="assets/javascripts/theme.init.js"></script>
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<form action="circular.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<input type="hidden" name="cir_id" id="cir_id" value="'.cleanvars($_GET['id']).'">
						<header class="panel-heading">
							<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Circular</h2>
						</header>
						<div class="panel-body">
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Refrence <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="cir_refrence" id="cir_refrence" value="'.$rowsvalues['cir_refrence'].'" required title="Must Be Required">
								</div>
							</div>
							<div class="form-group mt-sm">
								<label class="col-md-3 control-label"> Subject <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="cir_subject" id="cir_subject" value="'.$rowsvalues['cir_subject'].'" required title="Must Be Required"/>
								</div>
							</div>
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Address To <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="cir_addressto" id="cir_addressto" value="'.$rowsvalues['cir_addressto'].'" required title="Must Be Required">
								</div>
							</div>
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Date <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" data-plugin-datepicker value="'.$dated.'" required title="Must Be Required" name="cir_dated" id="cir_dated"/>
								</div>
							</div>
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Details <span class="required">*</span></label>
								<div class="col-md-9">
									<textarea data-plugin-summernote class=""form-group summernote summernoteEx" name="cir_details" id="cir_details" required title="Must Be Required">'.$rowsvalues['cir_details'].'</textarea>
								</div>
							</div>
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Regards <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="cir_regards" id="cir_regards" value="'.$rowsvalues['cir_regards'].'" required title="Must Be Required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Designation <span class="required">*</span></label>
								<div class="col-md-9">
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_designation">
										<option value="">Select</option>';
											$sqllmsdesg	= $dblms->querylms("SELECT designation_id, designation_name 
																			FROM ".DESIGNATIONS."
																			WHERE designation_status = '1' AND is_deleted != '1'
																			ORDER BY designation_name ASC
																		  ");
											while($valuedesg = mysqli_fetch_array($sqllmsdesg)) {
												echo '<option value="'.$valuedesg['designation_id'].'"'; if($valuedesg['designation_id'] == $rowsvalues['id_designation']){echo'selected';} echo'>'.$valuedesg['designation_name'].'</option>';
											}
											echo'
									</select>
								</div>
							</div>

							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Digital Signature </label>
								<div class="col-md-9">
									<input type="file" class="form-control" name="digital_signature" id="digital_signature" >
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
								<div class="col-md-9">
									<div class="radio-custom radio-inline">
										<input type="radio" id="cir_status" name="cir_status" value="1" '; if($rowsvalues['cir_status'] == 1) {echo 'checked';} echo'>
										<label for="radioExample1">Active</label>
									</div>
									<div class="radio-custom radio-inline">
										<input type="radio" id="cir_status" name="cir_status" value="2"'; if($rowsvalues['cir_status'] == 2) {echo 'checked';} echo'>
										<label for="radioExample2">Inactive</label>
									</div>
								</div>
							</div>
						</div>
						<footer class="panel-footer">
							<div class="row">
								<div class="col-md-12 text-right">
									<button type="submit" class="btn btn-primary" id="changes_circular" name="changes_circular">Update</button>
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