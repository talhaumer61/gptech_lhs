<?php 
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)){ 
	$sqllms	= $dblms->querylms("SELECT *
								   FROM ".ADMINISTRATION_DOWNLOAD." 
								   WHERE id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$row	= mysqli_fetch_array($sqllms);
	echo '
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="administration_downloads.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Video Lecture</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Type <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" onchange="chk_type(this.value)" data-minimum-results-for-search="Infinity" id="id_type" name="id_type">
									<option value="">Select</option>';
										$types	= get_downloadTypes();
										foreach($types as $key => $value) {
											echo '<option value="'.$key.'" '.($row['id_type'] == $key ? 'selected' : '').'>'.$value.'</option>';
										}
								echo '
								</select>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Title <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="title" name="title" value="'.$row['title'].'" required title="Must Be Required">
							</div>
						</div>
						<div class="form-group" id="file" '.($row['id_type'] == '3' ? 'style="display:none;"' : '').'>
							<label class="col-md-3 control-label">File</label>
							<div class="col-md-9">
								<input type="file" class="form-control" name="file"/>
							</div>
						</div>
						<div class="form-group mb-md" id="youtube_code" '.($row['id_type'] != '3' ? 'style="display:none;"' : '').'>
							<label class="col-md-3 control-label">Youtube Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="youtube_code" value="'.$row['youtube_code'].'" required title="Must Be Required">
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Description</label>
							<div class="col-md-9">
								<textarea class="form-control" rows="2" name="description" id="description">'.$row['description'].'</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="1" '.($row['status'] == '1' ? 'checked' : '').'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="2" '.($row['status'] == '2' ? 'checked' : '').'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="change_video" name="change_video">Update</button>
								<button class="btn btn-default modal-dismiss">Cancel</button>
							</div>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>';
}
?>