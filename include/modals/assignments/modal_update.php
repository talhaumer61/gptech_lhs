<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

$sqlassig = $dblms->querylms("SELECT assig_id, assig_status, assig_title, assig_note, assig_file, open_date, close_date, id_class, id_section, id_subject, id_teacher
								   FROM ".ASSIGNMENT."
								   WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
								   AND assig_id = '".$_GET['edit_id']."'");
$rowsvalues = mysqli_fetch_array($sqlassig);
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<section class="panel panel-featured panel-featured-primary">
	<form action="" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="assig_id" id="assig_id" value="'.cleanvars($_GET['edit_id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Assignment</h2>
		</header>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-md-3 control-label">Class <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required>
						<option value="">Select</option>';
						$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
															FROM ".CLASSES."
															WHERE class_status = '1' 
															ORDER BY class_id ASC");
						while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo'<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id']==$rowsvalues['id_class'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Section <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>
						<option value="">Select</option>';
						$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
														FROM ".CLASS_SECTIONS."
														WHERE id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
														AND id_class        = '".$rowsvalues['id_class']."'
														AND section_status  = '1'
														AND is_deleted      = '0'
														ORDER BY section_name ASC");
						while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo '<option value="'.$valuecls['section_id'].'" '.($valuecls['section_id']==$rowsvalues['id_section'] ? 'selected' : '').'>'.$valuecls['section_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_subject" name="id_subject" required>
						<option value="">Select</option>';
						$sqllmsdetail	= $dblms->querylms("SELECT s.subject_id, s.subject_code, s.subject_name
															FROM ".TIMETABEL_DETAIL." d 
															INNER JOIN ".TIMETABLE." t 	ON t.id = d.id_setup
															INNER JOIN ".CLASSES." c ON c.class_id = t.id_class
															INNER JOIN ".CLASS_SUBJECTS." s ON s.subject_id = d.id_subject
															WHERE t.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' AND t.status = '1' 
															AND t.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
															AND t.id_class		= '".cleanvars($rowsvalues['id_class'])."'
															AND t.id_section	= '".cleanvars($rowsvalues['id_section'])."'
															AND d.id_teacher	= '".cleanvars($rowsvalues['id_teacher'])."' 
															GROUP BY s.subject_id
															ORDER BY t.id ASC");

						echo'<option value="">Select</option>';
						while($valSubject = mysqli_fetch_array($sqllmsdetail)) {
							echo '<option value="'.$valSubject['subject_id'].'|'.$rowsvalues['id_teacher'].'" '.($rowsvalues['id_subject']==$valSubject['subject_id'] ? 'selected' : '').'>'.$valSubject['subject_code'].' - '.$valSubject['subject_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Title <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="assig_title" id="assig_title" value="'.$rowsvalues['assig_title'].'" required title="Must Be Required">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Open <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="input-daterange input-group" data-plugin-datepicker>
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
						<input type="text" class="form-control valid" name="open_date" id="open_date" value="'.date('m/d/Y',strtotime($rowsvalues['open_date'])).'" required="" title="Must Be Required" aria-required="true" aria-invalid="false">
						<span class="input-group-addon">Due </span>
						<input type="text" class="form-control" name = "close_date" id="close_date" value="'.date('m/d/Y',strtotime($rowsvalues['close_date'])).'" required="" title="Must Be Required"  aria-required="true">
					</div>
				</div>
			</div>			
			<div class="form-group">
				<label class="col-md-3 control-label">File <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="file" class="form-control" name="assig_file" id="assig_file" value="'.$rowsvalues['assig_file'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Note</label>
				<div class="col-md-9">
					<textarea class="form-control" rows="2" name="assig_note" id="assig_note">'.$rowsvalues['assig_note'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="assig_status" name="assig_status" value="1" '.($rowsvalues['assig_status']==1 ? 'checked' : '').'>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="assig_status" name="assig_status" value="2" '.($rowsvalues['assig_status']==2 ? 'checked' : '').'>
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_syllabus" name="changes_assignment">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>';
?>