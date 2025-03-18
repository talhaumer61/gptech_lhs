<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))){
echo '
<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-primary">
			<form action="students.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Student</h4>
				</div>
				<div class="panel-body">
					<label class="control-label">Photo</label>
					<div class="row">
						<div class="col-md-6">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
									<img src="uploads/default-student.jpg" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px">
									
								</div>
								<div>
									<span class="btn btn-xs btn-default btn-file">
										<span class="fileinput-new">Select image</span>
										<span class="fileinput-exists">Change</span>
										<input type="file" name="std_photo" accept="image/*">
									</span>
									<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-sm">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Form Number </label>
								<input type="text" class="form-control" name="form_no" id="form_no" title="Must Be Required" autofocus onchange="get_formno(this.value)">
							</div>
						</div>
					</div>
					<div id="getadmissiondetail">
						<div class="row mt-sm">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Student Name <span class="required">*</span></label>
									<input type="text" class="form-control" name="std_name" id="std_name" required title="Must Be Required" autofocus>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Father Name <span class="required">*</span></label>
									<input type="text" class="form-control" name="std_fathername" id="std_fathername" required title="Must Be Required" >
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Father CNIC <span class="required">*</span></label>
									<input type="text" class="form-control" name="std_fathercnic" id="std_fathercnic" required title="Must Be Required" >
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Gender <span class="required">*</span></label>
									<select name="std_gender" data-plugin-selectTwo data-minimum-results-for-search="Infinity" data-width="100%" class="form-control populate" required title="Must Be Required">
										<option value="">Select</option>
										<option value="Male" >Male</option>
										<option value="Female" >Female</option>
									</select>
								</div>
							</div>	
						</div>
						<div class="row mt-sm">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">NIC / B-Form</label>
									<input type="number" class="form-control" name="std_nic" id="std_nic"">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Guardian</label>
									<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_guardian">
										<option value="">Select</option>';
									foreach($guardian as $value){
									echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
									}
									echo '
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Phone <span class="required">*</span></label>
									<input type="number" class="form-control" name="std_phone" id="std_phone" required title="Must Be Required">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Emergency Contact </label>
									<input type="number" class="form-control" name="std_emergency_phone" id="std_emergency_phone" >
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Whatsapp </label>
									<input type="number" class="form-control" id="std_whatsapp" name="std_whatsapp">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Date of Birth </label>
									<input type="text" class="form-control" name="std_dob" id="std_dob" data-plugin-datepicker>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Blood Group </label>
									<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="std_bloodgroup">
										<option value="">Select</option>';
										foreach($bloodgroup as $listblood){
											echo '<option value="'.$listblood.'">'.$listblood.'</option>';
										}
										echo '
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Religion </label>
									<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="std_religion">
										<option value="">Select</option>';
										foreach($religion as $rel)
										{
											echo' <option value="'.$rel.'">'.$rel.'</option>';
										}
										echo'
									</select>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Group </label>
									<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_group">
										<option value="">Select</option>';
											$sqllmscls	= $dblms->querylms("SELECT group_id, group_name 
																FROM ".GROUPS."
																WHERE group_status = '1'
																ORDER BY group_name ASC");
											while($valuecls = mysqli_fetch_array($sqllmscls)) {
										echo '<option value="'.$valuecls['group_id'].'">'.$valuecls['group_name'].'</option>';
										}
									echo '
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<label class="control-label">Class <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_classsection(this.value)">
									<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
														FROM ".CLASSES."
														WHERE class_status = '1' 
														ORDER BY class_id ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo'<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
									}
								echo '
								</select>
							</div>
							<div class="col-sm-3">
								<div class="form-group" id="getclasssection">
									<label class="control-label">Section </label>
									<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_section">
										<option value="">Select</option>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<label class="control-label">Session <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session">
									<option value="">Select</option>';
									$sqllmsSession	= $dblms->querylms("SELECT session_id, session_status, session_name 
														FROM ".SESSIONS."
														WHERE session_status	= '1'
														AND is_deleted			= '0'
														ORDER BY session_id ASC");
									while($valueSession = mysqli_fetch_array($sqllmsSession)) {
										echo '<option value="'.$valueSession['session_id'].'|'.$valueSession['session_name'].'" '.($valueSession['session_id']==$_SESSION['userlogininfo']['ACADEMICSESSION'] ? 'selected' : '').'>'.$valueSession['session_name'].'</option>';
									}
								echo '
								</select>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Roll No.</label>
									<input type="text" class="form-control" name="std_rollno" id="std_rollno">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Admission Date <span class="required">*</span></label>
									<input type="text" class="form-control" name="std_admissiondate" id="std_admissiondate" data-plugin-datepicker required title="Must Be Required">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">City</label>
									<input type="text" class="form-control" name="std_city" id="std_city">
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label">Current Address </label>
									<textarea type="text" class="form-control" name="std_c_address" id="std_c_address"></textarea>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label">Permanent Address </label>
									<textarea type="text" class="form-control" name="std_p_address" id="std_p_address"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-sm-1 control-label mt-sm">Is New Student <span class="required">*</span></label>
						<div class="col-md-11">
							<div class="checkbox-custom checkbox-inline mt-sm">
								<input type="checkbox" class="is_new" id="is_new" name="is_new" value="1">
								<label for="checkboxExample1"></label>
							</div>
						</div>
					</div>
  					<div id="get_admission_challan"></div>
					<div class="form-group mt-xs">
						<label class="col-sm-1 control-label mt-sm">Status <span class="required">*</span></label>
						<div class="col-md-11 pull-left">
							<div class="radio-custom radio-inline mt-sm">
								<input type="radio" id="std_status" name="std_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="std_status" name="std_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
					<div class="alert alert-danger"> Please First Add Section To Add Student.</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" id="submit_student" name="submit_student" class="mr-xs btn btn-primary">Add Student</button>
							<button type="reset" class="btn btn-default">Reset</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>
</div>';
}
else{
	header("Location: students.php");
}
?>