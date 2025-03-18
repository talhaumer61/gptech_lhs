<?php 
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 

	$sqllmsChallan = $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.id_month, f.note, f.to_month, f.issue_date, f.due_date, f.total_amount,
                                        c.campus_name, c.campus_phone, c.campus_head, d.*
                                        FROM ".FEES." f									
                                        INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
                                        INNER JOIN ".ROYALTY_CHALLAN_DET." d ON d.id_setup = f.id
                                        WHERE f.id = '".cleanvars($_GET['id'])."'
                                        AND f.is_deleted != '1' LIMIT 1");
    $rowsvalues = mysqli_fetch_array($sqllmsChallan); 
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="royaltyChallans.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="id_fee" id="id_fee" value="'.cleanvars($_GET['id']).'">
		<input type="hidden" name="challan_no" id="challan_no" value="'.cleanvars($rowsvalues['challan_no']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Royalty Challan </h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<div class="col-md-12">
					<div class="row clearfix">
						<div class="col-md-4">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Campus <span class="required">*</span></label>
									<input type="text" class="form-control" name="campus_name" required title="Must Be Required" value="'.$rowsvalues['campus_name'].'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Challan No <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" name="challan_no" id="challan_no" value="'.$rowsvalues['challan_no'].'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label class="control-label">From Month <span class="required">*</span></label>
							<input type="text" class="form-control" name="from_month" required title="Must Be Required" value="'.get_monthtypes(cleanvars($rowsvalues['id_month'])).'" readonly/>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group mt-sm">
				<div class="col-md-12">
					<div class="row clearfix">
						
						<div class="col-md-4">
							<label class="control-label">To Month <span class="required">*</span></label>
							<input type="text" class="form-control" name="to_month" required title="Must Be Required" value="'.get_monthtypes(cleanvars($rowsvalues['to_month'])).'" readonly/>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Issue Date <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['issue_date']))).'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Due Date <span class="required">*</span></label>
									<input type="text" id="due_date" name="due_date" class="form-control" data-plugin-datepicker required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['due_date']))).'"'; if($rowsvalues['status'] == 1) {echo' readonly';}echo'/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	
			<div class="form-group">
				<div class="col-md-12">
					<label class="control-label">Payable <span class="required">*</span></label>
					<input type="text" id="payable" name="payable" class="form-control total" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
				</div>
			</div>
			<div class="form-group mb-md">
				<div class="col-md-12">
					<label class="control-label">Note </label>
					<textarea class="form-control" rows="2" name="note" id="note">'.$rowsvalues['note'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label">Status <span class="required">*</span></label>
				<div class="col-md-10">
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="1"'; if($rowsvalues['status'] == 1) {echo' checked';}echo'>
						<label for="radioExample1">Paid</label>
					</div>'; 
					if($rowsvalues['status'] != 1) {echo' 
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="2"'; if($rowsvalues['status'] == 2) {echo' checked';}echo'>
						<label for="radioExample2">Pending</label>
					</div>

					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="3"'; if($rowsvalues['status'] == 3) {echo' checked';}echo'>
						<label for="radioExample2">Unpaid</label>
					</div>';
					}
					echo '
				</div>
			</div>
					
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="update_challan" name="update_challan">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel </button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
?>