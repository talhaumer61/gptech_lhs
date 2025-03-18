<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'view' => '1'))){ 
	$session = $_SESSION['userlogininfo']['ACADEMICSESSION'];
	$type = "";
	$class = "";
	$sqlSession = "AND s.id_session = ".$session."";
	$sqlTyepe = "";
	$sqlClass = "";
	if (isset($_GET['find'])):
		if (!empty($_GET['id_session'])):
			$session = cleanvars($_GET['id_session']);
			$sqlSession = "AND s.id_session = $session";
		endif;
		if (!empty($_GET['id_type'])):
			$type = cleanvars($_GET['id_type']);
			$sqlTyepe = "AND s.id_type = $type";
		endif;
		if (!empty($_GET['id_class'])):
			$class = cleanvars($_GET['id_class']);
			$sqlClass = "AND s.id_class = $class";
		endif;
	endif;
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
		if (($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'add' => '1'))):
			echo'
			<a href="#make_scheme" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
				<i class="fa fa-plus-square"></i> Make Assessment Scheme
			</a>';
		endif;
		echo '
			<h2 class="panel-title"><i class="fa fa-list"></i>  Assessment Schemes List</h2>
		</header>
		<div class="panel-body">
			<form action="#" method="GET" autocomplete="off">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Session</label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_session" name="id_session" >
								<option value="">Select</option>';
								$sqllmsSession	= $dblms->querylms("SELECT session_id, session_name 
																		FROM ".SESSIONS." 
																		WHERE session_status = '1' 
																		ORDER BY session_id DESC");
								while($value_class 	= mysqli_fetch_array($sqllmsSession)) {
								echo '<option value="'.$value_class['session_id'].'" '.($value_class['session_id'] == $session ? 'selected' : '').'>'.$value_class['session_name'].'</option>';
								}
								echo '
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Exam Term</label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_type" name="id_type" >
								<option value="">Select</option>';
								$sqllmsExamType	= $dblms->querylms("SELECT type_id, type_name 
																		FROM ".EXAM_TYPES." 
																		WHERE type_status = '1' ORDER BY type_id ASC");
								while($value_class 	= mysqli_fetch_array($sqllmsExamType)) {
								echo '<option value="'.$value_class['type_id'].'" '.($value_class['type_id'] == $type ? 'selected' : '').'>'.$value_class['type_name'].'</option>';
								}
								echo '
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label">Class </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" onchange="get_filteredsubjects(this.value)">
							<option value="">Select</option>
							<option value="" selected>All</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
													FROM ".CLASSES." 
													WHERE class_status = '1'
													AND is_deleted != '1'
													ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'" '.($class == $valuecls['class_id']? 'selected': '').' >'.$valuecls['class_name'].'</option>';
								}
								echo '
						</select>
					</div>
					<div class="col-sm-12">
						<div class="form-group mt-xl">
							<center>
								<button type="submit" name="find" class="btn btn-primary" style="width: 90px;"><i class="fa fa-search"></i></button>
							</center>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>';
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Assessment Schemes List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="20">Sr.</th>
						<th class="center" width="80">Session</th>
						<th>Class</th>
						<th>Exam Type</th>
						<th>Subject</th>
						<th>Note</th>
						<th width="70px" class="center">Status</th>
						<th width="130" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT s.id , s.status, s.id_type, s.id_exam, s.file, s.id_month, s.id_sbuject_cat, s.note, se.session_name, c.class_name
												FROM ".EXAM_DOWNLOADS." s 
												INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
												LEFT JOIN ".CLASSES." c ON c.class_id = s.id_class
												WHERE s.is_deleted = '0' 
												$sqlSession
												$sqlTyepe
												$sqlClass
												ORDER BY s.id DESC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.get_scheme_type($rowsvalues['id_exam']).'</td>
							<td>'.get_subjectcat($rowsvalues['id_sbuject_cat']).'</td>
							<td>'.$rowsvalues['note'].'</td>
							<td class="center">'.get_status($rowsvalues['status']).'</td>
							<td class="center" width="120px;">
							<a href="uploads/assessment_downloads/'.$rowsvalues['file'].'" download="'.$rowsvalues['session_name'].'-'.get_assessment($rowsvalues['id_type']).'-'.$rowsvalues['type_name'].'-'.get_subjectcat($rowsvalues['id_sbuject_cat']).'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
							<a href="uploads/assessment_downloads/'.$rowsvalues['file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>';

							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'edit' => '1'))){ 
							echo '
								<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/exam_scheme/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '81', 'delete' => '1'))){ 
							echo'
								<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'summer-work.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
							}
							echo'
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