<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2)) { 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="#make_parentlogin" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Parent Login</a>
		<a href="#export_parentlogins" class="modal-with-move-anim btn btn-primary btn-xs pull-right mr-xs"> <i class="fa fa-download"></i> Export Parent Logins</a>
		<h2 class="panel-title"><i class="fa fa-list"></i> Parent List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
			<thead>
				<tr>
					<th class="center">#</th>
					<th style="width: 40px;">Photo</th>
					<th>Full Name</th>
					<th>Class</th>
					<th>Section</th>
					<th>Parent Contact</th> 
					<th>Username</th>
					<th width="70px;" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, s.std_photo, c.class_name, se.section_name
												FROM ".ADMINS." a
												INNER JOIN ".STUDENTS."  	  s  ON s.id_loginid 	= a.adm_id
												LEFT JOIN ".CLASSES."   	  c  ON c.class_id 		= s.id_class
												LEFT JOIN ".CLASS_SECTIONS."  se  ON se.section_id 	= s.id_section
												WHERE a.adm_logintype = '5' AND a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  AND a.is_deleted = '0'
												ORDER BY a.adm_username ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;

					if($rowsvalues['adm_photo']){
						$photo = 'uploads/images/admins/'.$rowsvalues['adm_photo'].'';
					} else if($rowsvalues['std_photo']) {
						$photo = 'uploads/images/students/'.$rowsvalues['std_photo'].'';
					} else {
						$photo = 'uploads/default-student.jpg';
					}
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
						<td>'.$rowsvalues['adm_fullname'].'</td>
						<td>'.$rowsvalues['class_name'].'</td>
						<td>'.$rowsvalues['section_name'].'</td>
						<td>'.$rowsvalues['adm_phone'].'</td>
						<td>'.$rowsvalues['adm_username'].'</td>
						<td class="center">'.get_status($rowsvalues['adm_status']).'</td>
						<td>
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/parentlogin/edit_parent.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'parentlogin.php?deleteid='.$rowsvalues['adm_id'].'\');"><i class="el el-trash"></i></a>
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