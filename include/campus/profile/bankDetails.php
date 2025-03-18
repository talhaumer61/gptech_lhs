<?php
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT bank_name, bank_abbreviation, bank_account_no
								FROM ".CAMPUS." a  
								WHERE campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
//-----------------------------------------------------
echo '
<div id="bankDetail" class="tab-pane">
	<form action="#" class="form-horizontal validate" method="post" accept-charset="utf-8">
		<fieldset class="mt-lg">
			<div class="form-group mb-md">
				<label class="col-sm-3 control-label">Bank Name <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="bank_name" name="bank_name" value="'.$rowsvalues['bank_name'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-sm-3 control-label">Bank Abbreviation <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="bank_abbreviation" name="bank_abbreviation" value="'.$rowsvalues['bank_abbreviation'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-sm-3 control-label">Account No. <span class="required">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="bank_account_no" name="bank_account_no" value="'.$rowsvalues['bank_account_no'].'" required title="Must Be Required"/>
				</div>
			</div>
		</fieldset>
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-offset-3 col-sm-5">
					<button type="submit" name="chnagesBankDetails" class="btn btn-primary">Update Bank Detail</button>
				</div>
			</div>
		</div>
	</form>
</div>';
?>