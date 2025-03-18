<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1'))){
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Class List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th class="center" width="70">No.</th>
						<th>Class Name</th>
						<th>Class Numeric</th>
						<th class="center" width="100">Options</th>
					</tr>
				</thead>
				<tbody>';
				$sqllms	= $dblms->querylms("SELECT c.class_id, c.class_status, c.class_code, c.class_name, c.class_attachment
												FROM ".CLASSES." c  
												WHERE c.class_id != '' AND is_deleted != '1'
												ORDER BY c.class_id ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['class_name'].'</td>
						<td>'.$rowsvalues['class_code'].'</td>
						<td class="center">';
							if(!empty($rowsvalues['class_attachment'])) {
								echo'<a href="uploads/class_subjects/'.$rowsvalues['class_attachment'].'" download="'.cleanvars($_SESSION['userlogininfo']['ACA_SESSION_NAME']).'-'.$rowsvalues['class_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i></a>
								<a href="uploads/class_subjects/'.$rowsvalues['class_attachment'].'" class="btn btn-info btn-xs mr-xs" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>';
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