<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

$sqllms	= $dblms->querylms("SELECT *
							FROM ".SOCIAL_LINKS."
							WHERE id = '".cleanvars($_GET['id'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-primary">
			<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Link</h2>
				</header>
				<div class="panel-body">
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Type <span class="required">*</span></label>
						<div class="col-md-9">
							<select data-plugin-selectTwo data-width="100%" name="id_social" id="id_social" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>';
								$socialtype = get_socialtype();
								foreach($socialtype as $key => $value){
									echo'<option value="'.$key.'" '.($rowsvalues['id_social']==$key ? 'selected' : '').'>'.$value.'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Link <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="link" id="link" value="'.$rowsvalues['link'].'" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<select data-plugin-selectTwo data-width="100%" name="status" id="status" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>';
								foreach($admstatus as $status){
									echo'<option value="'.$status['id'].'" '.($rowsvalues['status']==$status['id'] ? 'selected' : '').'>'.$status['name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="update_link" name="update_link">Update</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>
</div>';
?>