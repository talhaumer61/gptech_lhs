<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))){
echo '
<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-primary">
			<form action="students.php?view=import_admissions" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<div class="panel-heading">
					<a href="uploads/make_admission_sample.xlsx" class="btn btn-primary btn-xs pull-right mr-sm"><i class="fa fa-download"></i> Excel Formate</a>
					<h4 class="panel-title"><i class="fa fa-plus-square"></i> Import Students</h4>
				</div>
				<div class="panel-body">
					<div class="row mt-sm">
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">Session <span class="text-danger">*</span></label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_session">
									<option value="">Select</option>';
										$sqllmsSession	= $dblms->querylms("SELECT s.session_id, s.session_name
																			FROM ".SESSIONS." s
																			WHERE s.is_deleted = '0' 
																			AND s.session_status = '1'
																			ORDER BY s.session_name ASC");
										foreach($sqllmsSession as $value):
										echo '<option value="'.$value['session_id'].'">'.$value['session_name'].'</option>';
										endforeach;
									echo '
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">Class <span class="text-danger">*</span></label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" onchange="get_section(this.value)" name="id_class">
									<option value="">Select</option>';
										$sqllmsClass	= $dblms->querylms("SELECT c.class_id, c.class_name
																			FROM ".CLASSES." c
																			WHERE c.is_deleted = '0' 
																			AND c.class_status = '1'
																			ORDER BY c.class_id ASC");
										foreach($sqllmsClass as $value):
										echo '<option value="'.$value['class_id'].'">'.$value['class_name'].'</option>';
										endforeach;
									echo '
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">Section <span class="text-danger">*</span></label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_section" id="id_section">
									<option value="">Select</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">Upload File <span class="text-danger">*</span></label>
								<input type="file" class="form-control" name="imported_file" id="imported_file">
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="submit" id="import_submit" name="import_submit" class="mr-xs btn btn-primary">Import</button>
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