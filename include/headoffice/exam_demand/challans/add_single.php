<?php
$campus = '';
$due_date = '';
$id_session = '';
$id_examtype = '';
if(isset($_POST['campus'])){$campus = $_POST['campus'];}	
if(isset($_POST['due_date'])){$due_date = $_POST['due_date'];}	
if(isset($_POST['id_session'])){$id_session = $_POST['id_session'];}	
if(isset($_POST['id_examtype'])){$id_examtype = $_POST['id_examtype'];}	
//-----------------------------------------------
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Genrate Single Challan</h2>
	</header>
	<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-md-3">
					<label class="control-label">Session <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" id="id_session" name="id_session" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
							$sqlSession	= $dblms->querylms("SELECT session_id, session_name 
																FROM ".SESSIONS."
																WHERE is_deleted    = '0'
																AND session_status  = '1'
															");
							while($valSession = mysqli_fetch_array($sqlSession)) {
								echo '<option value="'.$valSession['session_id'].'" '.($valSession['session_id']==$id_session ? 'selected' : '').'>'.$valSession['session_name'].'</option>';
							}
						echo'
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label">Type <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
							$sqlExamType	= $dblms->querylms("SELECT type_id, type_name 
																FROM ".EXAM_TYPES."
																WHERE is_deleted    = '0'
																AND type_status     = '1'
															");
							while($valExamType = mysqli_fetch_array($sqlExamType)) {
								echo '<option value="'.$valExamType['type_id'].'" '.($valExamType['type_id']==$id_examtype ? 'selected' : '').'>'.$valExamType['type_name'].'</option>';
							}
						echo'
					</select>
				</div>
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
								echo'<option value="'.$value_campus['campus_id'].'" '.($value_campus['campus_id'] == $campus ? 'selected' : '').'>'.$value_campus['campus_name'].'</option>';
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

//-----------------------------------------------
if(isset($_POST['view_detail'])){

	$months = ($to_month - $from_month) + 1;

	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_FEE_CHALLANS." 
										WHERE id_examtype = '".$id_examtype."' 
										AND	  id_session  = '".$id_session."'
										AND   id_campus   = '".$campus."'
										AND   due_date    = '".$due_date."'  
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck) > 0){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_demand_challans.php?view=add_single", true, 301);
		exit();
	}else{
		$sqllmsDemandCheck	= $dblms->querylms("SELECT *, et.type_name
												FROM ".EXAM_DEMAND." ed
												INNER JOIN ".EXAM_TYPES." et ON et.type_id = ed.id_examtype
												WHERE ed.is_deleted	= '0' 
												AND	  ed.id_campus		= '".$campus."'
												AND	  ed.demand_status	= '1'
												AND	  ed.is_publish	= '1'
												AND   ed.id_session	= '".$id_session."'
												AND	  ed.id_examtype	= '".$id_examtype."'
												");
		if(mysqli_num_rows($sqllmsDemandCheck) > 0){	
			$valDemand = mysqli_fetch_array($sqllmsDemandCheck);	
			$sqlClass = $dblms->querylms("SELECT c.class_id, c.class_name, dd.*
											FROM ".CLASSES." as c
											INNER JOIN ".EXAM_DEMAND_DET." dd ON (
																					dd.id_class = c.class_id
																					AND dd.id_demand = '".cleanvars($valDemand['demand_id'])."'  
																				)
											WHERE c.is_deleted = '0'
											AND c.class_status = '1'
											ORDER BY c.class_id
										");								
			echo '
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i> Exam Demand Detail</h2>
				</header>
				<div class="panel-body">
					<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<input type="hidden" name="id_campus" id="id_campus" value="'.$campus.'">
						<input type="hidden" name="id_session" id="id_session" value="'.$id_session.'">
						<input type="hidden" name="id_examtype" id="id_examtype" value="'.$id_examtype.'">
						<input type="hidden" name="due_date" id="due_date" value="'.$due_date.'">
						<input type="hidden" name="total_amount" id="total_amount" value="'.$valDemand['total_amount'].'">
						<input type="hidden" name="id_demand" id="no_of_std" value="'.$valDemand['demand_id'].'">
						<fieldset>
							<div class="panel-body">
								<div class="table-responsive mt-sm mb-md">
									<table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
										<thead>
											<tr>
												<th class="center" width="40">Sr.</th>
												<th>Class</th>
												<th width="150" class="center">Amount Per Student</th>
												<th width="110" class="center">No of Students</th>
												<th width="110" class="center">Total Amount</th>
											</tr>
										</thead>
										<tbody>';	
											$srno = 0;
											$grandTotal = 0;
											$stdTotal	= 0;
											while($valClass = mysqli_fetch_array($sqlClass)){
												$grandTotal = $grandTotal + $valClass['total_amount'];
												$stdTotal	= $stdTotal + $valClass['no_of_std'];
												$srno++;
												echo'
												<tr>
													<td class="center">'.$srno.'</td>
													<td>'.$valClass['class_name'].'</td>
													<td class="center">'.$valClass['amount_per_std'].'</td>
													<td class="center">'.$valClass['no_of_std'].'</td>
													<td class="center">'.$valClass['total_amount'].'</td>
												</tr>';
											}
											echo'
											<tr>
												<th class="center" colspan="3">Grand Total</th>
												<th class="center">'.$stdTotal.'</th>
												<th class="center">'.$grandTotal.'</th>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</fieldset>
						<div class="panel-footer row text-right" style="margin-bottom: -15px;">
							<button type="submit" name="genrate_challan" id="genrate_challan" class="btn btn-primary">Genrate Challan</button>
						</div>
					</form>
				</div>
			</section>';
		} else{
			echo'
			<div class="panel-body">
				<h2 class="text text-danger text-center">Exam Demand Not Added.</h2>
			</div>';
		}
	}
}
?>