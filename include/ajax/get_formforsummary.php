<?php

	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
    
// FINANCIAL REPORT FORMS
if(isset($_POST['id_summary'])){

    if($_POST['id_summary'] == 1){
        // Class-wise Challans Details
        echo'
        <div class="col-sm-4">
            <label class="control-label">Class <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_classsection(this.value)">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                        FROM ".CLASSES."
                                        WHERE class_status = '1' 
                                        ORDER BY class_id ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
                }
            echo '
            </select>
        </div>

        <div class="col-sm-4" id="getclasssection">
            <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section" required>
                <option value="">Select</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <div class="">
            <label class="control-label">Month</label>
                <select class="form-control"  title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month">
                    <option value="">Select Month</option>';
                    foreach($monthtypes as $month) {
                    echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                    }
                echo '
                </select>
            </div>
        </div>

        <div class="col-md-12 mb-md">
            <label class="control-label">Select Date </label>
            <div class="input-timerange input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" class="form-control valid" name="start_date" id="start_date" data-plugin-datepicker aria-required="true">
                <span class="input-group-addon">to</span>
                <input type="text" class="form-control" name = "end_date" id="end_date" data-plugin-datepicker aria-required="true">
            </div>
        </div>
        ';
    }elseif($_POST['id_summary'] == 2){
        // Month Wise Challans Summary
        echo'
        <div class="col-md-4">
            <label class="control-label">Session <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session" name="id_session">
                <option value="">Select Session</option>';
                $sqllmssession	= $dblms->querylms("SELECT session_id, session_name 
                                                        FROM ".SESSIONS." 
                                                        WHERE session_status = '1' ORDER BY session_id ASC");
                while($value_session 	= mysqli_fetch_array($sqllmssession)) {
                echo '<option value="'.$value_session['session_id'].'">'.$value_session['session_name'].'</option>';
                }
                echo '
            </select>
        </div>
        
        <div class="col-md-4">
            <label class="control-label">From Month</label>
            <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="start_month">
                <option value="">From Month</option>';
                foreach($monthtypes as $month) {
                echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                }
            echo '
            </select>
        </div>

        <div class="col-md-4 mb-md">
            <label class="control-label">To Month</label>
            <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="end_month">
                <option value="">To Month</option>';
                foreach($monthtypes as $month) {
                echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                }
            echo '
            </select>
        </div>

        ';
    }elseif($_POST['id_summary'] == 3){
        // Individual Student Ledger
        echo'
        <div class="col-sm-4 col-md-4">
            <label class="control-label">Class <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class" onchange="get_classsection(this.value)">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                                    FROM ".CLASSES."
                                                    WHERE class_status = '1' 
                                                    ORDER BY class_id ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
                }
            echo '
            </select>
        </div>

        <div class="col-sm-4 col-md-4" id="getclasssection">
            <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section" required>
                <option value="">Select</option>
            </select>
        </div>

        <div id="getstudent">
            <div class="col-sm-4 col-md-4">
                <label class="control-label">Student <span class="required">*</span></label>
                <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_std" name="id_std" required>
                    <option value="">Select</option>
                </select>
            </div>
        </div>

        <div class="col-sm-4 col-md-4">
            <div class="">
            <label class="control-label">Month</label>
                <select class="form-control"  title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month">
                    <option value="">Select Month</option>';
                    foreach($monthtypes as $month) {
                    echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                    }
                echo '
                </select>
            </div>
        </div>

        <div class="col-sm-8 col-md-8">
            <label class="control-label">Select Date </label>
            <div class="input-timerange input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" class="form-control valid" name="start_date" id="start_date" data-plugin-datepicker aria-required="true">
                <span class="input-group-addon">to</span>
                <input type="text" class="form-control" name = "end_date" id="end_date" data-plugin-datepicker aria-required="true">
            </div>
        </div>
        ';
    }elseif($_POST['id_summary'] == 4){
        // Class Wise Fees collection Report
        echo'
        <div class="col-sm-4">
            <label class="control-label">Class <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_classsection(this.value)">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                        FROM ".CLASSES."
                                        WHERE class_status = '1' 
                                        ORDER BY class_id ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
                }
            echo '
            </select>
        </div>

        <div class="col-sm-4" id="getclasssection">
            <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section" required>
                <option value="">Select</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <div class="">
            <label class="control-label">Month</label>
                <select class="form-control"  title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month">
                    <option value="">Select Month</option>';
                    foreach($monthtypes as $month) {
                    echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                    }
                echo '
                </select>
            </div>
        </div>

        <div class="col-md-12 mb-md">
            <label class="control-label">Select Date </label>
            <div class="input-timerange input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" class="form-control valid" name="start_date" id="start_date" data-plugin-datepicker aria-required="true">
                <span class="input-group-addon">to</span>
                <input type="text" class="form-control" name = "end_date" id="end_date" data-plugin-datepicker aria-required="true">
            </div>
        </div>
        ';
    }elseif($_POST['id_summary'] == 5){
        // Accumulative Fees Collection Report
        echo'
        <div class="col-sm-4">
            <label class="control-label">Class <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_classsection(this.value)">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                        FROM ".CLASSES."
                                        WHERE class_status = '1' 
                                        ORDER BY class_id ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
                }
            echo '
            </select>
        </div>

        <div class="col-sm-4" id="getclasssection">
            <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section" required>
                <option value="">Select</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <div class="">
            <label class="control-label">Month</label>
                <select class="form-control"  title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month">
                    <option value="">Select Month</option>';
                    foreach($monthtypes as $month) {
                    echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                    }
                echo '
                </select>
            </div>
        </div>

        <div class="col-md-12 mb-md">
            <label class="control-label">Select Date </label>
            <div class="input-timerange input-group">
                <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" class="form-control valid" name="start_date" id="start_date" data-plugin-datepicker aria-required="true">
                <span class="input-group-addon">to</span>
                <input type="text" class="form-control" name = "end_date" id="end_date" data-plugin-datepicker aria-required="true">
            </div>
        </div>
        ';
    }elseif($_POST['id_summary'] == 6){
        // Accumulative Fees Collection Report
        echo'
        <div class="col-md-6">
            <label class="control-label">Session <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session" name="id_session">
                <option value="">Select Session</option>';
                $sqllmssession	= $dblms->querylms("SELECT session_id, session_name 
                                                        FROM ".SESSIONS." 
                                                        WHERE session_status = '1' ORDER BY session_id ASC");
                while($value_session 	= mysqli_fetch_array($sqllmssession)) {
                echo '<option value="'.$value_session['session_id'].'">'.$value_session['session_name'].'</option>';
                }
                echo '
            </select>
        </div>

        <div class="col-md-6">
            <label class="control-label">Class <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_classsection(this.value)">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                        FROM ".CLASSES."
                                        WHERE class_status = '1' 
                                        ORDER BY class_id ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
                }
            echo '
            </select>
        </div>

        <div class="col-md-6" id="getclasssection">
            <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section" required>
                <option value="">Select</option>
            </select>
        </div>
        
        <div class="col-md-6">
            <div class="">
            <label class="control-label">Start Month  <span class="required">*</span></label>
                <select class="form-control"  title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month" required>
                    <option value="">Select Month</option>';
                    foreach($monthtypes as $month) {
                    echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                    }
                echo '
                </select>
            </div>
        </div>
        ';
    }elseif($_POST['id_summary'] == 7){
        // Student Fee receipt details
        echo'
        <div class="col-md-6">
            <label class="control-label">Session <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session" name="id_session">
                <option value="">Select Session</option>';
                $sqllmssession	= $dblms->querylms("SELECT session_id, session_name 
                                                        FROM ".SESSIONS." 
                                                        WHERE session_status = '1' ORDER BY session_id ASC");
                while($value_session 	= mysqli_fetch_array($sqllmssession)) {
                echo '<option value="'.$value_session['session_id'].'">'.$value_session['session_name'].'</option>';
                }
                echo '
            </select>
        </div>

        <div class="col-md-6">
            <label class="control-label">Class <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_classsection(this.value)">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                        FROM ".CLASSES."
                                        WHERE class_status = '1' 
                                        ORDER BY class_id ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
                }
            echo '
            </select>
        </div>

        <div class="col-md-6" id="getclasssection">
            <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section" required>
                <option value="">Select</option>
            </select>
        </div>
        
        <div class="col-md-6">
            <div class="">
            <label class="control-label">Start Month  <span class="required">*</span></label>
                <select class="form-control"  title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month" required>
                    <option value="">Select Month</option>';
                    foreach($monthtypes as $month) {
                    echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
                    }
                echo '
                </select>
            </div>
        </div>
        ';
    }
}

echo'
<!-- THEME INITIALIZATION FILES -->
<script src="assets/javascripts/theme.init.js"></script>
';
?>