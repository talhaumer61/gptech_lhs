<?php 
//error_reporting(0);
//session_start();
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";

if(isset($_POST['id_class']) && isset($_POST['id_section'])) {

	$class      =   $_POST['id_class']; 
	$section    =   $_POST['id_section']; 

    echo '
    <div class="col-sm-4">
        <label class="control-label">Student <span class="required">*</span></label>
        <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_std" name="id_std" required>
            <option value="">Select</option>';
            $sqllmscls	= $dblms->querylms("SELECT std_id , std_name
                                            FROM ".STUDENTS."
                                            WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
                                            AND id_class    = '".$class."' 
                                            AND id_section  = '".$section."' 
                                            AND is_deleted != '1'
                                            ORDER BY std_name ASC");
            if(mysqli_num_rows($sqllmscls) > 0){
                while($valuecls = mysqli_fetch_array($sqllmscls)) {
                    echo '<option value="'.$valuecls['std_id'].'">'.$valuecls['std_name'].'</option>';
                }
            }else{
                echo '<option value="">No Record Found</option>';
            }
                
        echo '
        </select>
    </div>';
}
?>