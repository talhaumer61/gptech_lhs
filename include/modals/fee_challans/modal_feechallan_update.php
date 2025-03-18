<?php 
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
	
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
	
	$sqllms	= $dblms->querylms("SELECT  f.id, f.status, f.id_type, f.id_month, f.challan_no, f.id_session, f.id_class, f.id_section, f.id_std,
									f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.scholarship, f.concession, f.fine, f.prev_remaining_amount, f.note, 
									c.class_id, c.class_name,
									cs.section_id, cs.section_name,
									s.session_id, s.session_name,
									st.std_id, st.std_name, st.std_phone, st.std_regno
									FROM ".FEES." f				   
									INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
									INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
									INNER JOIN ".STUDENTS." st ON st.std_id 	 = f.id_std
									LEFT JOIN ".CLASS_SECTIONS." cs ON cs.section_id = st.id_section		
									WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
									AND f.id = '".cleanvars($_GET['id'])."'
									ORDER BY f.challan_no DESC");
	$rowsvalues = mysqli_fetch_array($sqllms);
	
	$concession = $rowsvalues['scholarship'] + $rowsvalues['concession'];

	if($rowsvalues['id_type'] == 1){
		$readonly = "";
	}else{
		$readonly = "readonly";
	}

	// Count Challans for Edit Balance
	//if there is one challan then previous balnc editable
	$sqllms_blnc	= $dblms->querylms("SELECT  id
												FROM ".FEES."		
												WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
												AND id_std = '".cleanvars($rowsvalues['id_std'])."'
												ORDER BY challan_no DESC");
	if(mysqli_num_rows($sqllms_blnc) > 1)
	{
		$blnc_edit = 1;
	}else{
		$blnc_edit = 0;
	}
	// $value_blnc = mysqli_fetch_array($sqllms_blnc);
	// TUITION FEE
	/*	
		$sql_fees	= $dblms->querylms("SELECT c.cat_id, c.cat_name, p.id, p.id_cat, p.amount
										FROM ".FEE_PARTICULARS." p
										INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = p.id_cat
										WHERE p.id_fee = '".$rowsvalues['id']."' ");
										*/
	// Scholarship
	// $sql_scholarship	= $dblms->querylms("SELECT SUM(percent) as scholarship
	// 								   FROM ".SCHOLARSHIP." 
	// 								   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
	// 								   AND id_session = '".$rowsvalues['id_session']."'
	// 								   AND   id_type = '1' AND status = '1' AND is_deleted != '1'
	// 								   AND id_std = '".$rowsvalues['std_id']."' ");
	
	// $values_scholarship = mysqli_fetch_array($sql_scholarship);
	// // Fee Concession
	// $sql_concess	= $dblms->querylms("SELECT SUM(percent) as concession
	// 								   FROM ".SCHOLARSHIP." 
	// 								   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
	// 								   AND id_session = '".$rowsvalues['id_session']."'
	// 								   AND   id_type = '2' AND status = '1' AND is_deleted != '1'
	// 								   AND id_std = '".$rowsvalues['std_id']."' ");
	
	// $values_concess = mysqli_fetch_array($sql_concess);
	// // Fine add in next month
	// $chln_mnth  = date("n", strtotime($rowsvalues['due_date'])) + 1;

	// $sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
	// 								   FROM ".SCHOLARSHIP." 
	// 								    WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
	// 									AND id_session = '".$rowsvalues['id_session']."'
	// 									AND MONTH(date) = '".$chln_mnth."'
	// 									AND id_type = '3' AND status = '1' AND is_deleted != '1'
	// 									AND id_std = '".$rowsvalues['std_id']."' ");
	
	// $values_fine = mysqli_fetch_array($sql_fine);
	// if($values_fine['fine'] > 0){
	// 	$fine = $values_fine['fine'];
	// }else {
	// 	$fine = 0;	
	// }
	// TUITION FEE
	$sql_tuitionfee	= $dblms->querylms("SELECT amount
									   FROM ".FEESETUPDETAIL." d
									   INNER JOIN ".FEESETUP." f ON f.id = d.id_setup
									   WHERE f.id_class = '".$rowsvalues['class_id']."' AND f.id_section = '".$rowsvalues['section_id']."'
									   AND   d.id_cat = '1'");
									   
	$values_tuitionfee = mysqli_fetch_array($sql_tuitionfee);
	$tuition_fee = $values_tuitionfee['amount'];

	// TUITION FEE 
	// $sqllms_his	= $dblms->querylms("SELECT SUM(remaining_amount) as rem
	// 							   FROM ".FEES."
	// 							   WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
	// 							   AND id_std = '".$rowsvalues['id_std']."' ");
	
	// $values_his = mysqli_fetch_array($sqllms_his);

	// payabel amount after Scholarship & Fine
	// TOTAL PERCENTAGE DEDUCTION IN FEE
	$total_fee = $rowsvalues['total_amount'];
	// $dis_per = $values_scholarship['scholarship'] + $values_concess['concession'];
	// DISCOUNT IN TUTION FEE
	// $dis_amount = ($tuition_fee * $dis_per) / 100;
	// DISCOUNT IN TUTION FEE
	// $after_dis = $total_fee - $dis_amount;
	// PAYABLE AFTER DISCOUNT AND FINE 
	// $payable = ($total_fee  + $fine + $rowsvalues['remaining_amount']) - $dis_amount;
	
	echo '
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="fee_challans.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="id_fee" id="id_fee" value="'.cleanvars($_GET['id']).'">
					<input type="hidden" name="type" id="id_fee" value="'.cleanvars($rowsvalues['id_type']).'">
					<input type="hidden" name="std_phone" id="std_phone" value="'.cleanvars($rowsvalues['std_phone']).'">
					<input type="hidden" name="challan_no" id="challan_no" value="'.cleanvars($rowsvalues['challan_no']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Fee Challan </h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-sm">
							<div class="col-md-12">
								<div class="row clearfix">
									<div class="col-md-4">
										<div class="form-group">
											<div class="col-md-12">
												<label class=control-label">Student <span class="required">*</span></label>
												<input type="text" class="form-control" name="std_name" required title="Must Be Required" value="'.$rowsvalues['std_name'].'" readonly/>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="col-md-12">
												<label class=control-label">Student <span class="required">*</span></label>
												<input type="text" class="form-control" required title="Must Be Required" value="'.$rowsvalues['class_name'].' ( '.$rowsvalues['section_name'].' )" readonly/>
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
								</div>
							</div>
						</div>
						<div class="form-group mt-sm">
							<div class="col-md-12">
								<div class="row clearfix">
									<div class="col-md-4">
										<label class="control-label">For Month <span class="required">*</span></label>
										<input type="text" class="form-control" name="month" required title="Must Be Required" value="'.get_monthtypes(cleanvars($rowsvalues['id_month'])).'" readonly/>
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
						<div class="form-group mt-sm">
							<div class="col-md-12">
								<label class="control-label">All Fees</label>
								<div class="row clearfix">';
									$total_fee = 0;
									$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
																		FROM ".FEE_CATEGORY."
																		WHERE cat_status = '1' 
																		ORDER BY cat_id ASC");
									$countcats 	= mysqli_num_rows($sqllmscats);
										
									if($countcats >0) {
										$src = 0;
										while($rowdoc 	= mysqli_fetch_array($sqllmscats)) {
											
											$sqllmsfeeprt  = $dblms->querylms("SELECT id, id_cat, amount 
																					FROM ".FEE_PARTICULARS." 
																					WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$rowsvalues['id']."' 
																					LIMIT 1");
													if(mysqli_num_rows($sqllmsfeeprt)>0) { 
														$valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
														echo'
														<div class="col-md-4">
															<div class="form-group mt-sm">
																<div class="col-md-12">
																	<label class=control-label">'.$rowdoc['cat_name'].' <span class="required">*</span></label>
																	<input type="hidden" name="id[]" value="'.$valuefeeprt['id'].'">
																	<input type="number" id="amount" name="amount[]" class="form-control cats" required title="Must Be Required" value="'.$valuefeeprt['amount'].'"'; /*if($rowsvalues['status'] == 1 || $rowdoc['cat_id'] == '1') {echo'readonly';}*/ echo' '.$readonly.'/>
																</div>
															</div>
														</div>
														';
													}
													//  else { 
													//     echo'
													//     <div class="col-md-4">
													// 		<div class="form-group mt-sm">
													// 			<div class="col-md-12">
													// 				<label class=control-label">'.$rowdoc['cat_name'].' <span class="required">*</span></label>
													// 				<input type="hidden" name="id[]" value="'.$valuefeeprt['id'].'">
													// 				<input type="text" id="amount" name="amount[]" class="form-control cats" required title="Must Be Required" value=""'; if($rowsvalues['status'] == 1 || $rowdoc['cat_id'] == '1') {echo'readonly';}echo'/>
													// 			</div>
													// 		</div>
													// 	</div>
													//     ';
													// }
													$total_fee = $total_fee + $valuefeeprt['amount'];
										}
									}
									/*
									
									$total_fee = 0;
									while($value_fee = mysqli_fetch_array($sql_fees)){
										
									echo'
									<div class="col-md-6">
										<div class="form-group mt-sm">
											<div class="col-md-12">
												<label class=control-label">'.$value_fee['cat_name'].' <span class="required">*</span></label>
												<input type="hidden" name="id[]" value="'.$value_fee['id'].'">
												<input type="text" id="amount" name="amount[]" class="form-control" required title="Must Be Required" value="'.$value_fee['amount'].'"'; if($rowsvalues['status'] == 1 || $value_fee['cat_id'] == '1') {echo'readonly';}echo'/>
											</div>
										</div>
									</div>
									';
									$total_fee = $total_fee + $value_fee['amount'];
									}
									
									*/
									echo'
									<input type="hidden" name="total_fee" value="'.$total_fee.'">
								</div>
							</div>
						</div>
						<div class="form-group mt-sm">
							<div class="col-md-12">
								<label class="control-label">Others</label>
								<div class="row clearfix">
									<div class="col-md-4">
										<div class="form-group mt-sm">
											<div class="col-md-12">
												<label class=control-label">Discount <span class="required">*</span></label>
												<input type="text" id="" name="" class="form-control disc" required title="Must Be Required" value="'.$concession.'" readonly/>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group mt-sm">
											<div class="col-md-12">
												<label class=control-label">Fine <span class="required">*</span></label>
												<input type="text" id="" name="" class="form-control fine" required title="Must Be Required" value="'.$rowsvalues['fine'].'" readonly/>
											</div>
										</div>
									</div>';
									if($rowsvalues['id_type'] != 1){
										echo'<div class="col-md-4">
											<div class="form-group mt-sm">
												<div class="col-md-12">
													<label class=control-label">Balance <span class="required">*</span></label>
													<input type="text" id="prev_remaining_amount" name="prev_remaining_amount" class="form-control cats" required title="Must Be Required" value="'.$rowsvalues['prev_remaining_amount'].'"'; if($blnc_edit != 1){echo'readonly';} echo'/>
												</div>
											</div>
										</div>';
									}
									echo'
								</div>';
								//$payable = ($total_fee + $fine) - $dis_amount;
								// <div class="row clearfix">
								// 	<div class="col-md-6">
								// 		<div class="form-group mt-sm">
								// 			<div class="col-md-12">
								// 				<label class=control-label">Payable <span class="required">*</span></label>
								// 				<input type="text" id="payable" name="payable" class="form-control total" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
								// 			</div>
								// 		</div>
								// 	</div>
								// 	<div class="col-md-6">
								// 		<div class="form-group mt-sm">
								// 			<div class="col-md-12">
								// 				<label class=control-label">Previous Balance <span class="required">*</span></label>
								// 				<input type="text" id="prev_remaining_amount" name="prev_remaining_amount" class="form-control" required title="Must Be Required" value="'.$rowsvalues['prev_remaining_amount'].'"/>
								// 			</div>
								// 		</div>
								// 	</div>
								// </div>
								echo'
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label">Payable <span class="required">*</span></label>
								<input type="text" id="payable" name="payable" class="form-control total" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
							</div>
						</div>
						';

						// 	<div class="col-md-6">
						// 		<label class="control-label">Paid Amount </label>
						// 		<input type="text" id="paid_amount" name="paid_amount" class="form-control paid"/>
						// 	</div>
						// </div>
						
						// <div class="form-group">
						// 	<div class="col-md-12">
						// 		<label class="control-label">Rem. Amount <span class="required">*</span></label>
						// 		<input type="text" id="prev_remaining_amount" name="prev_remaining_amount" class="form-control rem" required title="Must Be Required" readonly/>
						// 	</div>
						// </div>
						echo' 
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
								<button type="submit" class="btn btn-primary" id="changes_challan" name="changes_challan">Update</button>
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

<script type="text/javascript">
$(document).on("change", ".cats", function() {
    var sum = 0;
    $(".cats").each(function(){
        sum += +$(this).val();
    });
	
	var concess = <?php echo $concession; ?>;
    var fine = <?php echo $rowsvalues['fine'];?>;
	var payable = (fine + sum);
    $(".total").val(payable);
});


$(document).on("change", ".paid", function() {
	var payable =  document.getElementById("payable").value;
	var paid = document.getElementById("paid_amount").value;
	var rem  = payable - paid;
	$(".rem").val(rem);
});
</script>