<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)){ 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)){ 
				echo'
				<a href="#make_download" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
				<i class="fa fa-plus-square"></i> Make Download
				</a>';
			}
			echo '
			<h2 class="panel-title"><i class="fa fa-list"></i>  Download List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="50" class="center">Sr#</th>
						<th width="120" class="center">Type</th>
						<th>Title</th>
						<th>Description</th>
						<th width="70px;" class="center">Status</th>
						<th width="120" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT *
													FROM ".ADMINISTRATION_DOWNLOAD."  
													WHERE is_deleted = '0'
													ORDER BY id_type ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td class="center">'.get_downloadTypes($rowsvalues['id_type']).'</td>
							<td>'.$rowsvalues['title'].'</td>
							<td>'.$rowsvalues['description'].'</td>
							<td class="center">'.get_status($rowsvalues['status']).'</td>
							<td class="center">
								';
								if($rowsvalues['id_type'] == '3'){
									echo '
										<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/administration_downloads/view.php?youtube_code='.$rowsvalues['youtube_code'].'&title='.$rowsvalues['title'].' \');"><i class="glyphicon glyphicon-eye-open"></i></a>
										<a href="https://www.SSyoutube.com/watch?v='.$rowsvalues['youtube_code'].'" target="_blank" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-download"></i> </a>
									';
								}else{
									echo '
										<a href="uploads/administration_downloads/'.$rowsvalues['file'].'" class="btn btn-info btn-xs" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>
										<a href="uploads/administration_downloads/'.$rowsvalues['file'].'" download="'.$rowsvalues['title'].'" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-download"></i> </a>
									';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)){ 
								echo '
									<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/administration_downloads/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)){ 
								echo '
									<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'administration_downloads.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo '
							</td>
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