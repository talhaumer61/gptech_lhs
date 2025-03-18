<?php
$campus = '';
$from_month = '';
$to_month = '';
$due_date = '';
if(isset($_POST['campus'])){$campus = $_POST['campus'];}	
if(isset($_POST['from_month'])){$from_month = $_POST['from_month'];}	
if(isset($_POST['to_month'])){$to_month = $_POST['to_month'];}	
if(isset($_POST['due_date'])){$due_date = $_POST['due_date'];}	
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
	</header>
	<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Campus <span class="required">*</span></label>
						<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
																	FROM ".CAMPUS." c  
																	WHERE c.campus_id != '' AND campus_status = '1'
																	ORDER BY c.campus_name ASC");
							while($value_campus = mysqli_fetch_array($sqllmscampus)){
								if($value_campus['campus_id'] == $campus){
									echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
								}else{
									echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
				</div>
				<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">From Month <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" name="from_month" id="from_month" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						foreach($monthtypes as $month){
							if($month['id'] == $from_month){
								echo'<option value="'.$month['id'].'" selected>'.$month['name'].'</option>';
							} else {
								echo'<option value="'.$month['id'].'">'.$month['name'].'</option>';
							}
						}
						echo'
						</select>
				</div>
				</div>
				<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">To Month <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" name="to_month" id="to_month" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						foreach($monthtypes as $month){
							if($month['id'] == $to_month){
								echo'<option value="'.$month['id'].'" selected>'.$month['name'].'</option>';
							} else {
								echo'<option value="'.$month['id'].'">'.$month['name'].'</option>';
							}
						}
						echo'
						</select>
				</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label">Due Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="due_date" id="due_date" value="'.$_POST['due_date'].'" data-plugin-datepicker required title="Must Be Required"/>
					</div>
				</div>
			</div>
			<center>
				<button type="submit" name="view_detail" id="view_detail" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
			</center>
		</div>
	</form>
</section>';

if(isset($_POST['view_detail'])){

	$months = ($to_month - $from_month) + 1;

	$sqllmscheck  = $dblms->querylms("SELECT id
											FROM ".FEES." 
											WHERE ( (id_month BETWEEN $from_month AND $to_month) OR (to_month BETWEEN $from_month AND $to_month) )
											AND id_type = '3' AND id_campus = '".cleanvars($campus)."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: royaltyChallans.php?view=add", true, 301);
		exit();
	}else{
		$currentDate		=	date('Y-m-d');
		// $currentDate		=	'2024-01-02';
		$sqllmsRoyaltyCheck	= 	$dblms->querylms("SELECT id, royalty_type, royalty_amount
													FROM ".ROYALTY_SETTING." 
													WHERE is_deleted	= '0' 
													AND	id_campus		= '".$campus."'
													AND	start_date 		<= '".$currentDate."'
													AND	end_date   		>= '".$currentDate."'
													AND	status			= '1'
												");
		if(mysqli_num_rows($sqllmsRoyaltyCheck) > 0){	
			$valRoyaltyCheck = mysqli_fetch_array($sqllmsRoyaltyCheck);									
			echo '
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i>  Royalty Detail For '.$months.' Months</h2>
				</header>
				<div class="panel-body">
					<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<input type="hidden" name="id_campus" id="id_campus" value="'.$campus.'">
						<input type="hidden" name="from_month" id="from_month" value="'.$from_month.'">
						<input type="hidden" name="to_month" id="to_month" value="'.$to_month.'">
						<input type="hidden" name="due_date" id="due_date" value="'.$due_date.'">
						<fieldset>
							<div class="panel-body">';	
								$sqllmsstudents	= $dblms->querylms("SELECT COUNT(s.std_id) as total, c.campus_name
																	FROM ".STUDENTS." s
																	INNER JOIN ".CAMPUS." c ON c.campus_id = s.id_campus
																	WHERE s.std_id 	!= '' 
																	AND s.std_status	= '1' 
																	AND s.is_deleted 	!= '1'
																	AND s.id_campus 	= '".$campus."'
																  ");
								$value_std = mysqli_fetch_array($sqllmsstudents);

								if(mysqli_num_rows($sqllmsstudents) > 0){

									if($valRoyaltyCheck['royalty_type'] == 1){
										$amount			=	$valRoyaltyCheck['royalty_amount'];
										$totalamount	=	$amount  *  $months;
									}else{
										$amount			=	$valRoyaltyCheck['royalty_amount'] *  $value_std['total'];
										$totalamount	=	$amount  *  $months;
									}
									
									echo 
										'<div class="table-responsive">
											<table class="table table-bordered table-condensed table-striped mb-none">
												<thead>
													<tr>
														<th>Campus</th>
														<th class="center">Royalty Type</th>
														<th class="center">Royalty Amount</th>
														<th class="center">Students</th>
														<th class="center">Months</th>
														<th class="center">Total Amount</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="hidden" name="no_of_month" id="months" value="'.$months.'">
															<input type="hidden" name="no_of_std" id="months" value="'.$value_std['total'].'">
															<input type="hidden" name="total_amount" id="months" value="'.$totalamount.'">
															<input type="hidden" name="royalty_type" id="months" value="'.$valRoyaltyCheck['royalty_type'].'">
															<input type="hidden" name="royalty_amount" id="months" value="'.$valRoyaltyCheck['royalty_amount'].'">
															'.$value_std['campus_name'].'
														</td>
														<td class="center">'.get_royaltyTypes($valRoyaltyCheck['royalty_type']).'</td>
														<td class="center">'.$valRoyaltyCheck['royalty_amount'].'</td>
														<td class="center">'.$value_std['total'].'</td>
														<td class="center">'.$months.'</td>
														<td class="center">'.$totalamount.'</td>
													</tr>
												</tbody>
											</table>
										</div>';
									
								}else{
									echo'<h4 class="text text-danger center">No Student Added!</h4>';
								}
							echo'
							</div>
						</fieldset>
						<div class="panel-footer row center" style="margin-bottom: -15px;">
							<button type="submit" name="genrate_challan" id="genrate_challan" class="btn btn-primary">Genarte Royalty Challan</button>
						</div>
					</form>
				</div>
			</section>';
		} else{
			echo'
			<div class="panel-body">
				<h2 class="text text-danger text-center">Royalty Not Added.</h2>
			</div>';
		}
	}
}
?>

<script type="text/javascript">
	//Grand Total Amount On Input of each Total
	$(document).on("input", ".totalAmount", function() {
		var grandTotal = 0;
		$(".totalAmount").each(function(){
			grandTotal += +$(this).val();
		});
		$("#grandTotal").val(grandTotal);
	});

	// On Load Calculate Grand Total Against Months
	$( document ).ready(function() {
		var grandTotal = 0;
		$(".totalAmount").each(function(){
			grandTotal += +$(this).val();
		});
		$("#grandTotal").val(grandTotal);
	});
</script>