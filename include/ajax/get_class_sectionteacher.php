<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_class'])){
	$class = $_POST['id_class'];
	if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){ $id_campus = $_POST['id_campus']; }else{ $id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];	}
	
	echo'
	<div class="col-sm-3 mt-sm">
		<label class="control-label">Section</label>
		<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section">
			<option value="">Select</option>';
			$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
											FROM ".CLASS_SECTIONS."
											WHERE id_class		= '".$class."'
											AND id_campus		= '".cleanvars($id_campus)."'
											AND section_status	= '1'
											AND is_deleted		= '0'
											ORDER BY section_name ASC");
			while($valuecls = mysqli_fetch_array($sqllmscls)) {
				echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
			}
			echo'
		</select>
	</div>
	<div class="col-md-3 mt-sm">
		<label class="control-label">Teacher </label>
		<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_teacher" name="id_teacher">
			<option value="">Select</option>';
			$sqlTecher	= $dblms->querylms("SELECT GROUP_CONCAT(DISTINCT(td.id_subject)) as subjects, td.id_teacher, e.emply_name
												FROM ".TIMETABLE." t
												INNER JOIN ".TIMETABEL_DETAIL." td ON td.id_setup = t.id
												INNER JOIN ".EMPLOYEES." e ON e.emply_id = td.id_teacher
												WHERE t.status		= '1'
												AND t.is_deleted	= '0'
												AND t.id_class		= '".$class."'
												AND t.id_campus		= '".cleanvars($id_campus)."'
												$sqlSection_t
												GROUP BY td.id_teacher
											");
			while($valTeacher = mysqli_fetch_array($sqlTecher)) {
				echo '<option value="'.$valTeacher['id_teacher'].'|'.$class.'|'.$valTeacher['subjects'].'" '.($valTeacher['id_teacher']==$id_teacher ? 'selected' : '').'>'.$valTeacher['emply_name'].'</option>';
			}
			echo'
		</select>
	</div>';
}
?>