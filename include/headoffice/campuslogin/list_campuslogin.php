<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)) { 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<a href="#make_campuslogin" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Campus Login</a>
			<h2 class="panel-title"><i class="fa fa-list"></i> Campus Login List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th style="text-align:center;">#</th>
						<th style="width: 40px;">Photo</th>
						<th>Username</th>
						<th>Full Name</th>
						<th>Campus</th>
						<th>Phone</th> 
						<th width="70px;" style="text-align:center;">Status</th>
						<th width="100" style="text-align:center;">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, a.id_campus, c.campus_name
													FROM ".ADMINS." a
													INNER JOIN ".CAMPUS." c ON c.campus_id = a.id_campus
													WHERE a.adm_type	= '1'
													AND a.adm_logintype	= '2'
													and a.is_deleted	= '0'
													ORDER BY c.campus_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						if($rowsvalues['adm_photo']){
							$photo = 'uploads/images/admins/'.$rowsvalues['adm_photo'].'';
						}else{
							$photo = 'uploads/logo.png';
						}
						echo'
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
							<td>'.$rowsvalues['adm_username'].'</td>
							<td>'.$rowsvalues['adm_fullname'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td>'.$rowsvalues['adm_phone'].'</td>
							<td style="text-align:center;">'.get_status($rowsvalues['adm_status']).'</td>
							<td>
								<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/campuslogin/edit_campuslogin.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
								<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'campuslogin.php?deleteid='.$rowsvalues['adm_id'].'\');"><i class="el el-trash"></i></a>
							</td>
						</tr>';
					}
					echo'
				</tbody>
			</table>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>