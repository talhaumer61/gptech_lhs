<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_class'])) {
	$id_class = $_POST['id_class'];
    echo'
    <div class="col-sm-3">
        <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" name="id_section">
                <option value="">Select</option>';
                $sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
                                                FROM ".CLASS_SECTIONS."
                                                WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                AND section_status = '1' AND id_class = '".$id_class."' AND is_deleted != '1'
                                                ORDER BY section_name ASC");
                while($valuecls = mysqli_fetch_array($sqllmscls)) {
                    echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
                }
                echo'
            </select>
        </div>
    </div>
    <div class="col-sm-3">
        <label class="control-label">Subject <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_subject">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
                                                    FROM ".CLASS_SUBJECTS."
                                                    WHERE subject_status = '1' AND id_class = '".$id_class."' AND is_deleted != '1'
                                                    ORDER BY subject_name ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                        echo '<option value="'.$valuecls['subject_id'].'">'.$valuecls['subject_code'].' - '.$valuecls['subject_name'].'</option>';
                    }
                    echo'
            </select>
        </div>
    </div>';
}
