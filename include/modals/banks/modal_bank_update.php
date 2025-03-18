<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

$sqllms	= $dblms->querylms("SELECT bank_id, bank_status, id_type, bank_name, account_title, account_no, iban_no, branch_code
							FROM ".BANKS."
							WHERE bank_id = '".cleanvars($_GET['id'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-primary">
			<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="bank_id" id="bank_id" value="'.cleanvars($_GET['id']).'">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Bank</h2>
				</header>
				<div class="panel-body">
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Fund Type <span class="required">*</span></label>
						<div class="col-md-9">
							<select data-plugin-selectTwo data-width="100%" name="id_type" id="id_type" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>';
								foreach ($fundType as $fund):
									echo'<option value="'.$fund['id'].'" '.($rowsvalues['id_type']==$fund['id'] ? 'selected' : '').'>'.$fund['name'].'</option>';
								endforeach;
								echo'
							</select>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Bank Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="bank_name" id="bank_name" value="'.$rowsvalues['bank_name'].'" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Account Title <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="account_title" id="account_title" value="'.$rowsvalues['account_title'].'" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Account Number <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="account_no" id="account_no" value="'.$rowsvalues['account_no'].'" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">IBAN</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="iban_no" id="iban_no" value="'.$rowsvalues['iban_no'].'" />
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Branch Code <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="branch_code" id="branch_code" value="'.$rowsvalues['branch_code'].'" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="bank_status" name="bank_status" value="1" '.((isset($rowsvalues['bank_status']) && $rowsvalues['bank_status']==1) ? 'checked' : '').'>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="bank_status" name="bank_status" value="2" '.((isset($rowsvalues['bank_status']) && $rowsvalues['bank_status']==2) ? 'checked' : '').'>
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="update_bank" name="update_bank">Update</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>
</div>';
?>