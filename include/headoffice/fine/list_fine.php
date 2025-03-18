<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'view' => '1'))){ 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading"> 	
		<h2 class="panel-title"><i class="fa fa-list"></i>  Fine List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th style="text-align:center; width: 50px;">#</th>
					<th>Student Reg no.</th>
					<th>Student</th>
					<th>Fine Name</th>
					<th>Amount</th>
					<th>Date</th>
					<th>Session </th>
					<th>Campus </th>
					<th>Note </th>
					<th>Status </th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.id_type, s.amount, s.date, s.id_cat,  s.id_std, s.id_session, s.note, s.id_campus,
												c.cat_id, c.cat_name, c.cat_type,
												st.std_id, st.std_name, st.std_fathername, st.std_regno,
												se.session_id, se.session_name, cp.campus_name
												FROM ".SCHOLARSHIP." s
												INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
												INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std
												INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
												INNER JOIN ".CAMPUS." cp ON cp.campus_id = s.id_campus
												WHERE c.cat_type = '3'
												ORDER BY s.id ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td style="text-align:center;">'.$srno.'</td>
						<td>'.$rowsvalues['std_regno'].'</td>
						<td>'.$rowsvalues['std_name'].' '.$rowsvalues['std_fathername'].'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
						<td>'.$rowsvalues['amount'].'</td>
						<td>'.$rowsvalues['date'].'</td>
						<td>'.$rowsvalues['session_name'].'</td>
						<td>'.$rowsvalues['campus_name'].'</td>
						<td>'.$rowsvalues['note'].'</td>
						<td style="text-align:center;">'.get_status($rowsvalues['status']).'</td>
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