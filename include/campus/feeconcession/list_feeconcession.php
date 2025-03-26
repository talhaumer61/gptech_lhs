<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'view' => '1'))){ 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'added' => '1'))){ 
			echo'
			<a href="feeconcession.php?view=add" class="btn btn-primary btn-xs pull-right">
				<i class="fa fa-plus-square"></i> Make Fee Concession
			</a>';
		}
		echo '
		<h2 class="panel-title"><i class="fa fa-list"></i>  Fee Concession List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width=50px>#</th>
					<th>Student Regno.</th>
					<th>Student</th>
					<th>Father Name</th>
					<th>Amount </th>
					<th>Class </th>
					<th>Category</th>
					<th>Session </th>
					<th width="70px;" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.percent, cd.amount, s.note,
													c.cat_id, c.cat_name, c.cat_type,
													st.std_id, st.std_name, st.std_fathername, st.std_regno,
													cl.class_name, se.session_name
													FROM ".SCHOLARSHIP." s
													INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
													INNER JOIN ".CONCESSION_DETAIL." cd ON cd.id_setup = s.id
													INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std
													INNER JOIN ".CLASSES." cl ON cl.class_id = s.id_class
													INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
													WHERE s.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
													AND s.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' AND s.id_type = '2' AND c.cat_type = '2' AND s.is_deleted = 0
													ORDER BY s.id ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['std_regno'].'</td>
						<td>'.$rowsvalues['std_name'].'</td>
						<td>'.$rowsvalues['std_fathername'].'</td>
						<td>'.$rowsvalues['amount'].'</td>
						<td>'.$rowsvalues['class_name'].'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
						<td>'.$rowsvalues['session_name'].'</td>
						<td class="center">'.get_status($rowsvalues['status']).'</td>
						<td class="text-center">';
						if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'updated' => '1'))){ 
							echo'<a href="feeconcession.php?id='.$rowsvalues['id'].'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>';
						}
						if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'deleted' => '1'))){ 
							echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'feeconcession.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
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