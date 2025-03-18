<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1'))){
echo '
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">';
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'add' => '1'))){
	echo'
	<a href="#make_circular" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
		<i class="fa fa-plus-square"></i> Make Circular
	</a>';
	}
	echo'
	<h2 class="panel-title"><i class="fa fa-list"></i> Circular List</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th width="70px" class="center">Sr #</th>
		<th width="100">Dated</th>
		<th>Refr#</th>
		<th>Subject</th>
		<th width="70px" class="center">Status</th>
		<th width="100" class="center">Options</th>
	</tr>
</thead>
<tbody>';
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT cir_id, cir_status, cir_subject, cir_dated, cir_refrence
								   FROM ".CIRCULARS."
								   WHERE cir_id != '' AND is_deleted != '1'
								   AND id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
                                   AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
								   ORDER BY cir_id DESC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td class="center">'.$srno.'</td>
	<td>'.date('d M, Y', strtotime($rowsvalues['cir_dated'])).'</td>
	<td>'.$rowsvalues['cir_refrence'].'</td>
	<td>'.$rowsvalues['cir_subject'].'</td>
	<td class="center">'.get_status($rowsvalues['cir_status']).'</td>
	<td class="center">
	<a href="circular_print.php?id='.$rowsvalues['cir_id'].'" class="btn btn-success btn-xs" target="_blank"><i class="glyphicon glyphicon-file"></i></a>';
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'edit' => '1'))){
		echo'
		<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/circular/update.php?id='.$rowsvalues['cir_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
	}
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'delete' => '1'))){
	echo'
		<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'circulation.php?deleteid='.$rowsvalues['cir_id'].'\');"><i class="el el-trash"></i></a>';
	}
	echo'
	</td>
</tr>';
//-----------------------------------------------------
}
//-----------------------------------------------------
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