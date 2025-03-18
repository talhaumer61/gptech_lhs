<?php
echo '
<div id="bankDetail" class="tab-pane">
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<a href="#make_bank" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Add Bank</a>
			<h2 class="panel-title"><i class="fa fa-list"></i> Bank List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th style="text-align:center;">#</th>
						<th>Type</th>
						<th>Bank Name</th>
						<th>Account Title</th>
						<th>Account No</th>
						<th>IBAN</th>
						<th>Branch Code</th>
						<th width="70px;" style="text-align:center;">Status</th>
						<th width="100" style="text-align:center;">Options</th>
					</tr>
				</thead>
				<tbody>';	
				$sqllms	= $dblms->querylms("SELECT bank_id, bank_status, id_type, bank_name, account_title, account_no, iban_no, branch_code
											FROM ".BANKS."
											WHERE is_deleted = '0'  
											ORDER BY id_type ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td style="text-align:center;">'.$srno.'</td>
						<td>'.get_fundType($rowsvalues['id_type']).'</td>
						<td>'.$rowsvalues['bank_name'].'</td>
						<td>'.$rowsvalues['account_title'].'</td>
						<td>'.$rowsvalues['account_no'].'</td>
						<td>'.$rowsvalues['iban_no'].'</td>
						<td>'.$rowsvalues['branch_code'].'</td>
						<td style="text-align:center;">'.get_status($rowsvalues['bank_status']).'</td>
						<td>
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/banks/modal_bank_update.php?id='.$rowsvalues['bank_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'profile.php?deleteid='.$rowsvalues['bank_id'].'\');"><i class="el el-trash"></i></a>
						</td>
					</tr>';
				}
				echo '
				</tbody>
			</table>
		</div>
	</section>
</div>';
?>