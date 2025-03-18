<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'view' => '1'))){
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="#make_timetable" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Make Extra Lecture
		</a>
		<h2 class="panel-title"><i class="fa fa-list"></i>  Extra Lectures List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>												
					<th class="center">#</th>
					<th>class</th>
					<th>Subject</th>
					<th>Teacher</th>
					<th>Room</th>
					<th>Date</th>
					<th>Time</th>
					<th width="70px;" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT t.id, t.status, t.id_section, t.from_date, t.to_date, t.from_time, t.to_time, c.class_name, se.section_name, sb.subject_name, e.emply_name, r.room_no
												FROM ".TIMETABLE_EXTRA_PERIOD."  t
												INNER JOIN ".CLASSES." 		 c  ON c.class_id 		= t.id_class 
												LEFT  JOIN ".CLASS_SECTIONS." se ON se.section_id 	= t.id_section 
												INNER JOIN ".CLASS_SUBJECTS." sb ON sb.subject_id  	= t.id_subject 
												INNER JOIN ".EMPLOYEES." 	 e  ON e.emply_id 		= t.id_teacher 
												INNER JOIN ".CLASS_ROOMS." 	 r  ON r.room_id 		= t.id_room 
												WHERE t.id != ''  AND t.is_deleted != '1'
												AND t.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
												AND t.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
												ORDER BY t.from_date ASC, t.from_time");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['class_name'].''; if($rowsvalues['id_section'] != 0){echo' ('.$rowsvalues['section_name'].')';} echo'</td>
						<td>'.$rowsvalues['subject_name'].'</td>
						<td>'.$rowsvalues['emply_name'].'</td>
						<td>'.$rowsvalues['room_no'].'</td>
						<td><b>'.date('D d M Y', strtotime($rowsvalues['from_date'])).'</b> To <b>'.date('D d M Y', strtotime($rowsvalues['to_date'])).'</b></td>
						<td><b>'.date("g:i A", strtotime($rowsvalues['from_time'])).'</b> To <b>'.date("g:i A", strtotime($rowsvalues['to_time'])).'</b></td>
						<td class="center">'.get_status($rowsvalues['status']).'</td>
						<td class="center">';
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'edit' => '1'))){
							echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/timetable/extra_period/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
						}
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'delete' => '1'))){
							echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'timetable_period.php?deleteid=\');"><i class="el el-trash"></i></a>';
						}
						echo'
						</td>
					</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</section>
';
}
else{
	header("Location: dashboard.php");
}
?>