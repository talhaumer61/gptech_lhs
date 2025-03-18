<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '59', 'view' => '1'))){ 
//---------------------------------------
if(isset($_POST['id_class'])){$class = $_POST['id_class'];}else{$class = "";}
if(isset($_POST['id_subject'])){$subject = $_POST['id_subject'];}else{$subject = "";}
if(isset($_POST['id_week'])){$week_id = $_POST['id_week'];}else{$week_id = "";}
//---------------------------------------
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="syllabus_dlp.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select Options</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mb-md">
				<div class="col-md-4">
					<label class="control-label">Class <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_filteredsubjects(this.value)">
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
												FROM ".CLASSES." 
												WHERE class_status = '1'
												ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'"'; if($class == $valuecls['class_id']){ echo'selected';} echo'>'.$valuecls['class_name'].'</option>';
							}
					echo '
					</select>
				</div>
				<div class="col-md-4">
					<label class="control-label">Subject </label>
					<div id="getfilteredsubjects">
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_subject" >
							<option value="">Select</option>';
							$sqllmsSub	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
													FROM ".CLASS_SUBJECTS."
													WHERE subject_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
													ORDER BY subject_name ASC");
							while($valueSub = mysqli_fetch_array($sqllmsSub)) {
								echo '<option value="'.$valueSub['subject_id'].'"'; if($subject == $valueSub['subject_id']){ echo'selected';} echo'>'.$valueSub['subject_code'].' - '.$valueSub['subject_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<label class="control-label">Week </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_week">
						<option value="">Select</option>';
						for($i=1; $i<=15; $i++) {
							echo'<option value="'.$i.'"'; if($week_id == $i){ echo'selected';} echo'>Week '.$i.'</option>';
						}
						echo'						
					</select>
				</div>
			</div>
			<div class="col-md-12 text-center">
				<button type="submit" id="show_result" name="show_result" class="mr-xs btn btn-primary">Show Syllabus</button>
			</div>
		</div>
	</form>
</section>

<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">
	<h2 class="panel-title"><i class="fa fa-list"></i>  DLP List</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th class="center" width="70">#</th>
		<th>Session</th>
		<th>Term</th>
		<th>Subject</th>
		<th>Class</th>
		<th>Week</th>
		<th>Note</th>
		<th width="100" class="center">Options</th>
	</tr>
</thead>
<tbody>';
//------------------------------------------
if(isset($_POST['show_result'])){
	//if Sub
	if($subject){$sqlSub = "AND s.id_subject=".$subject."";}else{$sqlSub = "";}
	//if Week
	if($week_id){$sqlWeek = "AND s.id_week=".$week_id."";}else{$sqlWeek = "";}
	// Query
	$sql2 = " AND s.id_class = ".$class." ".$sqlSub." ".$sqlWeek." ";
} else {
	$sql2 = "";
}

//------------------------------------------
$sqllms	= $dblms->querylms("SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session,
								   s.syllabus_file, s.id_month, s.id_week, s.id_class, s.id_subject, s.note,
								   se.session_name, c.class_name, cs.subject_name
								   FROM ".SYLLABUS." s 
								   INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
								   INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
								   INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
								   WHERE s.syllabus_status = '1' AND s.is_deleted != '1'
								   AND s.syllabus_type = '2' $sql2
								   AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
								   ORDER BY s.syllabus_id DESC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
if($rowsvalues['syllabus_term'] == 1){
	$term = 'First Term';
}
elseif($rowsvalues['syllabus_term'] == 2){
	$term = 'Second Term';
}
$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td class="center">'.$srno.'</td>
	<td>'.$rowsvalues['session_name'].'</td>
	<td>'.$term.'</td>
	<td>'.$rowsvalues['subject_name'].'</td>
	<td>'.$rowsvalues['class_name'].'</td>
	<td>Week '.$rowsvalues['id_week'].'</td>
	<td>'.$rowsvalues['note'].'</td>
	<td class="center">
		<a href="uploads/dlp/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.get_monthtypes($rowsvalues['id_month']).'-'.get_week($rowsvalues['id_week']).'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i></a>
		<a href="uploads/dlp/'.$rowsvalues['syllabus_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>
	</td>
</tr>';
//-----------------------------------------------------
}
//-----------------------------------------------------
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