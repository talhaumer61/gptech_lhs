<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'edit' => '1'))){   
//-----------------------------------------------------
$sqllmsfeesetup	= $dblms->querylms("SELECT f.id, f.status, f.dated, f.id_class, f.id_section, f.id_session,
								   c.class_name, cs.section_name, s.session_name
								   FROM ".FEESETUP." f						   
								   INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
								   INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
								   INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
								   WHERE f.id = '".cleanvars($_GET['id'])."' AND f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY f.dated ASC");
$value_setup = mysqli_fetch_array($sqllmsfeesetup);
    echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-plus-square"></i>	Edit Class Fee Structure</h2>
		</header>
		<form action="feesetup.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
			<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">    
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 control-label">Class <span class="required">*</span></label>
						<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_feestruclasssection(this.value)" disabled>
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																FROM ".CLASSES."
																WHERE class_status = '1' AND is_deleted != '1'
																ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo '<option value="'.$valuecls['class_id'].'"'; if($valuecls['class_id'] == $value_setup['id_class']){ echo' selected';}echo'>'.$valuecls['class_name'].'</option>';
							}
						echo '
						</select>
					</div>
				</div>
				<div id="getfeestruclasssection">
					<div class="form-group">
						<label class="col-md-2 control-label">Section <span class="required">*</span></label>
							<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_section" disabled>
								<option value="">Select</option>';
								$sqllmssection	= $dblms->querylms("SELECT section_id, section_name 
															FROM ".CLASS_SECTIONS."
															WHERE section_status = '1' AND is_deleted != '1'
															AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
															ORDER BY section_name ASC");
								while($valuesection = mysqli_fetch_array($sqllmssection)) {
								echo '<option value="'.$valuesection['section_id'].'"'; if($valuesection['section_id'] == $value_setup['id_section']){ echo' selected';}echo'>'.$valuesection['section_name'].'</option>';
								}
							echo '
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="1"'; if($value_setup['status'] == 1){ echo' checked';}echo'>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="2" '; if($value_setup['status'] == 2){ echo' checked';}echo'>
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
				
				<br>
				
				<table class="table table-hover table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th class="text-center">Category</th>
							<th class="text-center">Duration</th>
							<th class="text-center">Amount</th>
							<th class="text-center">Type</th>
						</tr>
					</thead>
					<tbody>';
							$sqllms	= $dblms->querylms("SELECT c.cat_id, c.cat_name
															FROM ".FEE_CATEGORY." c												 
															WHERE c.cat_status = '1' AND is_deleted != '1' 
															ORDER BY c.cat_name ASC");
							$srno = 0;
							while($rowsvalues = mysqli_fetch_array($sqllms)) {
								$srno++;
								$sqllmsfeedetail	= $dblms->querylms("SELECT fsd.id, fsd.duration, fsd.amount, fsd.type
																			FROM ".FEE_CATEGORY." c
																			INNER JOIN ".FEESETUPDETAIL." fsd ON fsd.id_cat = c.cat_id 													 
																			WHERE c.is_deleted != '1'
																			AND fsd.id_setup = '".cleanvars($_GET['id'])."' AND fsd.id_cat = '".$rowsvalues['cat_id']."'
																			LIMIT 1");
								$value_detail = mysqli_fetch_array($sqllmsfeedetail);
									echo '
									<input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
									<input type="hidden" name="id_edit['.$srno.']" id="id_edit['.$srno.']" value="'.$value_detail['id'].'">
									<tr>
										<td >'.$rowsvalues['cat_name'].'</td>
										<td>
											<div class="form-group mt-sm">
												<div class="col-md-12">
													<select class="form-control" name="duration['.$srno.']" id="duration['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" >
														<option vlaue="Yearly"'; if($value_detail['duration'] == 'Yearly'){echo' selected';} echo'>Yearly</option>
														<option vlaue="Half"'; if($value_detail['duration'] == 'Half'){echo' selected';} echo'>Half</option>
														<option vlaue="Quatar"'; if($value_detail['duration'] == 'Quatar'){echo' selected';} echo'>Quatar</option>
														<option vlaue="Monthly"'; if($value_detail['duration'] == 'Monthly'){echo' selected';} echo'>Monthly</option>
														<option vlaue="Once"'; if($value_detail['duration'] == 'Once'){echo' selected';} echo'>Once</option>
													</select>
												</div>
											</div>
										</td>
										<td>
											<div class="form-group mt-sm">
												<div class="col-md-12">
													<input type="number" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']" value="'.$value_detail['amount'].'"'; if($rowsvalues['cat_id'] == 1){echo'min="1000" required title="Amount must be greater or equal to 1000"';}else{echo'required title="Must Be Required"';} echo'/>
												</div>
											</div>
										</td>
										<td>
											<div class="form-group mt-sm">
												<div class="col-md-12">
													<select class="form-control" name="type['.$srno.']" id="type['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" >
														<option vlaue="Refundable"'; if($value_detail['type'] == 'Refundable'){echo' selected';} echo'>Refundable</option>
														<option vlaue="Non-Refundable"'; if($value_detail['type'] == 'Non-Refundable'){echo' selected';} echo'>Non-Refundable</option>
													</select>
												</div>
											</div>
										</td>
									</tr>';
							}
							echo'
					</tbody>
				</table>
				
			</div>	  
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="changes_feesetup" name="changes_feesetup">Save</button>
						<a href="feesetup.php" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}
else{
	header("Location: feesetup.php");
}
?>
