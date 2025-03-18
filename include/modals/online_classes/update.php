<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();

    $today = date('Y-m-d');
    echo $today. $_GET['date'];
    if ($today >= $_GET['date']) {
        $update = 'readonly';
    } else {
        $update = '';
    }
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-primary">
            <form action="" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="zoom_id" id="zoom_id" value="'.cleanvars($_GET['id']).'">
                <header class="panel-heading">
                    <h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Assignment</h2>
                </header>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Title <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="zoom_title" id="zoom_title" value="'.$_GET['tit'].'" required title="Must Be Required" '.$update.'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Date <span class="required">*</span></label>
                        <div class="col-md-9">
                            <div class="input-daterange input-group" data-plugin-datepicker>
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" name="dated" id="dated" value="'.date('m/d/Y' , strtotime(cleanvars($_GET['date']))).'" required title="Must Be Required" '.$update.'>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-md">
                        <label class="col-md-3 control-label">Time <span class="required">*</span></label>
                        <div class="col-md-9">
                            <div class="input-daterange input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                                <input type="time" class="form-control valid" name="start_time" id="start_time" value="'.$_GET['srt'].'" required="" title="Must Be Required" aria-required="true" aria-invalid="false" '.$update.'>
                                <span class="input-group-addon">Due </span>
                                <input type="time" class="form-control" name="end_time" id="end_time" value="'.$_GET['end'].'" required="" title="Must Be Required"  aria-required="true" '.$update.'>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Link <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="zoom_link" id="zoom_link" value="'.$_GET['link'].'" required title="Must Be Required" '.$update.'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Password <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="zoom_pass" id="zoom_pass" value="'.$_GET['pass'].'"  required title="Must Be Required" '.$update.'>
                        </div>
                    </div>
                    <div class="form-group mb-md">
                        <label class="col-md-3 control-label">Note </label>
                        <div class="col-md-9">
                            <textarea class="form-control" rows="2" name="details" id="details" '.$update.'>'.$_GET['det'].'</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status <span class="required">*</span></label>
                        <div class="col-md-9">
                            <div class="radio-custom radio-inline">
                                <input type="radio" id="zoom_status" name="zoom_status" value="1"';if($_GET['stat'] == 1) { echo'checked';} echo'>
                                <label for="radioExample1">Active</label>
                            </div>
                            <div class="radio-custom radio-inline">
                                <input type="radio" id="zoom_status" name="zoom_status" value="2"'; if($_GET['stat'] == 2) { echo'checked';} echo'>
                                <label for="radioExample2">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">';
                            if ( $update == '') {
                                echo'<button type="submit" class="btn btn-primary" id="changes_class" name="changes_class">Update</button>';
                            }
                            echo'
                            <button class="btn btn-default modal-dismiss">Cancel</button>
                        </div>
                    </div>
                </footer>
            </form>
        </section>
    </div>
</div>';
?>