<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'view' => '1'))){ 

	// Vars
	$class		= "";
	$subject	= "";
	$no			= "";
	$sqlClass 	= "";
	$sqlSub 	= "";
	$sqlMonth 	= "";
	$sqlAssess 	= "";

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
	}
	// Query For Pages
	$sql = "SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session,s.syllabus_file, s.id_month, s.id_week, s.id_class, s.id_subject, s.note, se.session_name, c.class_name, cs.subject_name
				FROM ".SYLLABUS." s 
				INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
				INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
				INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
				WHERE s.syllabus_status = '1' 
				AND s.is_deleted 	= '0' 
				AND s.syllabus_type = '4' 
				$sqlClass 
				$sqlSub 
				$sqlAssess  
				AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
				ORDER BY s.syllabus_id DESC";
	// Pages
	$sqllms	= $dblms->querylms($sql);									
	$count = mysqli_num_rows($sqllms);

	if($page == 0 || empty($page)) { $page = 1; }		//if no page var is given, default to 1.
	$prev 		    = $page - 1;						//previous page is page - 1
	$next 		    = $page + 1;						//next page is page + 1
	$lastpage  		= ceil($count/$Limit);				//lastpage is = total pages / items per page, rounded up.
	$lpm1 		    = $lastpage - 1;

	// Query
	$sqllmsAses	= $dblms->querylms("$sql LIMIT ".($page-1)*$Limit .",$Limit");
	// AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Monthly Assessment List</h2>
		</header>
		<div class="panel-body">
			<form action="#" method="GET" autocomplete="off">
				<div class="row">
					<div class="form-group">
						<div class="col-md-4">
							<label class="control-label">Class </label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" onchange="get_filteredsubjects(this.value)">
								<option value="">Select</option>
								<option value="" selected>All</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																	FROM ".CLASSES." 
																	WHERE class_status = '1'
																	AND is_deleted = '0'
																	ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'" '.($class == $valuecls['class_id']? 'selected': '').'>'.$valuecls['class_name'].'</option>';
								}
								echo '
							</select>
						</div>
						<div class="col-md-4">
							<label class="control-label">Subject </label>
							<div id="getfilteredsubjects">
								<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_subject">
									<option value="">Select</option>';
									$sqllmsSub	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
																		FROM ".CLASS_SUBJECTS."
																		WHERE subject_status = '1' 
																		AND is_deleted = '0'
																		ORDER BY subject_name ASC");
									while($valueSub = mysqli_fetch_array($sqllmsSub)) {
										echo '<option value="'.$valueSub['subject_id'].'" '.($subject == $valueSub['subject_id']? 'selected': '').'>'.$valueSub['subject_code'].' - '.$valueSub['subject_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="control-label">Assessment </label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" name="no">
								<option value="">Select</option>';
								foreach (get_AssessmentType() as $key => $value) {
									echo '<option value="'.$key.'" '.($no == $key ? 'selected': '').'>'.$value.'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="form-group mb-md">
						<center>
							<button type="submit" name="find" class="btn btn-primary" style="width: 90px;"><i class="fa fa-search"></i> Search</button>
						</center>
					</div>
				</div>
			</form>';
			if(mysqli_num_rows($sqllmsAses) > 0) {
				echo'
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th class="center" width="70">#</th>
							<th>Class</th>
							<th>Subject</th>
							<th>Assessment No</th>
							<th>Note</th>
							<th width="100" class="center">Options</th>
						</tr>
					</thead>
					<tbody>';
						// if(isset($_POST['show_result'])){
						// 	//if Sub
						// 	if($subject){$sqlSub = "AND s.id_subject=".$subject."";}else{$sqlSub = "";}
						// 	//if Month
						// 	if($month_id){$sqlMonth = "AND s.id_month=".$month_id."";}else{$sqlMonth = "";}
						// 	//----------
						// 	$sql2 = " AND s.id_class = ".$class." ".$sqlSub." ".$sqlMonth." ";
						// } else {
						// 	$sql2 = "";

						$srno = 0;
						$srno = ($page == 1)? 0: ($page - 1) * $Limit; 
						while($rowsvalues = mysqli_fetch_array($sqllmsAses)) {
							$srno++;
							echo '
							<tr>
								<td class="center">'.$srno.'</td>
								<td>'.$rowsvalues['class_name'].'</td>
								<td>'.$rowsvalues['subject_name'].'</td>
								<td>Assessment: '.$rowsvalues['id_month'].'</td>
								<td>'.$rowsvalues['note'].'</td>
								<td class="center">
									<a href="uploads/monthly_assessments/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.get_monthtypes($rowsvalues['id_month']).'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
									<a href="uploads/monthly_assessments/'.$rowsvalues['syllabus_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>';
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