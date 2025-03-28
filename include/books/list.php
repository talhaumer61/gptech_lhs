<?php 
echo '
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">
	<a href="#make_book" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
	<i class="fa fa-plus-square"></i> Add Book Category</a>
	<h2 class="panel-title"><i class="fa fa-list"></i>  Book category List</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th style="text-align:center;">#</th>
		<th>Category Name</th>
		<th width="70px;" style="text-align:center;">Status</th>
		<th width="100" style="text-align:center;">Options</th>
	</tr>
</thead>
<tbody>';
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT b.cat_id,b.cat_name, b.cat_status
								   FROM ".LMS_BOOKCATEGORY." b  
								   WHERE b.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY b.cat_name ASC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td style="text-align:center;">'.$srno.'</td>
	<td>'.$rowsvalues['cat_name'].'</td>
	<td style="text-align:center;">'.get_status($rowsvalues['cat_status']).'</td>
	<td>
		<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/books/update.php?id='.$rowsvalues['cat_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
		<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'sms_lms_bookcategory.php?deleteid='.$rowsvalues['cat_id'].'\');"><i class="el el-trash"></i></a>
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