<?php
$campus = cleanvars($_GET['id']);
echo '
<div id="list_exam_fee" class="tab-pane ">
	
	<div class="panel-body">
		<header class="mb-md">
			<a href="#make_exam_fee" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Exam Fee</a>
		</header>
		<br>
		<table class="table table-bordered table-striped table-condensed mb-none table_export" >
			<thead>
				<tr>
					<th width="30" class="center">Sr#</th>
					<th>Exam Type</th>
					<th width="120px;" class="center">Fee Per Student</th>
					<th width="70px;" class="center">Status</th>
					<th width="100px;" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT e.id, e.status, e.fee_per_std, et.type_name 
											FROM ".EXAM_FEE." as e    
											INNER JOIN ".EXAM_TYPES." as et ON et.type_id = e.id_exam_type    
											WHERE e.id_campus = '".$campus."' 
											AND e.is_deleted = '0'
											ORDER BY e.id DESC
										  ");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
				$srno++;
				echo '
				<tr>
					<td class="center">'.$srno.'</td>
					<td>'.$rowsvalues['type_name'].'</td>
					<td class="center">'.$rowsvalues['fee_per_std'].'</td>
					<td class="center">'.get_status($rowsvalues['status']).'</td>
					<td class="center">
						<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/exam_fee/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>
						<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'campuses.php?id='.$campus.'&deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>
					</td>
				</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</div>';
?>