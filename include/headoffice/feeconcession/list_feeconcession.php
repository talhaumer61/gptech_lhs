<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'view' => '1'))){ 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Fee Concession List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width=50px>#</th>
					<th>Student Regno.</th>
					<th>Student</th>
					<th>Class </th>
					<th>Category</th>
					<th>Session</th>
					<th>Campus</th>
					<th width="70px;" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.percent, s.amount, s.note,
													c.cat_id, c.cat_name, c.cat_type,
													st.std_id, st.std_name, st.std_fathername, st.std_regno,
													cl.class_name, se.session_name, cp.campus_name
													FROM ".SCHOLARSHIP." s
													INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
													INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std
													INNER JOIN ".CLASSES." cl ON cl.class_id = s.id_class
													INNER JOIN ".CAMPUS." cp ON cp.campus_id = s.id_campus
													INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
													WHERE s.id_type = '2' AND c.cat_type = '2'
													ORDER BY s.id ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['std_regno'].'</td>
						<td>'.$rowsvalues['std_name'].' '.$rowsvalues['std_fathername'].'</td>
						<td>'.$rowsvalues['class_name'].'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
						<td>'.$rowsvalues['session_name'].'</td>
						<td>'.$rowsvalues['campus_name'].'</td>
						<td class="center">'.get_status($rowsvalues['status']).'</td>
						<td class="text-center">';
						if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'updated' => '1'))){ 
							echo'<a href="feeconcession.php?id='.$rowsvalues['id'].'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-eye-open"></i></a>';
						}
						if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'deleted' => '1'))){ 
							echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'notice.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
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
}
else{
	header("Location: dashboard.php");
}
?>