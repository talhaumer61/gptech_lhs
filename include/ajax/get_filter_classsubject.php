<?php 
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
//--------------------------------------------
if(isset($_POST['id_class'])) {
	$class = $_POST['id_class']; 
//--------------------------------------------
echo '
<select class="form-control" data-width="100%" data-minimum-results-for-search="Infinity" name="id_subject">
<option value="">Select</option>';
    $sqllmscls	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
                            FROM ".CLASS_SUBJECTS."
                            WHERE subject_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
                            ORDER BY subject_name ASC");
    while($valuecls = mysqli_fetch_array($sqllmscls)) {
        echo '<option value="'.$valuecls['subject_id'].'">'.$valuecls['subject_code'].' - '.$valuecls['subject_name'].'</option>';
    }
echo '
</select>';
//---------------------------------------
}
?>