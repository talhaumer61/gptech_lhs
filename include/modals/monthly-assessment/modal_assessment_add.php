<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'add' => '1'))){ 
echo'
<div id="make_assessment" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="monthly_assessment.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Monthly Assessment</h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Session <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session">
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT session_id, session_status, session_name 
																FROM ".SESSIONS."
																WHERE session_status = '1'
																ORDER BY session_name DESC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['session_id'].'">'.$valuecls['session_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Term <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="syllabus_term">
							<option value="">Select</option>';
							$sqlTerms	= $dblms->querylms("SELECT term_id, term_status, term_name 
															FROM ".EXAM_TERMS."
															WHERE term_status = '1'
															ORDER BY term_id ASC");
							while($valTerms = mysqli_fetch_array($sqlTerms)) {
								echo '<option value="'.$valTerms['term_id'].'">'.$valTerms['term_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Assessment <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month">
							<option value="">Select</option>';
							for($i = 1; $i<=3; $i++ ){
								echo '<option value="'.$i.'">Assessment : '.$i.'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Class <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class" onchange="get_classsubject(this.value)">
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
													FROM ".CLASSES."
													WHERE class_status = '1'
													ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div id="getclasssubject">
					<div class="form-group  mb-md">
						<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_subject">
								<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
														FROM ".CLASS_SUBJECTS."
														WHERE subject_status = '1' AND id_class = '".$class['class_id']."'
														ORDER BY subject_name ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['subject_id'].'">'.$valuecls['subject_code'].' - '.$valuecls['subject_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">File <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="file" class="form-control" name="syllabus_file" id="syllabus_file" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Note</label>
					<div class="col-md-9">
						<textarea class="form-control" rows="2" name="note" id="note"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="syllabus_status" name="syllabus_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="syllabus_status" name="syllabus_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_assessment" name="submit_assessment">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>