<?php
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '14', 'add' => '1'))){
			echo'<a href="?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add Exam Demand</a>';
		}
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i> Exam Demand List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th width="20" class="center">Sr#</th>
					<th width="80" class="center">Session</th>
					<th>Exam Type</th>
					<th width="110" class="center">Total Students</th>
					<th width="110" class="center">Total Amount</th>
					<th width="70" class="center">Status</th>
					<th width="70" class="center">Publish</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				//-----------------------------------------------------
				$sqllms	= $dblms->querylms("SELECT d.demand_id, d.demand_status, d.is_publish, d.total_amount, d.id_session, d.id_examtype,
												t.type_name, s.session_name, d.total_std, d.total_amount
												FROM ".EXAM_DEMAND." d
												INNER JOIN ".EXAM_TYPES." t ON t.type_id = d.id_examtype
												INNER JOIN ".SESSIONS." s ON s.session_id = d.id_session
												WHERE d.id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
												AND d.is_deleted	= '0' 
												ORDER BY d.demand_id DESC");
				$srno = 0;
				//-----------------------------------------------------
				while($rowsvalues = mysqli_fetch_array($sqllms)){
					$srno++;
					echo '
					<tr>
						<td style="width:40px; text-align:center;">'.$srno.'</td>
						<td class="center">'.$rowsvalues['session_name'].'</td>
						<td>'.$rowsvalues['type_name'].'</td>
						<td class="center">'.$rowsvalues['total_std'].'</td>
						<td class="center">'.$rowsvalues['total_amount'].'</td>
						<td class="center">'.get_status($rowsvalues['demand_status']).'</td>
						<td class="center">'.get_statusyesno($rowsvalues['is_publish']).'</td>
						<td class="center">';
							if($rowsvalues['is_publish']==2){
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) ||($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || ($_SESSION['userlogininfo']['LOGINIDA'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '18', 'updated' => '1'))){ 
									echo'<a href="exam_demand.php?id_demand='.$rowsvalues['demand_id'].'&id_session='.$rowsvalues['id_session'].'&id_examtype='.$rowsvalues['id_examtype'].'&demand_status='.$rowsvalues['demand_status'].'&is_publish='.$rowsvalues['is_publish'].'&total_amount='.$rowsvalues['total_amount'].'" class="btn btn-primary btn-xs m-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) ||($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || ($_SESSION['userlogininfo']['LOGINIDA'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '18', 'deleted' => '1'))){ 
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'exam_demand.php?deleteid='.$rowsvalues['demand_id'].'\');"><i class="el el-trash"></i></a>';
								}
							}else{
								echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/exam_demand/detail.php?id='.$rowsvalues['demand_id'].'\');"><i class="glyphicon glyphicon-eye-open"></i></a>';
							}
						echo'
							
						</td>
					</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</section>';
?>