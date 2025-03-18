<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'view' => '1'))){ 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Assessment Schemes List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th width="20" class="center">Sr.</th>
					<th width="80" class="center">Session</th>
					<th>Exam</th>
					<th>Class</td>
					<th>Subject</td>
					<th>Note</th>
					<th width="70px" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT s.id , s.status, s.id_type, s.id_exam, s.file, s.id_month, s.id_sbuject_cat, s.note, e.type_name, se.session_name, c.class_name
												FROM ".EXAM_DOWNLOADS." s 
												INNER JOIN ".EXAM_TYPES." e ON e.type_id = s.id_exam
												INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
												LEFT JOIN ".CLASSES." c ON c.class_id = s.id_class
												WHERE s.id != '' 
												AND s.status = '1' 
												AND s.is_deleted != '1' 
												AND s.id_session = '".$_SESSION['userlogininfo']['EXAM_SESSION']."'
												ORDER BY s.id DESC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['session_name'].'</td>
						<td>'.$rowsvalues['type_name'].'</td>
						<td>'.$rowsvalues['class_name'].'</td>
						<td>'.get_subjectcat($rowsvalues['id_sbuject_cat']).'</td>
						<td>'.$rowsvalues['note'].'</td>
						<td class="center">'.get_status($rowsvalues['status']).'</td>
						<td width="120px" class="center">
							<a href="uploads/assessment_downloads/'.$rowsvalues['file'].'" download="'.$rowsvalues['session_name'].'-'.get_assessment($rowsvalues['id_type']).'-'.$rowsvalues['type_name'].'-'.get_subjectcat($rowsvalues['id_sbuject_cat']).'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
							<a href="uploads/assessment_downloads/'.$rowsvalues['file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>
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