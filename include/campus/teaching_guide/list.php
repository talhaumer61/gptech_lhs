<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '64', 'view' => '1'))){ 
//---------------------------------------
if(isset($_POST['id_class'])){$class = $_POST['id_class'];}else{$class = "";}
if(isset($_POST['id_subject'])){$subject = $_POST['id_subject'];}else{$subject = "";}
if(isset($_POST['syllabus_term'])){$term = $_POST['syllabus_term'];}else{$term = "";}
//---------------------------------------
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="teaching_guide.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
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
					<label class="control-label">Term </label>
					<select class="form-control"  data-plugin-selectTwo data-width="100%" name="syllabus_term">
						<option value="">Select</option>
						<option value="1"'; if($term == '1'){ echo'selected';} echo'>First Term</option>
						<option value="2"'; if($term == '2'){ echo'selected';} echo'>Second Term</option>
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
	<h2 class="panel-title"><i class="fa fa-list"></i>  Teaching Guides List</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th style="text-align:center;">#</th>
		<th>Term</th>
		<th>Title</th>
		<th>Class</th>
		<th>Subject</th>
		<th>Note</th>
		<th width="100" style="text-align:center;">Options</th>
	</tr>
</thead>
<tbody>';
//------------------------------------------
if(isset($_POST['show_result'])){
	//if Sub
	if($subject){$sqlSub = "AND s.id_subject=".$subject."";}else{$sqlSub = "";}
	//if Term
	if($term){$sqlTerm = "AND s.syllabus_term=".$term."";}else{$sqlTerm = "";}
	//----------
	$sql2 = " AND s.id_class = ".$class." ".$sqlSub." ".$sqlTerm." ";
}
else{
	$sql2 = "";
}
//------------------------------------------
$sqllms	= $dblms->querylms("SELECT s.guide_id, s.guide_status, s.guide_title, s.guide_term,
								   s.id_session, s.guide_file, s.id_class, s.id_subject, s.note,
								   se.session_id, se.session_status, se.session_name,
								   c.class_id, c.class_status, c.class_name,
								   cs.subject_id, cs.subject_status, cs.subject_name
								   FROM ".TEACHING_GUIDES." s 
								   INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
								   INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
								   INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
								   WHERE s.guide_status = '1' AND s.is_deleted != '1' $sql2
								   AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
								   ORDER BY s.guide_id DESC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
if($rowsvalues['guide_term'] == 1){
	$term = 'First';
}
elseif($rowsvalues['guide_term'] == 2){
	$term = 'Second';
}
$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td style="text-align:center;">'.$srno.'</td>
	<td>'.$term.'</td>
	<td>'.$rowsvalues['guide_title'].'</td>
	<td>'.$rowsvalues['class_name'].'</td>
	<td>'.$rowsvalues['subject_name'].'</td>
	<td>'.$rowsvalues['note'].'</td>
	<td  width="70" class="center">
		<a href="uploads/teaching_guides/'.$rowsvalues['guide_file'].'" download="'.$rowsvalues['session_name'].'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
		<a href="uploads/teaching_guides/'.$rowsvalues['guide_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>
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