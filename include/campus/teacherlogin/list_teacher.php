<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2)) { 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="#make_teacherlogin" class="modal-with-move-anim btn btn-primary btn-xs pull-right"> <i class="fa fa-plus-square"></i> Make Teacher Login</a>
		<a href="#export_teacherlogins" class="modal-with-move-anim btn btn-primary btn-xs pull-right mr-xs"> <i class="fa fa-download"></i> Export Teacher Login</a>
		<h2 class="panel-title"><i class="fa fa-list"></i> Teachers Login List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
			<thead>
				<tr>
					<th class="center">#</th>
					<th style="width: 40px;">Photo</th>
					<th>Full Name</th>
					<th>Department</th>
					<th>Phone</th>
					<th>Username</th>
					<th width="70px;" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, e.emply_photo, d.dept_name
												FROM ".ADMINS." a
												INNER JOIN ".EMPLOYEES." 	e ON e.id_loginid = a.adm_id
												INNER JOIN ".DEPARTMENTS."  d ON d.dept_id 	  = e.id_dept
												WHERE a.adm_logintype = '3' AND a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' AND a.is_deleted = '0'
												ORDER BY a.adm_username ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;

					if($rowsvalues['adm_photo']){
						$photo = 'uploads/images/admins/'.$rowsvalues['adm_photo'].'';
					} else if($rowsvalues['emply_photo']) {
						$photo = 'uploads/images/employees/'.$rowsvalues['emply_photo'].'';
					} else {
						$photo = 'uploads/images/employees/default.jpg';
					}
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
						<td>'.$rowsvalues['adm_fullname'].'</td>
						<td>'.$rowsvalues['dept_name'].'</td>
						<td>'.$rowsvalues['adm_phone'].'</td>
						<td>'.$rowsvalues['adm_username'].'</td>
						<td class="center">'.get_status($rowsvalues['adm_status']).'</td>
						<td>
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/teacherlogin/edit_teacher.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'teacherlogin.php?deleteid='.$rowsvalues['adm_id'].'\');"><i class="el el-trash"></i></a>
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