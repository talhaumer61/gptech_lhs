<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){ 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){ 
				echo'<a href="#make_campus" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Campus </a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Campus List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none table_export" >
				<thead>
					<tr>
						<th width="20" class="center">Sr.</th>
						<th width="30">Logo</th>
						<th width="30">Zone</th>
						<th>Govt Reg#</th>
						<th>Code</th>
						<th>Name</th>
						<th>Head</th>
						<th>E-mail</th>
						<th>Phone</th>
						<th width="70px;" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT c.campus_id, c.campus_status, c.campus_regno, c.campus_regno_gov, c.campus_code, c.campus_name, c.campus_address, c.campus_email, c.campus_phone, c.campus_head, c.campus_fax, c.campus_website, c.campus_logo, c.id_zone
												FROM ".CAMPUS." c  
												ORDER BY c.campus_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						if($rowsvalues['campus_logo'] && file_exists("uploads/images/campus/".$rowsvalues['campus_logo']."")) { 
							$logo = "uploads/images/campus/".$rowsvalues['campus_logo'];
						}else{
							$logo = "uploads/logo.png";
						}
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td class="center"><img src="'.$logo.'" style="width:40px; height:40px;"></td>
							<td>'.get_AreaZone($rowsvalues['id_zone']).'</td>
							<td>'.$rowsvalues['campus_regno_gov'].'</td>
							<td>'.$rowsvalues['campus_code'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td>'.$rowsvalues['campus_head'].'</td>
							<td>'.$rowsvalues['campus_email'].'</td>
							<td>'.$rowsvalues['campus_phone'].'</td>
							<td class="center">'.get_status($rowsvalues['campus_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'edit' => '1'))){ 
									echo'<a href="campuses.php?id='.$rowsvalues['campus_id'].'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'delete' => '1'))){ 
									echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'hostels.php?delete campus_id='.$rowsvalues['campus_id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
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