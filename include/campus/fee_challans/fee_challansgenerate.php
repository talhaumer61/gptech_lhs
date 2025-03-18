<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){   
	$today = date('m/d/Y');
	if(isset($_POST['id_class'])){$class = $_POST['id_class'];} else{$class = '';}
	if(isset($_POST['id_section'])){$section = $_POST['id_section'];}	else{$section = '';}
	

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Class Fee Challans</h4>
		</header>	
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-sm-4">
					<div class="form-group">
						<label class="control-label">For Month <span class="required">*</span></label>
						<input type="month" class="form-control" name="yearmonth" id="yearmonth" value=""  required title="Must Be Required"  onchange="get_duedate(this.value)"/>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Class <span class="required">*</span></label>
						<select data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required" class="form-control">
							<option value="">Select</option>';
								$sqllms	= $dblms->querylms("SELECT class_id, class_name
																FROM ".CLASSES." 
																WHERE class_status = '1' AND is_deleted != '1' 
																ORDER BY class_id ASC");
								while($rowsvalues = mysqli_fetch_array($sqllms)){
									if($rowsvalues['class_id'] == $class){
										echo'<option value="'.$rowsvalues['class_id'].'" selected>'.$rowsvalues['class_name'].'</option>';
									}else{
										echo'<option value="'.$rowsvalues['class_id'].'">'.$rowsvalues['class_name'].'</option>';
									}
								}
							echo'
						</select>
					</div>
				</div>
				<div class="col-sm-4" id="getduedate">
					<label class="control-label">Due Date <span class="required">*</span></label>
					<input type="text" class="form-control" name="due_date" id="due_date" value="" data-plugin-datepicker required title="Must Be Required"/>
				</div>  
			</div>
			<center>
				<button type="submit" name="challans_details" id="challans_details" class="btn btn-primary"><i class="fa fa-search"></i> Check Details</button>
			</center>
		</div>
		</form>
	</section>';

	// SEARCH RESULTS
	if(isset($_POST['challans_details'])){ 
		
	$due_date 	= date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	$yearmonth 	= date('Y-m', strtotime(cleanvars($_POST['yearmonth'])));
	$year 		= date('y', strtotime(cleanvars($_POST['yearmonth'])));
	$idmonth 	= date('n', strtotime(cleanvars($_POST['yearmonth'])));
		
		$sqllmsfeesetup	= $dblms->querylms("SELECT f.id, f.dated, f.id_class, f.id_section, f.id_session, f.id_campus,
										c.class_id, c.class_status, c.class_name,
										cs.section_id, cs.section_status, cs.section_name					     
										FROM ".FEESETUP." f
										INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
										INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section	
										WHERE f.is_deleted != '1'
										AND f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
										AND f.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
										AND f.status = '1' AND f.id_class = '".$class."'  
										ORDER BY f.id DESC LIMIT 1");
		if(mysqli_num_rows($sqllmsfeesetup) > 0){
			$value_feesetup = mysqli_fetch_array($sqllmsfeesetup);
			$fee_id = $value_feesetup['id'];
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<form action="fee_challans.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<input type="hidden" name="yearmonth" id="yearmonth" value="'.$yearmonth.'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-dollar"></i>
						Challan Details of <b>'.$value_feesetup['class_name'].'</b> (<b>'.$yearmonth.'</b>)</h2>
					</header>
					<div class="panel-body">
						<div class="alert alert-danger"> Please select only those heads which you want to add in challan.</div>
						<div class="row mt-sm">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Class <span class="required">*</span></label>
									<input type="hidden" name="id_class" id="id_class" value="'.$class.'">
									<input type="hidden" name="id_section" id="id_section" value="'.$section.'">
									<input type="text" class="form-control" value="'.$value_feesetup['class_name'].' ('.$value_feesetup['section_name'].')" required title="Must Be Required" readonly/>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">For Month <span class="required">*</span></label>
									<select data-plugin-selectTwo data-width="100%" name="id_month" id="id_month" required title="Must Be Required" class="form-control populate">						
										<option value="">Select</option>';
										foreach($monthtypes as $month){
											if($idmonth == $month['id']) {
												echo'<option value="'.$month['id'].'" selected>'.$month['name'].'</option>';
											} else {
												echo'<option value="'.$month['id'].'">'.$month['name'].'</option>';
											}
											
										}
										echo '
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Issue Date <span class="required">*</span></label>
									<input type="text" class="form-control" name="issue_date" id="issue_date" data-plugin-datepicker value="'.$today.'"required title="Must Be Required"/>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label">Due Date <span class="required">*</span></label>
									<input type="text" class="form-control" name="due_date" value="'.$due_date.'" id="due_date" data-plugin-datepicker required title="Must Be Required"/>
								</div>
							</div>
						</div>';
						//--------------- Fee Details -----------------------	
						$sqllms	= $dblms->querylms("SELECT 	d.id, d.id_setup, d.id_cat, d.amount,
													c.cat_id, c.cat_name
													FROM ".FEESETUPDETAIL." d
													INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = d.id_cat												 
													WHERE d.id_setup = '".$fee_id."' AND c.cat_status = '1'
													AND c.cat_id NOT IN(3,4)
													AND c.is_deleted != '1'
													ORDER BY c.cat_name ASC");
						$srno = 0;
						$amount = 0;
						$total_amount = 0;
						while($rowsvalues = mysqli_fetch_array($sqllms)) {
							$srno++;
							echo'
							<div class="mt-sm" style="margin-left: -15px; ">
								<div class="col-sm-3">
									<div class="form-group">';
										// if($rowsvalues['cat_id'] != 1){
											echo'
											<div class="checkbox-custom checkbox-inline" style="margin-top: -2px;">
												<input type="checkbox" name="is_selected['.$srno.']" id="is_selected" value="1" checked>
												<label for="checkboxExample1"></label>
											</div>';
										// }
										echo'
										<input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
										<label class="control-label">'.$rowsvalues['cat_name'].' <span class="required">*</span></label>
										<input type="number" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']" value="'.$rowsvalues['amount'].'" required title="Must Be Required" readonly/>
									</div>
								</div>
							</div>';
							$amount = $rowsvalues['amount'];
							$total_amount = $total_amount + $amount;
							//-------- GET TUITION FEE -------------
							if($rowsvalues['cat_id'] == 1){
								$tuitionFee = $rowsvalues['amount'];
							}
						}	
						$sqllmsstudent	= $dblms->querylms("SELECT std_id, transport_fee
															FROM ".STUDENTS."
															WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
															AND id_class = '".cleanvars($class)."'
															AND std_status = '1' AND is_deleted != '1'
															ORDER BY std_id ASC");
						$no = 0;
						while($value_std = mysqli_fetch_array($sqllmsstudent)) {
							$no++;
							// std ID
							echo'<input type="hidden" name="id_std['.$no.']" id="id_std" value="'.$value_std['std_id'].'">';

							//----------------- Remaining Amount ------------------
							$sqllms_rem = $dblms->querylms("SELECT remaining_amount FROM ".FEES." 
																WHERE id_std = '".$value_std['std_id']."'
																AND is_deleted != '1'
																ORDER BY id DESC LIMIT 1");
							if(mysqli_num_rows($sqllms_rem) > 0){
								$row_rem = mysqli_fetch_array($sqllms_rem);
								$rem_amount = $row_rem['remaining_amount'];
								$allowEdit = "readonly"; 
							}else{
								$allowEdit = "";
								$rem_amount = 0;
							}

							// concession scholarship remaining
							echo'
							<input type="hidden" name="transport_fee['.$no.']" value="'.$value_std['transport_fee'].'">
							<input type="hidden" name="prev_remaining_amount['.$no.']" value="'.$rem_amount.'">';
						}
						echo'
						<input type="hidden" name="total_amount" value="'.$total_amount.'">
						<div class="row mt-sm mb-lg">
							<div class="col-sm-12 mt-md">
								<div class="form-group">
									<label class="control-label">Note</label>
									<textarea type="text" class="form-control" name="note"></textarea>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer mt-sm">
						<div class="row">
							<div class="col-md-12">
								<center><button type="submit" name="bulk_challans_generate" id="bulk_challans_generate" class="btn btn-primary">Generate Challans</button></center>
							</div>
						</div>
					</footer>
				</form>
			</section>';
		}else{
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<h2 class="panel-body center mt-none">No Fee Structure is Added!</h2>
			</section>';
		}
	}
}else{
	header("Location: fee_challans.php");
}
?>