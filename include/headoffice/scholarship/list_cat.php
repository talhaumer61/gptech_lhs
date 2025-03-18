<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'view' => '1'))){ 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'added' => '1'))){ 
				echo'
				<a href="#make_cat" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
				<i class="fa fa-plus-square"></i> Make Scholarship Category
				</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Scholarship Category List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width=50px>#</th>
						<th>Name</th>
						<th>Detail</th>
						<th width="70px;" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT cat_id, cat_status, cat_type, cat_name, cat_detail
													FROM ".SCHOLARSHIP_CAT."
													WHERE cat_type = '1'
													ORDER BY cat_id DESC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.$rowsvalues['cat_detail'].'</td>
							<td class="center">'.get_status($rowsvalues['cat_status']).'</td>
							<td class="text-center">';
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'updated' => '1'))){ 
								echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/scholarship/cat_update.php?id='.$rowsvalues['cat_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '38', 'deleted' => '1'))){ 
								echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'notice.php?deleteid='.$rowsvalues['cat_id'].'\');"><i class="el el-trash"></i></a>';
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