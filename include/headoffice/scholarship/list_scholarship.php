<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'view' => '1'))){ 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'added' => '1'))){ 
				echo'
				<a href="#make_scholarship" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
				<i class="fa fa-plus-square"></i> Make Scholarship
				</a>';
			}
			echo '
			<h2 class="panel-title"><i class="fa fa-list"></i>  Scholarship List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th style="text-align:center; width: 50px;">#</th>
						<th>Student Regno.</th>
						<th>Student</th>
						<th>Category</th>
						<th>Amount</th>
						<th>Session</th>
						<th>Campus </th>
						<th width="70px;" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.amount, c.cat_name,
													st.std_name, st.std_fathername, st.std_regno,
								   					se.session_id, se.session_name,
													cp.campus_name
													FROM ".SCHOLARSHIP." s
													INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
													INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std
													INNER JOIN ".CAMPUS." cp ON cp.campus_id = s.id_campus
													INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
													WHERE s.id_type = '1' AND c.cat_type = '1'
													ORDER BY s.id DESC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['std_regno'].'</td>
							<td>'.$rowsvalues['std_name'].' '.$rowsvalues['std_fathername'].'</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.$rowsvalues['amount'].'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td class="center">'.get_payments($rowsvalues['status']).'</td>
							<td class="text-center">';
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'updated' => '1'))){ 
							echo'
								<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" 
								onclick="showAjaxModalZoom(\'include/modals/scholarship/scholarship_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'deleted' => '1'))){ 
							echo'
								<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'notice.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
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
} else {
	header("Location: dashboard.php");
}
?>