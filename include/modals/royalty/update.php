<?php 

	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
	
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1)){ 
	
	$sqllms	= $dblms->querylms("SELECT  *
								FROM ".ROYALTY_SETTING."
								WHERE id = '".cleanvars($_GET['id'])."' 
								LIMIT 1
							  ");
	$row = mysqli_fetch_array($sqllms);
	
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="campuses.php?id='.cleanvars($row['id_campus']).'"  class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Royalty</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-4 control-label">Royalty Type <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($row['id_campus']).'">
					<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
					<select name="royalty_type" data-plugin-selectTwo data-width="100%" id="royalty_type" onchange="chk_royalty_type(this.value)" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						$types	= get_royaltyTypes();
						foreach($types as $key => $value) {
							echo '<option value="'.$key.'" '.($row['royalty_type'] == $key ? 'selected' : '') .'>'.$value.'</option>';
						}
						echo '
					</select>
				</div>
			</div>

			<div class="form-group mt-sm" id="per_month" '.($row['royalty_type'] == 2 ? 'style="display:none;"' : '').'>
				<label class="col-md-4 control-label">Per Month <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" required name="per_month" value="'.($row['royalty_type'] == 1 ? $row['royalty_amount'] : '').'" >
				</div>
			</div>

			<div class="form-group mt-sm" id="per_student" '.($row['royalty_type'] == 1 ? 'style="display:none;"' : '').'>
				<label class="col-md-4 control-label">Per Student <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="number" class="form-control" required name="per_student" value="'.($row['royalty_type'] == 2 ? $row['royalty_amount'] : '').'" >
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-4 control-label">Date  <span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
						<span class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</span>
						<input type="text" class="form-control" required title="Must Be Required" name="start_date" value="'.date('d-m-Y', strtotime($row['start_date'])).'">
						<span class="input-group-addon">to</span>
						<input type="text" class="form-control" required title="Must Be Required" name="end_date" value="'.date('d-m-Y', strtotime($row['end_date'])).'" >
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-4 control-label">Status <span class="required">*</span></label>
				<div class="col-md-8">
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
					<button type="submit" class="btn btn-primary" id="update_royalty" name="update_royalty">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
//---------------------------------------------------------