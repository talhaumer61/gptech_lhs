<?php
$sql1 = "";
$id_campus = "";
// CAMPUS
if($_GET['id_campus']){
	$sql1 = "AND d.id_campus = '".cleanvars($_GET['id_campus'])."'";
	$id_campus = $_GET['id_campus'];
}
$sql2 = "";
$id_session = "";
// SESSION
if($_GET['id_session']){
    $sql2 = "AND d.id_session = '".$_GET['id_session']."'";
    $id_session = $_GET['id_session'];
}
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
	</header>
	<form action="#" id="form" enctype="multipart/form-data" method="GET" accept-charset="utf-8">
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-md-6">
					<label class="control-label">Session</label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_session" name="id_session">
						<option value="">Select</option>';
						$sqlSession	= $dblms->querylms("SELECT session_id, session_name 
														FROM ".SESSIONS."
														WHERE session_status = '1' ORDER BY session_id ASC");
						while($valSession = mysqli_fetch_array($sqlSession)) {
							echo '<option value="'.$valSession['session_id'].'" '.($id_session == $valSession['session_id'] ? 'selected' : '').'>'.$valSession['session_name'].'</option>';
						}
						echo '
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label">Campus</label>
					<select data-plugin-selectTwo data-width="100%" name="id_campus" id="id_campus" title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
															FROM ".CAMPUS." c  
															WHERE c.campus_id != '' AND campus_status = '1'
															ORDER BY c.campus_name ASC");
						while($value_campus = mysqli_fetch_array($sqllmscampus)){
							echo'<option value="'.$value_campus['campus_id'].'" '.($value_campus['campus_id']==$id_campus ? 'selected' : '').'>'.$value_campus['campus_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<center>
				<button type="submit" name="show_results" id="show_results" class="btn btn-primary"><i class="fa fa-search"></i> Filter Results</button>
			</center>
		</div>
	</form>
</section>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i> Exam Demand List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
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
					<th width="100" class="center">Action</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT d.demand_id, d.demand_status, d.is_publish, d.total_amount, d.id_session, d.id_examtype,
												t.type_name, s.session_name, d.total_std, d.total_amount, c.campus_name
												FROM ".EXAM_DEMAND." d
												INNER JOIN ".EXAM_TYPES." t ON t.type_id = d.id_examtype
												INNER JOIN ".SESSIONS." s ON s.session_id = d.id_session
												INNER JOIN ".CAMPUS." c ON c.campus_id = d.id_campus
												WHERE d.is_deleted	= '0'
												AND d.is_publish	= '1'
												AND d.demand_status	= '1' 
												$sql1
												$sql2
												GROUP BY d.demand_id
												ORDER BY d.demand_id DESC
										  ");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td style="width:40px; text-align:center;">'.$srno.'</td>
						<td class="center">'.$rowsvalues['session_name'].'</td>
						<td>'.$rowsvalues['campus_name'].'</td>
						<td>'.$rowsvalues['type_name'].'</td>
						<td class="center">'.$rowsvalues['total_std'].'</td>
						<td class="center">'.$rowsvalues['total_amount'].'</td>
						<td class="center">'.get_status($rowsvalues['demand_status']).'</td>
						<td class="center">'.get_statusyesno($rowsvalues['is_publish']).'</td>
						<td class="center">';
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '18', 'updated' => '1'))){ 
								echo'<a href="exam_demand.php?id_demand='.$rowsvalues['demand_id'].'&id_session='.$rowsvalues['id_session'].'&id_examtype='.$rowsvalues['id_examtype'].'&demand_status='.$rowsvalues['demand_status'].'&is_publish='.$rowsvalues['is_publish'].'&total_amount='.$rowsvalues['total_amount'].'&total_std='.$rowsvalues['total_std'].'" class="btn btn-primary btn-xs m-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
							}
							echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/exam_demand/detail.php?id='.$rowsvalues['demand_id'].'\');"><i class="glyphicon glyphicon-eye-open"></i></a>
						</td>
					</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</section>';
?>