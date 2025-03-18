<?php
$campus = cleanvars($_GET['id']);
echo '
<div id="list_royalty" class="tab-pane ">
	
	<div class="panel-body">
		<header class="mb-md">
			<a href="#make_royalty" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Royalty</a>
		</header>
		<br>
		<table class="table table-bordered table-striped table-condensed mb-none table_export" >
			<thead>
				<tr>
					<th width="30" class="center">Sr#</th>
					<th >Duration</th>
					<th width="150px;" class="center">Royalty Type</th>
					<th width="150px;" class="center">Royalty Amount</th>
					<th width="70px;" class="center">Status</th>
					<th width="100px;" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT *
											FROM ".ROYALTY_SETTING." 
											WHERE id_campus = '".$campus."' 
											AND is_deleted = '0'
											ORDER BY start_date ASC
										  ");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
				$srno++;
				echo '
				<tr>
					<td class="center">'.$srno.'</td>
					<td >'.date('d M, Y', strtotime($rowsvalues['start_date'])).' - '.date('d M, Y', strtotime($rowsvalues['end_date'])).'</td>
					<td class="center">'.get_royaltyTypes($rowsvalues['royalty_type']).'</td>
					<td class="center">'.$rowsvalues['royalty_amount'].'</td>
					<td class="center">'.get_status($rowsvalues['status']).'</td>
					<td class="center">
						<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/royalty/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>
						<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'campuses.php?id='.$campus.'&deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>
					</td>
				</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</div>';
?>