<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)){
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Montessori List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="50" class="center">Sr#</th>
						<th width="120" class="center">Type</th>
						<th width="120" class="center">Date</th>
						<th>Title</th>
						<th>Resource Person</th>
						<th width="120" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("
													SELECT 
														  id_type
														, mont_date
														, mont_title
														, mont_resource_person
														, mont_youtube_code
													FROM ".MONTESSORI."  
													WHERE 
															is_deleted  = '0'
														AND
															mont_status = '1'
													ORDER BY mont_id DESC
												");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td class="center">'.get_MontessoriTypes($rowsvalues['id_type']).'</td>
							<td class="center">'.date("d/m/Y",strtotime($rowsvalues['mont_date'])).'</td>
							<td>'.$rowsvalues['mont_title'].'</td>
							<td>'.$rowsvalues['mont_resource_person'].'</td>
							<td class="center">
                                <a href="#show_modal" class="modal-with-move-anim-pvs btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/montessori/view.php?youtube_code='.$rowsvalues['mont_youtube_code'].'&title='.$rowsvalues['mont_title'].' \');"><i class="glyphicon glyphicon-eye-open"></i></a>
                                <a href="https://www.SSyoutube.com/watch?v='.$rowsvalues['mont_youtube_code'].'" target="_blank" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-download"></i> </a>
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