<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1)){ 
	echo '
	<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) ){ 
		echo '
		<a href="#make_hostel" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
		<i class="fa fa-plus-square"></i> Add Qualification Level</a>';
	}
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i>  Qualification Level List</h2>
	</header>
	<div class="panel-body">
	<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
	<thead>
		<tr>
			<th style="text-align:center;">No.</th>
			<th>Qualification Level Name</th>
			<th>Qualification Level Code</th>
			<th width="70px;" style="text-align:center;">Status</th>';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1)){ 
				echo '
					<th width="100" style="text-align:center;">Options</th>';
			}
			echo '
		</tr>
	</thead>
	<tbody>';
	
	$sqllms	= $dblms->querylms("SELECT q.q_l_id, q.q_l_name, q.q_l_code, q.q_l_status  
									FROM ".QUALIFICATION_LEVELS." q 
									WHERE q.is_deleted= '0'
									ORDER BY q.q_l_name ASC");
	$srno = 0;
	
	while($rowsvalues = mysqli_fetch_array($sqllms)) {
		
	$srno++;
	
	echo '
	<tr>
		<td style="text-align:center;">'.$srno.'</td>
		<td>'.$rowsvalues['q_l_name'].'</td>
		<td>'.$rowsvalues['q_l_code'].'</td>
		<td style="text-align:center;">'.get_status($rowsvalues['q_l_status']).'</td>';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1)){ 
			echo '
			<td>';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1)){ 
			echo'
				<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/qualification_level/modal_qualification_level_update.php?id='.$rowsvalues['q_l_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
			}
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1)){ 
			echo'
				<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'qualification_level.php?deleteid='.$rowsvalues['q_l_id'].'\');"><i class="el el-trash"></i></a>';
			}
			echo'
			</td>';
		}
	echo '
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