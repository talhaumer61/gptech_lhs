<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'view' => '1'))){ 
//---------------------------------------
if(isset($_POST['id_class'])){$class = $_POST['id_class'];}else{$class = "";}
if(isset($_POST['id_subject'])){$subject = $_POST['id_subject'];}else{$subject = "";}
if(isset($_POST['id_month'])){$month_id = $_POST['id_month'];}else{$month_id = "";}
//---------------------------------------
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="syllabus_worksheet.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
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
					<label class="control-label">Month </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_month">
						<option value="">Select</option>';
						foreach($monthtypes as $month){
							echo'<option value="'.$month['id'].'"'; if($month_id == $month['id']){ echo'selected';} echo'>'.$month['name'].'</option>';
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
	<h2 class="panel-title"><i class="fa fa-list"></i>  Work Sheet List</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th style="text-align:center;">#</th>
		<th>Session</th>
		<th>Term</th>
		<th>Subject</th>
		<th>Class</th>
		<th>Month</th>
		<th>Note</th>
		<th width="100" style="text-align:center;">Options</th>
	</tr>
</thead>
<tbody>';
//------------------------------------------
if(isset($_POST['show_result'])){
	//if Sub
	if($subject){$sqlSub = "AND s.id_subject=".$subject."";}else{$sqlSub = "";}
	//if Month
	if($month_id){$sqlMonth = "AND s.id_month=".$month_id."";}else{$sqlMonth = "";}
	//----------
	$sql2 = " AND s.id_class = ".$class." ".$sqlSub." ".$sqlMonth." ";
}
else{
	$sql2 = "";
}
//------------------------------------------
$sqllms	= $dblms->querylms("SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session,
								   s.syllabus_file, s.id_month, s.id_week, s.id_class, s.id_subject, s.note,
								   se.session_id, se.session_status, se.session_name,
								   c.class_id, c.class_status, c.class_name,
								   cs.subject_id, cs.subject_status, cs.subject_name
								   FROM ".SYLLABUS." s 
								   INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
								   INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
								   INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
								   WHERE s.syllabus_status = '1' AND s.is_deleted != '1' 
								   AND s.syllabus_type = '3' $sql2
								   AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
								   ORDER BY s.syllabus_id DESC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
if($rowsvalues['syllabus_term'] == 1){
	$term = 'First';
}
elseif($rowsvalues['syllabus_term'] == 2){
	$term = 'Second';
}
$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td style="text-align:center;">'.$srno.'</td>
	<td>'.$rowsvalues['session_name'].'</td>
	<td>'.$term.'</td>
	<td>'.$rowsvalues['subject_name'].'</td>
	<td>'.$rowsvalues['class_name'].'</td>
	<td>'.get_monthtypes($rowsvalues['id_month']).'</td>
	<td>'.$rowsvalues['note'].'</td>
	<td style="text-align:center;">
		<a href="uploads/worksheet/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.get_monthtypes($rowsvalues['id_month']).'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i></a>
		<a href="uploads/worksheet/'.$rowsvalues['syllabus_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>
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