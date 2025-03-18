<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1)){
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1)){
				echo'
				<a href="#make_setting" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
					<i class="fa fa-plus-square"></i> Make New Settings
				</a>';
				}
				echo'
			<h2 class="panel-title"><i class="fa fa-cogs"></i> Settings</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th>Area Zone</th>
						<th>Admission Session</th>
						<th>Academic Session</th>
						<th>Exam Session</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT adm_session, acd_session, exam_session, id_zone
												FROM ".SETTINGS." s
												WHERE status ='1' AND is_deleted = '0'");
					$sql = "SELECT session_name FROM ".SESSIONS." s WHERE ";
					while($rowsvalues = mysqli_fetch_array($sqllms)):
						echo'
						<tr>
							<td>'.get_AreaZone($rowsvalues['id_zone']).'</td>
							<td>'.mysqli_fetch_array($dblms->querylms("$sql '".$rowsvalues['adm_session']."' = s.session_id"))['session_name'].'</td>
							<td>'.mysqli_fetch_array($dblms->querylms("$sql '".$rowsvalues['acd_session']."' = s.session_id"))['session_name'].'</td>
							<td>'.mysqli_fetch_array($dblms->querylms("$sql '".$rowsvalues['exam_session']."' = s.session_id"))['session_name'].'</td>
						</tr>';
					endwhile;
					echo'
				</tbody>
			</table>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>