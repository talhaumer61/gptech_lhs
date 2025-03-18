<?php
echo'
<div id="social_links" class="tab-pane ">
	<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
		<header class="panel-heading">
			<a href="#add" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Soical Link</a>
			<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> List Social Links </h2>
		</header>';
		$sqllms	= $dblms->querylms("SELECT id, status, id_social, link
										FROM ".SOCIAL_LINKS." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND is_deleted	= '0'
										ORDER BY id ASC
									");
		if(mysqli_num_rows($sqllms)>0){
			echo'
			<div class="panel-body">
				<div class="table-responsive mt-sm mb-md">
					<table class="table table-bordered table-striped table-condensed mb-none table_export">
						<thead>
							<tr>
								<th width="30" class="center">Sr.</th>
								<th>Title</th>
								<th class="center">Link</th>
								<th width="70" class="center">Status</th>
								<th width="70" class="center">Options</th>
							</tr>
						</thead>
						<tbody>';
							$srno = 0;
							while($rowsvalues = mysqli_fetch_array($sqllms)) {
								$srno++;
								echo '
								<tr>
									<td class="center">'.$srno.'</td>
									<td >'.get_socialtype($rowsvalues['id_social']).'</td>
									<td>'.$rowsvalues['link'].'</td>
									<td class="center">'.get_status($rowsvalues['status']).'</td>
									<td class="center">
										<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/social_links/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>
										<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'profile.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>
									</td>
								</tr>';
							}
							echo '
						</tbody>
					</table>
				</div>
			</div>';
		}else{
			echo'<h2 class="panel-body text-center font-bold mt-none text text-danger">No Record Found</h2>';
		}
		echo'
	</section>
</div>';
?>