<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))){ 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))){ 
			echo'<a href="employee.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Employee</a>';
		} echo'
		<a href="#emply_report" class="modal-with-move-anim-pvs btn btn-primary btn-xs pull-right mr-xs" onclick="showAjaxModalZoom(\'../../modals/employee/employee_reporting.php\');"><i class="fa fa-print"></i> Report</a>
		<h2 class="panel-title"><i class="fa fa-list"></i>  Employees List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
			<thead>
				<tr>
					<th class="center">Sr No</th>
					<th width="40px">Photo</th>
					<th>Employee Name</th>
					<th>Type</th>
					<th>Regestration Number</th>
					<th>Department</th>
					<th>Designation</th>
					<th>Phone</th>
					<th width="70px;" class="center">Status</th>
					<th width="100px;" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT e.emply_id, e.emply_status, e.emply_regno, e.emply_name, e.id_dept, 
												e.id_designation, e.id_type, e.emply_gender, e.emply_dob, e.emply_joindate,
												e.emply_education, e.emply_experence, e.emply_religion, e.emply_bloodgroup,
												e.emply_address, e.emply_phone, e.emply_email, e.emply_photo,
												d.dept_name,
												dp.designation_name 
												FROM ".EMPLOYEES." e     
												INNER JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
												INNER JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
												WHERE e.emply_id != '' AND e.is_deleted != '1'
												AND e.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
												ORDER BY e.emply_name ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>';
							if($rowsvalues['emply_photo']) { 
								echo'<img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" style="width:40px; height:40px;">' ;
							} else {
									echo "No Image";
							}
						echo'
						</td>
						<td>'.$rowsvalues['emply_name'].'</td>
						<td>'.get_emplytype($rowsvalues['id_type']).'</td>
						<td>'.$rowsvalues['emply_regno'].'</td>
						<td>'.$rowsvalues['dept_name'].'</td>
						<td>'.$rowsvalues['designation_name'].'</td>
						<td>'.$rowsvalues['emply_phone'].'</td>
						<td class="center">'.get_status($rowsvalues['emply_status']).'</td>
						<td class="center">';
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'edit' => '1'))){ 
								echo'<a class="btn btn-success btn-xs" href="employee.php?id='.$rowsvalues['emply_id'].'"> <i class="fa fa-user-circle-o"></i></a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'delete' => '1'))){ 
								echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'employee.php?deleteid='.$rowsvalues['emply_id'].'\');"><i class="el el-trash"></i></a>';
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
else
{
	header("location: dashboard.php");
}
?>