<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'view' => '1'))){
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<a href="#make_timetable" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
				<i class="fa fa-plus-square"></i> Make Lecture
			</a>
			<h2 class="panel-title"><i class="fa fa-list"></i>  Lectures List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>												
						<th width="50" class="center">Sr.</th>
						<th>Lecture Name</th>
						<th width="50">Order</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT period_id, period_ordering, period_status, period_name, period_timestart, period_timeend 
												FROM ".PERIODS."  
												WHERE period_id != ''  AND is_deleted != '1'
												AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
												ORDER BY period_ordering ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['period_name'].'</td>
							<td>'.$rowsvalues['period_ordering'].'</td>
							<td class="center">'.get_status($rowsvalues['period_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'edit' => '1'))){
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/timetable/period/modals_period_update.php?id='.$rowsvalues['period_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'delete' => '1'))){
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'timetable_period.php?deleteid='.$rowsvalues['period_id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
					}
					echo'
				</tbody>
			</table>
		</div>
	</section>';
}
else{
	header("Location: dashboard.php");
}
?>