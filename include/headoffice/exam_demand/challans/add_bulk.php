<?php
$due_date = '';
$id_session = '';
$id_examtype = '';
if(isset($_POST['due_date'])){$due_date = $_POST['due_date'];}	
if(isset($_POST['id_session'])){$id_session = $_POST['id_session'];}	
if(isset($_POST['id_examtype'])){$id_examtype = $_POST['id_examtype'];}	
//-----------------------------------------------
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Genrate Bulk Challan</h2>
	</header>
	<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-md-4">
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
				<div class="col-md-4">
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
				<div class="col-md-4">
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
		$sqllmsDemandCheck	= $dblms->querylms("SELECT ed.demand_id, ed.demand_status, ed.is_publish, ed.total_amount, ed.id_session, ed.id_examtype, ed.id_campus, et.type_name, s.session_name, ed.total_std, ed.total_amount, c.campus_name
												FROM ".EXAM_DEMAND." ed
												INNER JOIN ".EXAM_TYPES." et ON et.type_id = ed.id_examtype
												INNER JOIN ".SESSIONS." s ON s.session_id = ed.id_session
												INNER JOIN ".CAMPUS." c ON c.campus_id = ed.id_campus
												WHERE ed.is_deleted	= '0' 
												AND	  ed.demand_status	= '1'
												AND	  ed.is_publish	= '1'
												AND   ed.id_session	= '".$id_session."'
												AND	  ed.id_examtype	= '".$id_examtype."'
												");
		if(mysqli_num_rows($sqllmsDemandCheck) > 0){							
			echo '
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i> Exam Demand Detail</h2>
				</header>
				<div class="panel-body">
					<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<input type="hidden" name="id_session" id="id_session" value="'.$id_session.'">
						<input type="hidden" name="id_examtype" id="id_examtype" value="'.$id_examtype.'">
						<input type="hidden" name="due_date" id="due_date" value="'.$due_date.'">
						<fieldset>
							<div class="panel-body">
								<div class="table-responsive mt-sm mb-md">
									<table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
										<thead>
											<tr>
												<th width="20" class="center">Sr#</th>
												<th width="80" class="center">Session</th>
												<th>Campus</th>
												<th>Exam Type</th>
												<th width="110" class="center">Total Students</th>
												<th width="110" class="center">Total Amount</th>
												<th width="70" class="center">Status</th>
												<th width="70" class="center">Publish</th>
												<th width="50" class="center">Action</th>
											</tr>
										</thead>
										<tbody>';	
											$srno = 0;
											while($rowsvalues = mysqli_fetch_array($sqllmsDemandCheck)) {
												$srno++;
												echo '
												<tr>
													<td style="width:40px; class="center">
														<input type="hidden" name="id_demand[]" id="id_demand[]" value="'.$rowsvalues['demand_id'].'">
														'.$srno.'
													</td>
													<td class="center">'.$rowsvalues['session_name'].'</td>
													<td>
														<input type="hidden" name="id_campus[]" id="id_campus[]" value="'.$rowsvalues['id_campus'].'">
														'.$rowsvalues['campus_name'].'
													</td>
													<td>'.$rowsvalues['type_name'].'</td>
													<td class="center">'.$rowsvalues['total_std'].'</td>
													<td class="center">
														<input type="hidden" name="total_amount[]" id="total_amount[]" value="'.$rowsvalues['total_amount'].'">
														'.$rowsvalues['total_amount'].'
													</td>
													<td class="center">'.get_status($rowsvalues['demand_status']).'</td>
													<td class="center">'.get_statusyesno($rowsvalues['is_publish']).'</td>
													<td class="center">
														<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/exam_demand/detail.php?id='.$rowsvalues['demand_id'].'\');"><i class="glyphicon glyphicon-eye-open"></i></a>
													</td>
												</tr>';
											}
											echo'
										</tbody>
									</table>
								</div>
							</div>
						</fieldset>
						<div class="panel-footer row text-right" style="margin-bottom: -15px;">
							<button type="submit" name="genrate_bulk_challan" id="genrate_bulk_challan" class="btn btn-primary">Genrate Challan</button>
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
?>