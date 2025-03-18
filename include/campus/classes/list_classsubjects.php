<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'view' => '1'))){
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="#print_subjects" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-print"></i> Print
		</a>
		<h2 class="panel-title"><i class="fa fa-list"></i>  Subject List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width="70">No.</th>
					<th>Subject Code</th>
					<th>Subject Name</th>
					<th>Weekly Lectures</th>
					<th>Medium Of Instruction</th>
					<th class="center">Subject Type</th>
					<th>Class Name</th>
					<th>Montly Total Marks</th>
					<th>Montly Passing Marks</th>
					<th>Term Total Marks</th>
					<th>Term Passing Name</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT sub.subject_id, sub.subject_status, sub.subject_code, sub.subject_name, sub.weekly_period, sub.monthly_totalmarks, sub.monthly_passmarks, sub.term_totalmarks, sub.term_passmarks, sub.subject_type, sub.instruction_medium, sub.id_cat, sub.id_class,
												c.class_name
												FROM ".CLASS_SUBJECTS." sub 
												INNER JOIN ".CLASSES." c ON c.class_id = sub.id_class
												WHERE sub.subject_id != '' AND sub.is_deleted != '1'
												AND sub.subject_status = '1' 
												ORDER BY c.class_name, sub.subject_name ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['subject_code'].'</td>
						<td>'.$rowsvalues['subject_name'].'</td>
						<td>'.$rowsvalues['weekly_period'].'</td>
						<td class="center">'.get_instrmedium($rowsvalues['instruction_medium']).'</td>
						<td class="center">'.get_subjecttype($rowsvalues['subject_type']).'</td>
						<td>'.$rowsvalues['class_name'].'</td>
						<td>'.$rowsvalues['monthly_totalmarks'].'</td>
						<td>'.$rowsvalues['monthly_passmarks'].'</td>
						<td>'.$rowsvalues['term_totalmarks'].'</td>
						<td>'.$rowsvalues['term_passmarks'].'</td>
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
