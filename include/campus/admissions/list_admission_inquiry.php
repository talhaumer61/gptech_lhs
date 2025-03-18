<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'view' => '1')))
{ 
echo '
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">
	<a href="#admission_inquiry" class="modal-with-move-anim btn btn-primary btn-xs pull-right">';
	if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'added' => '1')))
{ 
echo'

	<i class="fa fa-plus-square"></i> Make Inquiry</a>
	<h2 class="panel-title"><i class="fa fa-list"></i>  Inquiry List</h2>';
}
echo'

</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
<thead>
	<tr>
		<th class="center">No.</th>
		<th>Form no.</th>
		<th>Name</th>
		<th>Father Name</th>
		<th>Cell No.</th>
		<th>Dated</th>
		<th>Source</th>
		<th>Class</th>
		<th width="70px;" class="center">Status</th>
		<th width="100" class="center">Options</th>
	</tr>
</thead>
<tbody>';
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT q.id, q.form_no, q.status, q.name, q.fathername, q.cell_no, q.address, q.note, q.date_added, q.source,
								c.class_name
								FROM ".ADMISSIONS_INQUIRY." q  
								INNER JOIN ".CLASSES." c ON c.class_id = q.id_class
								WHERE q.is_deleted != '1'
								AND q.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								ORDER BY q.id DESC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td class="center">'.$srno.'</td>
	<td>'.$rowsvalues['form_no'].'</td>
	<td>'.$rowsvalues['name'].'</td>
	<td>'.$rowsvalues['fathername'].'</td>
	<td>'.$rowsvalues['cell_no'].'</td>
	<td>'.date("d M Y", strtotime($rowsvalues['date_added'])).'</td>
	<td>'.get_inquirysrc($rowsvalues['source']).'</td>
	<td>'.$rowsvalues['class_name'].'</td>
	<td class="center">'.get_inqStatus($rowsvalues['status']).'</td>
	<td class="center">';
	if($rowsvalues['status'] != '3' && ($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || ($_SESSION['userlogininfo']['LOGINIDA'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'added' => '1'))){
	echo'<a href="students.php?inquiry='.$rowsvalues['id'].'" class="btn btn-success btn-xs";"><i class="glyphicon glyphicon-plus-sign"></i> </a>';
	}
	if($rowsvalues['status'] != '3' && ($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || ($_SESSION['userlogininfo']['LOGINIDA'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'updated' => '1')))
	{ 
		echo'
			<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/admissions/modal_admission_inquiry_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>
		';
	}
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || ($_SESSION['userlogininfo']['LOGINIDA'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'deleted' => '1')))
	{ 
		echo'
			<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'admission_inquiry.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>
		';
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