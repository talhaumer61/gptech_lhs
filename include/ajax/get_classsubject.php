<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_class']) && !isset($_POST['id_section'])) {
	$class = $_POST['id_class']; 
	echo '
	<div class="form-group mb-md">
		<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
		<div class="col-md-9">
			<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_subject">
				<option value="">Select</option>';
				if($_POST['syllabus_breakdown'] == "syllabus_breakdown"){echo'<option value="0">All Subjects</option>';}
				$sqllmscls	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
										FROM ".CLASS_SUBJECTS."
										WHERE subject_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
										ORDER BY subject_name ASC");
				while($valuecls = mysqli_fetch_array($sqllmscls)) {
					echo '<option value="'.$valuecls['subject_id'].'">'.$valuecls['subject_code'].' - '.$valuecls['subject_name'].'</option>';
				}
				echo'
			</select>
		</div>
	</div>';
}

elseif(isset($_POST['id_teacher'])){
	$aray 		= explode("|", $_POST['id_teacher']);
	$id_teacher	= $aray[0];
	$class		= $aray[1];

	echo'<option value="">Select</option>';
	if($_POST['syllabus_breakdown'] == "syllabus_breakdown"){echo'<option value="0">All Subjects</option>';}
	$sqlSubject	= $dblms->querylms("SELECT td.id_subject, s.subject_code, s.subject_name
									FROM ".TIMETABLE." t
									INNER JOIN ".TIMETABEL_DETAIL." td ON td.id_setup = t.id
									INNER JOIN ".CLASS_SUBJECTS." s ON s.subject_id = td.id_subject 
									WHERE t.id_class		= '".cleanvars($class)."' 
									AND td.id_teacher		= '".cleanvars($id_teacher)."'
									AND s.subject_status	= '1'
									AND s.is_deleted		= '0'
									GROUP BY s.subject_id ASC");
	while($valSubject = mysqli_fetch_array($sqlSubject)) {
		echo '<option value="'.$valSubject['id_subject'].'" '.($valSubject['id_subject']==$id_subject ? 'selected' : '').'>'.$valSubject['subject_code'].' - '.$valSubject['subject_name'].'</option>';
	}
}

elseif(isset($_POST['id_section']) && isset($_POST['id_class'])){
	$sqllmsemp  = $dblms->querylms("SELECT emply_id  
									FROM ".EMPLOYEES." 
									WHERE id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
									AND id_loginid = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' LIMIT 1");
	$value_emp = mysqli_fetch_array($sqllmsemp);

	$sqllmsdetail	= $dblms->querylms("SELECT s.subject_id, s.subject_code, s.subject_name
										FROM ".TIMETABEL_DETAIL." d 
										INNER JOIN ".TIMETABLE." t 	ON t.id = d.id_setup
										INNER JOIN ".CLASSES." c ON c.class_id = t.id_class
										INNER JOIN ".CLASS_SUBJECTS." s ON s.subject_id = d.id_subject
										WHERE t.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' AND t.status = '1' 
										AND t.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND t.id_class		= '".cleanvars($_POST['id_class'])."'
										AND t.id_section	= '".cleanvars($_POST['id_section'])."'
										AND d.id_teacher	= '".cleanvars($value_emp['emply_id'])."' 
										GROUP BY s.subject_id
										ORDER BY t.id ASC");

	echo'<option value="">Select</option>';
	while($valSubject = mysqli_fetch_array($sqllmsdetail)) {
		echo '<option value="'.$valSubject['subject_id'].'|'.$value_emp['emply_id'].'">'.$valSubject['subject_code'].' - '.$valSubject['subject_name'].'</option>';
	}
}
?>