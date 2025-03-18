<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_class'])){
    echo'<option value="">Select</option>';
    $sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
                                    FROM ".CLASS_SECTIONS."
                                    WHERE id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                    AND id_class        = '".$_POST['id_class']."'
                                    AND section_status  = '1'
                                    AND is_deleted      = '0'
                                    ORDER BY section_name ASC");
    if(mysqli_num_rows($sqllmscls) > 0){
        while($valuecls = mysqli_fetch_array($sqllmscls)) {
            echo '<option value="'.$valuecls['section_id'].'" '.($valuecls['section_id']==$section ? 'selected' : '').'>'.$valuecls['section_name'].'</option>';
        }
    }else{
        echo '<option value="">No Record Found</option>';
    }
}
?>