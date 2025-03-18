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
    <select class="form-control populate" data-plugin-selectTwo data-width="100%" id="id_std" name="id_std" required title="Must Be Required" onchange="get_fatherdetail(this.value)">
        <option value="">Select</option>';
            $sqllmsstudent	= $dblms->querylms("SELECT std_id, std_name 
                                FROM ".STUDENTS."
                                WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                AND std_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
                                ORDER BY std_name ASC");
            while($value_stu = mysqli_fetch_array($sqllmsstudent)) {
        echo '<option value="'.$value_stu['std_id'].'">'.$value_stu['std_name'].'</option>';
        }
    echo '
    </select>';
//---------------------------------------
}
?>