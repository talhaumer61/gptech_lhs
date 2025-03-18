<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'view' => '1'))){ 

	// Vars
	$class		= "";
	$subject	= "";
	$no			= "";
	$type		= "";
	$sqlClass 	= "";
	$sqlSub 	= "";
	$sqlMonth 	= "";
	$sqlAssess 	= "";
	$sqlType 	= "";

	if(isset($_GET['find']))
	{
		//  class
		if($_GET['id_class']) {
			$class = $_GET['id_class'];
			$sqlClass = "AND s.id_class = '".$class."'";
		} else {
			$sqlClass = "";
		}
		// status
		if($_GET['id_subject']) {
			$subject = $_GET['id_subject'];
			$sqlSub = "AND s.id_subject = '".$subject."' ";
		} else {
			$sqlSub = "";
		}
		// search Words
		if($_GET['no']) {
			$no = $_GET['no'];
			$sqlAssess = "AND s.id_month = '".$no."' ";
		} else {
			$sqlAssess = "";
		}
		if($_GET['syllabus_term']) {
			$term = $_GET['syllabus_term'];
			$sqlTerm = "AND s.syllabus_term = '".$term."' ";
		} else {
			$sqlTerm = "";
		}
	}

	// Query For Pages
	$sql = "SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session, s.syllabus_file, s.id_month, s.id_week, s.id_class, s.id_subject, s.note, se.session_name, c.class_name, cs.subject_name
				FROM ".SYLLABUS." s 
				INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
				INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
				INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
				WHERE s.syllabus_status = '1' AND s.is_deleted != '1' 
				AND s.syllabus_type = '4' 
				$sqlClass 
				$sqlSub 
				$sqlAssess  
				$sqlTerm
				ORDER BY s.syllabus_id DESC";

	// Pages
	$sqllms	= $dblms->querylms($sql);									
	$count = mysqli_num_rows($sqllms);

	if($page == 0 || empty($page)) { $page = 1; }						//if no page var is given, default to 1.
	$prev 		    = $page - 1;						//previous page is page - 1
	$next 		    = $page + 1;						//next page is page + 1
	$lastpage  		= ceil($count/$Limit);				//lastpage is = total pages / items per page, rounded up.
	$lpm1 		    = $lastpage - 1;
	// Query
	$sqllmsAses	= $dblms->querylms("$sql LIMIT ".($page-1)*$Limit .",$Limit");
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'add' => '1'))){ 
				echo'
				<a href="#make_assessment" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
				<i class="fa fa-plus-square"></i> Make Monthly Assessment
				</a>';
			}
			echo '
			<h2 class="panel-title"><i class="fa fa-list"></i> Monthly Assessment List</h2>
		</header>
		<div class="panel-body">
			<form action="#" method="GET" autocomplete="off">
				<div class="form-group mb-lg">
						<div class="col-md-3">
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
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label">Exam Term</label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" name="syllabus_term" name="syllabus_term" >
									<option value="">Select</option>';
									$sqlTerms	= $dblms->querylms("SELECT term_id, term_status, term_name 
																	FROM ".EXAM_TERMS."
																	WHERE term_status = '1'
																	ORDER BY term_id ASC");
									while($valTerms = mysqli_fetch_array($sqlTerms)) {
										echo '<option value="'.$valTerms['term_id'].'" '.($valTerms['term_id'] == $term ? 'selected' : '').'>'.$valTerms['term_name'].'</option>';
									}
									echo '
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<label class="control-label">Subject </label>
							<div id="getfilteredsubjects">
							<select class="form-control" data-plugin-selectTwo data-width="100%"  name="id_subject">
								<option value="">Select</option>';
								$sqllmsSub	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
																	FROM ".CLASS_SUBJECTS."
																	WHERE subject_status = '1' 
																	AND is_deleted = '0'
																	ORDER BY subject_name ASC");
								while($valueSub = mysqli_fetch_array($sqllmsSub)) {
									echo '<option value="'.$valueSub['subject_id'].'" '.($subject == $valueSub['subject_id']? 'selected': '').' >'.$valueSub['subject_code'].' - '.$valueSub['subject_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<label class="control-label">Assessment </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="no">
							<option value="">Select</option>';
							foreach(get_AssessmentType() as $key => $val):
								echo '<option value="'.$key.'" '.($no == $key? 'selected': '').' >'.$val.'</option>';
							endforeach;
							echo'
						</select>
					</div>
					<div class="col-sm-1">
						<div class="form-group mt-xl">
							<button type="submit" name="find" class="btn btn-primary" style="width: 90px;"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			</form>';
			if(mysqli_num_rows($sqllmsAses) > 0) {
				echo'
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th width="20" style="text-align:center;">Sr.</th>
							<th width="80">Session</th>
							<th>Class</th>
							<th>Subject</th>
							<th>Note</th>
							<th width="100">Assessment</th>
							<th style="text-align:center;">Status</th>
							<th width="100" style="text-align:center;">Options</th>
						</tr>
					</thead>
					<tbody>';
						$srno = 0;
						$srno = ($page == 1)? 0: ($page - 1) * $Limit; 
						while($rowsvalues = mysqli_fetch_array($sqllmsAses)) {
							$srno++;
							echo '
							<tr>
								<td style="text-align:center;">'.$srno.'</td>
								<td class="center">'.$rowsvalues['session_name'].'</td>
								<td>'.$rowsvalues['class_name'].'</td>
								<td>'.$rowsvalues['subject_name'].'</td>
								<td>'.$rowsvalues['note'].'</td>
								<td class="center">'.get_AssessmentType($rowsvalues['id_month']).'</td>
								<td style="text-align:center; width: 80px;">'.get_status($rowsvalues['syllabus_status']).'</td>
								<td  width="140" class="center">
									<a href="uploads/monthly_assessments/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.get_monthtypes($rowsvalues['id_month']).'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-download"></i> </a>
									<a href="uploads/monthly_assessments/'.$rowsvalues['syllabus_file'].'" class="btn btn-info btn-xs" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>';
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'edit' => '1'))){ 
										echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/monthly-assessment/modal_assessment_update.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
									}
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'delete' => '1'))){ 
										echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'monthly_assessment.php?deleteid='.$rowsvalues['syllabus_id'].'\');"><i class="el el-trash"></i></a>';
									}
									echo'
								</td>
							</tr>';
						}
						echo '
					</tbody>
				</table>';
			} else {
				echo'
				<section class="text text-danger text-center">
					<h3>No Record Found</h3>
				</section>';
			}
			// Filters For Pagination
			$filters = 'id_class='.$class.'&_id_subject='.$subject.'&no='.$no.'';
			// Pagination
			include_once('include/pagination.php');
			echo'
		</div>
	</section>';
} else {
	header("Location: dashboard.php");
}
?>
<script type="text/javascript">
	function get_filteredsubjects(id_class) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_filter_classsubject.php",  
			data: "id_class="+id_class,  
			success: function(msg){  
				$("#getfilteredsubjects").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
</script>