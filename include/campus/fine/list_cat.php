<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'view' => '1'))){ 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Fine Category List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th style="text-align:center; width: 50px;">#</th>
						<th>Name</th>
						<th>Detail</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT cat_id, cat_status, cat_name, cat_detail
													FROM ".SCHOLARSHIP_CAT."
													WHERE cat_type = '3'
													AND is_deleted != '1'
													AND cat_status = '1'
													ORDER BY cat_id ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.$rowsvalues['cat_detail'].'</td>
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