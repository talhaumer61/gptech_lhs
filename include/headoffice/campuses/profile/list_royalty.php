<?php
$campus = cleanvars($_GET['id']);

echo '
<div id="list_royalty" class="tab-pane ">
	<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="campus_id" id="campus_id" value="'.cleanvars($_GET['id']).'">
		<div class="panel-body">';			
			// Royalty Type
			$sqllmsRoyalty	= $dblms->querylms("SELECT id, royalty_status, royalty_type
												FROM ".ROYALTY_SETTING." r
												WHERE id_campus = '".$_GET['id']."'
												AND royalty_type = '3'
												AND is_deleted	= '0'
												ORDER BY id ASC");
			if(mysqli_num_rows($sqllmsParticulars) > 0){
				echo'
				<div class="table-responsive">
					<table class="table table-bordered table-condensed table-striped mb-none">
						<thead>
							<tr>
								<th class="center" width="40">Sr.</th>
								<th class="center">Title</th>';
								$sqlParticulars = $dblms->querylms("SELECT part_id, part_name
																		FROM ".ROYALTY_PARTICULARS."
																		WHERE part_status = '1' 
																		AND is_deleted = '0'
																		ORDER BY part_id DESC");
								$ParticularArray = array();
								while($valueParticulars = mysqli_fetch_array($sqlParticulars)):
									$ParticularArray[] = $valueParticulars;
									echo'<th class="center">'.$valueParticulars['part_name'].'</th>';
								endwhile;
								echo'
								<th class="center">Active</th>
							</tr>
						</thead>
						<tbody>';
						$sr=0;
						$valRoyaltyArray = array();
						while($valRoyaltyCheck = mysqli_fetch_array($sqllmsRoyalty)):
							$valRoyaltyArray[] = $valRoyaltyCheck;
							$sr++;
							echo'
							<tr>
								<td class="center">'.$sr.'</td>
								<td>'.get_royaltyType($valRoyaltyCheck['royalty_type']).'</td>';
								foreach ($ParticularArray as $particular):
									$sqlRoyaltyDetail	= $dblms->querylms("SELECT SUM(total_amount) as total_amount
																				FROM ".ROYALTY_SETTING_DET."
																				WHERE id_setup		= '".cleanvars($valRoyaltyCheck['id'])."'
																				AND id_particular	= '".cleanvars($particular['part_id'])."' ");
									$valueRoyaltyDetail	= mysqli_fetch_array($sqlRoyaltyDetail);
									echo'<td class="center">'.$valueRoyaltyDetail['total_amount'].'</td>';
								endforeach;
								echo'
								<td class="center">
									<input type="radio" id="royalty_status" name="royalty_status" value="'.$valRoyaltyCheck['id'].'" onclick="change_royalty_status(this.value)" '.($valRoyaltyCheck['royalty_status']==1 ? 'checked' : '').'>
								</td>
							</tr>
							';
						endwhile;
						echo'
						</tbody>
					</table>
				</div>';
			}
			else{
				echo'<h4 class="text text-danger center">No Royalty Particular Added!</h4>';
			}
			echo'
		</div>
		<!--
		<div class="panel-footer">
			<div class="row center">
				<button type="submit"  name="update_royalty_status" id="update_royalty_status" class="btn btn-primary">Update Royalty</button>
			</div>
		</div>
		-->
	</form>
</div>';
//---------------- Campus Royalty Status ----------------------
if(isset($_POST['edit_id']) && !empty($_POST['edit_id'])){
	foreach ($valRoyaltyArray as $valRoyalty):
		if($valRoyalty['id'] == $_POST['edit_id']){
			$sql1 = "royalty_status	= '1'";
		}else{
			$sql1 = "royalty_status = '0'";
		}
		$sqllmsRoyalty  = $dblms->querylms("UPDATE ".ROYALTY_SETTING." SET  
													  $sql1
													, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify	= Now()
													  WHERE id		= '".cleanvars($valRoyalty['id'])."' ");
	endforeach;
	echo '
		jQuery(document).ready(function($) {
			new PNotify({
				title	: "Successfully",
				text	: "Record Successfully Added.",
				type	: "success",
				hide	: true,
				buttons: {
					closer	: true	,
					sticker	: false
				}
			});
		}';
}

?>
<script type="text/javascript">
	function change_royalty_status(royalty_status){
		console.log(royalty_status);
		$.ajax({  
			type: "POST",
			data: "edit_id="+royalty_status,
			success: function(msg){  
            	console.log(msg)
				// $(".getroyaltytype").html(msg); 
			}
		});  
	}
</script>