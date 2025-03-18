<?php	
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '91', 'view' => '1'))){ 
	error_reporting(0);
	if(isset($_POST['id_session'])){
		$aray = explode("|", $_POST['id_session']);
		$id_session		= $aray[0];
		$session_name	= $aray[1];
	}
	if(isset($_POST['id_examtype'])){
		$aray = explode("|", $_POST['id_examtype']);
		$id_examtype	= $aray[0];
		$type_name		= $aray[1];
	}
	$class		= $_POST['id_class'];
	$section	= $_POST['id_section'];
	$month		= $_POST['month'];	
	$dated		= $_POST['dated'];

	if($view=='report'){
		$title = 'Attendance Report Panel';
	}else{
		$title = 'Attendance Panel';
	}
	
	echo'
	<title> '.$title.' | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>'.$title.' </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				// FILTERS
				if($view=='report'){
					echo'
					<section class="panel panel-featured panel-featured-primary">
						<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<header class="panel-heading">
							<h2 class="panel-title">
								<i class="fa fa-list"></i> <span class="hidden-xs">Students Attendance Report			
							</h2>
						</header>
						<div class="panel-body">
							<div class="row mb-lg">
								<div class="form-group">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Session <span class="required">*</span></label>
											<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_session" name="id_session">
												<option value="">Select</option>';
												$sqllmsSession	= $dblms->querylms("SELECT session_id, session_status, session_name 
																					FROM ".SESSIONS."
																					WHERE is_deleted = '0'
																					ORDER BY session_id ASC");
												while($valueSession = mysqli_fetch_array($sqllmsSession)) {
													echo '<option value="'.$valueSession['session_id'].'|'.$valueSession['session_name'].'" '.($valueSession['session_id'] == $id_session ? 'selected' : '').'>'.$valueSession['session_name'].'</option>';
												}
											echo '
											</select>
										</div>
									</div>									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Type <span class="required">*</span></label>
											<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype">
												<option value="">Select</option>';
												$sqlExamtype	= $dblms->querylms("SELECT type_id, type_name 
																					FROM ".EXAM_TYPES."
																					WHERE is_deleted	= '0'
																					AND type_status		= '1'
																					ORDER BY type_id ASC");
												while($valExamtype = mysqli_fetch_array($sqlExamtype)){
													echo '<option value="'.$valExamtype['type_id'].'|'.$valExamtype['type_name'].'" '.($valExamtype['type_id'] == $id_examtype ? 'selected' : '').'>'.$valExamtype['type_name'].'</option>';
												}
											echo '
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-4">
										<label class="control-label">Class </label>
										<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_class" onchange="get_classsection(this.value)">
											<option value="">Select</option>';
											$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																			FROM ".CLASSES."
																			WHERE class_status = '1' 
																			ORDER BY class_id ASC");
											while($valuecls = mysqli_fetch_array($sqllmscls)){
												echo'<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id']==$class ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
											}
											echo'
										</select>
									</div>
									<div class="col-sm-4">
										<div id="getclasssection">
											<label class="control-label">Section </label>
											<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_section" >
												<option value="">Select</option>';
												$sqllmscls = $dblms->querylms("SELECT section_id, section_name 
																				FROM ".CLASS_SECTIONS."
																				WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																				AND section_status = '1' AND id_class = '".$class."'
																				ORDER BY section_name ASC");
												while($valuecls = mysqli_fetch_array($sqllmscls)){
													echo'<option value="'.$valuecls['section_id'].'" '.($valuecls['section_id']==$section ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
												}
												echo'
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<label class="control-label">Date <span class="required">*</span></label>
										<input type="text" class="form-control valid" name="dated" id="dated" value="'.$dated.'" data-plugin-datepicker aria-required="true" required>
									</div>
								</div>
							</div>
							<div class="center">
								<button type="submit" class="btn btn-primary" id="attendance_report" name="attendance_report">
									<i class="fa fa-check-square-o"></i> Attendance Report
								</button>
							</div>
						</div>
						</form>
					</section>';
				}else{
					echo'
					<section class="panel panel-featured panel-featured-primary">
						<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<header class="panel-heading">
							<h2 class="panel-title">
								<i class="fa fa-list"></i> <span class="hidden-xs">Students Attendance Detail			
							</h2>
						</header>
						<div class="panel-body">
							<div class="row mb-lg">
								<div class="form-group">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Session <span class="required">*</span></label>
											<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_session" name="id_session">
												<option value="">Select</option>';
												$sqllmsSession	= $dblms->querylms("SELECT session_id, session_status, session_name 
																					FROM ".SESSIONS."
																					WHERE is_deleted = '0'
																					ORDER BY session_id ASC");
												while($valueSession = mysqli_fetch_array($sqllmsSession)) {
													echo '<option value="'.$valueSession['session_id'].'|'.$valueSession['session_name'].'" '.($valueSession['session_id'] == $id_session ? 'selected' : '').'>'.$valueSession['session_name'].'</option>';
												}
											echo '
											</select>
										</div>
									</div>									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">Type <span class="required">*</span></label>
											<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype">
												<option value="">Select</option>';
												$sqlExamtype	= $dblms->querylms("SELECT type_id, type_name 
																					FROM ".EXAM_TYPES."
																					WHERE is_deleted	= '0'
																					AND type_status		= '1'
																					ORDER BY type_id ASC");
												while($valExamtype = mysqli_fetch_array($sqlExamtype)){
													echo '<option value="'.$valExamtype['type_id'].'|'.$valExamtype['type_name'].'" '.($valExamtype['type_id'] == $id_examtype ? 'selected' : '').'>'.$valExamtype['type_name'].'</option>';
												}
											echo '
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-4">
										<label class="control-label">Class <span class="required">*</span></label>
										<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_classsection(this.value)">
											<option value="">Select</option>';
											$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																			FROM ".CLASSES."
																			WHERE class_status = '1' 
																			ORDER BY class_id ASC");
											while($valuecls = mysqli_fetch_array($sqllmscls)) {
												if($valuecls['class_id'] == $class){
													echo '<option value="'.$valuecls['class_id'].'" selected>'.$valuecls['class_name'].'</option>';
												}else{
													echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
												}
											}
											echo'
										</select>
									</div>
									<div class="col-sm-4">
										<div id="getclasssection">
											<label class="control-label">Section <span class="required">*</span></label>
											<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_section" required>
												<option value="">Select</option>';
												$sqllmscls = $dblms->querylms("SELECT section_id, section_name 
																				FROM ".CLASS_SECTIONS."
																				WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																				AND section_status = '1' AND id_class = '".$class."'
																				ORDER BY section_name ASC");
												while($valuecls = mysqli_fetch_array($sqllmscls)){
													if($valuecls['section_id'] == $section){
														echo '<option value="'.$valuecls['section_id'].'" selected>'.$valuecls['section_name'].'</option>';
													}else{
														echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
													}
												}
												echo'
											</select>
										</div>
									</div>
									<div class="col-sm-4">
										<label class="control-label">Month <span class="required">*</span></label>
										<select name="month" class="form-control"  data-plugin-selectTwo data-width="100%" required>
											<option>Select Month</option>';
											foreach($monthtypes as $listtype){ 
												if($month == $listtype['id']){
													echo '<option value="'.$listtype['id'].'" selected>'.$listtype['name'].'</option>';
												}else{
													echo '<option value="'.$listtype['id'].'">'.$listtype['name'].'</option>';
												}													
											}
											echo'
										</select>
									</div>
								</div>
							</div>
							<div class="center">
								<button type="submit" class="btn btn-primary" id="view_attendance" name="view_attendance">
									<i class="fa fa-check-square-o"></i> View Attendance
								</button>
							</div>
						</div>
						</form>
					</section>';
				}

				// RESULTS
				if(isset($_POST['view_attendance'])){
					echo'
					<div id="" class="" style=" overflow: auto;">
						<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
							<form action="attendance_employees.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
								<header class="panel-heading">
									<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> 
										Students Attendance Report Of <b> '.get_monthtypes($month).' </b>
									</h2>
								</header>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-condensed mb-none ">
											<thead>
												<tr>
													<th style="width:40px; text-align: center;">#</th>
													<th width="40">Photo</th>
													<th style="text-align: center;">
														Students <i class="fa fa-hand-o-down"></i> |
														Date <i class="fa fa-hand-o-right"></i>
													</th>';
													$days =  cal_days_in_month(CAL_GREGORIAN, $_POST['month'], 2019);
														for($i = 1; $i<=$days; $i++) { 
															$datearray[] = $i;
														echo '<th style="text-align: center;">'.$i.'</th>';
													}
													echo'
												</tr>
											</thead>
											<tbody>';
												$sqllms	= $dblms->querylms("SELECT  s.std_id, s.std_status, s.std_name, s.id_class,
																			s.id_section, s.id_session, s.std_rollno, s.std_regno, s.std_photo, s.id_campus 
																			FROM ".STUDENTS." s
																			WHERE s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  AND s.std_status  = '1'
																			AND s.id_class	  = '".$class."' AND s.id_section  = '".$section."'
																			AND s.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
																		");
												$srno = 0;
												while($rowsvalues = mysqli_fetch_array($sqllms)) {
													$srno++;
													echo'
													<tr>
														<td style="width:40px; text-align: center;">'.$srno.'</td>
														<td class="center"> <img src="uploads/images/students/'.$rowsvalues['std_photo'].'" width="35" height="35"</td>
														<td>
															<b>'.$rowsvalues['std_name'].'</b>
														</td>';
														foreach($datearray as $date) {
															$sqlatten = $dblms->querylms("SELECT a.id, a.dated, a.id_class, a.id_section, a.id_session, a.id_campus,
																							d.id, d.id_setup, d.id_std, d.status 
																							FROM ".STUDENT_ATTENDANCE." a
																							INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
																							WHERE a.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																							AND a.id_session	= '".cleanvars($id_session)."'
																							AND a.id_examtype	= '".cleanvars($id_examtype)."'
																							AND a.id_class		= '".cleanvars($class)."'
																							AND a.id_section	= '".cleanvars($section)."' 
																							AND MONTH(a.dated)	= '".cleanvars($month)."'
																							AND DAY(a.dated)	= '".cleanvars($date)."'
																							AND   d.id_std 	= '".$rowsvalues['std_id']."'
															");
															$rowsatten = mysqli_fetch_array($sqlatten);
															echo'<td style="text-align: center;"> '. get_attendtype($rowsatten['status']).'  </td>';							
														}
														echo'
													</tr>';
												}
												echo'				
											</tbody>
										</table>
									</div>
								</div>

								<!-- <div class="panel-footer">
									<div class="text-right">
										<a href="attendance/employees_report_print/1/b" class="btn btn-sm btn-primary " target="_blank">
										<i class="glyphicon glyphicon-print"></i> Print			</a>
									</div>
								</div> -->
							</form>
						</section>
					</div>';
				}elseif(isset($_POST['attendance_report'])){
					$sqlDated = "";
					$sql1 = "";
					$sql2 = "";
					if(!empty($dated)){
						$sqlDated = "AND a.dated = '".date('Y-m-d', strtotime($dated))."'";
					}
					if(!empty($class)){
						$sql1 = "AND a.id_class = '".$class."'";
					}
					if(!empty($section)){
						$sql2 = "AND a.id_section = '".$section."'";
					}
					if(!empty($id_session)){
						$sql3 = "AND a.id_session = '".$id_session."'";
					}
					if(!empty($id_examtype)){
						$sql4 = "AND a.id_examtype = '".$id_examtype."'";
					}
					$sqllms	= $dblms->querylms("SELECT c.class_name, cs.section_name,
                                                    COUNT(CASE WHEN ad.status = '1' THEN 1 else null end) as `present`, 
                                                    COUNT(CASE WHEN ad.status = '2' THEN 1 else null end) as `absent`, 
                                                    COUNT(CASE WHEN ad.status = '3' THEN 1 else null end) as `leave`
                                                    FROM ".STUDENT_ATTENDANCE." a
                                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." ad ON ad.id_setup = a.id
                                                    INNER JOIN ".CLASSES." c ON c.class_id = a.id_class AND c.class_status = '1' AND c.is_deleted = '0'
                                                    INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = a.id_section
                                                    WHERE a.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
													$sqlDated
													$sql1
													$sql2
													$sql3
													$sql4
													GROUP BY a.id_section
													ORDER BY a.id_class
                                                ");
					if(mysqli_num_rows($sqllms) > 0){
						echo'
						<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
							<header class="panel-heading">
								<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> 
									Students  Attendance Report <b>('.date('d M, Y', strtotime($dated)).')</b> 
								</h2>
							</header>
							<div class="panel-body">
								<div class="text-right on-screen">
									<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
									<button id="export_button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</button>
								</div>
								<div id="printResult">
									<div id="header" style="display:none;">
										<h2 style="text-align: center;">
											<img src="uploads/logo.png" class="img-fluid" width="60" height="60"> 
											<span><b>'.SCHOOL_NAME.'</b></span>
										</h2>
										<h3 style="text-align: center;"><b></b></h3>
										<h4 style="text-align: center;"><b>Students Attendance Report</b></h4>
										<table class="table" style="border-style: hidden;">
											<tr>
												<td>'.(!empty($dated) ? '<b>Date</b>: '.date('d M, Y' , strtotime($dated)).'' : '').'</td>
												<td>'.(!empty($session_name) ? '<b>Session</b>: '.$session_name.'' : '').'</td>
												<td>'.(!empty($type_name) ? '<b>Term</b>: '.$type_name.'' : '').'</td>
											</tr>
										</table>
									</div>
									<div class="table-responsive mt-md">
										<table class="table table-bordered table-striped table-condensed mb-none ">
											<thead>
												<tr>
													<th width="40" class="center">Sr.</th>
													<th class="center">Class</th>
													<th class="center">Present</th>
													<th class="center">Absent</th>
													<th class="center">Leave</th>
													<th class="center">Total Student</th>
												</tr>
											</thead>
											<tbody>';
												$srno = 0;
												$grandPresent = 0;
												$grandAbsent = 0;
												$grandLeave = 0;
												$grandTotal = 0;
												while($rowsvalues = mysqli_fetch_array($sqllms)){
													$total = $rowsvalues['present'] + $rowsvalues['absent'] + $rowsvalues['leave'];
													$srno++;													
													echo'
													<tr>
														<td class="center">'.$srno.'</td>
														<td>'.$rowsvalues['class_name'].' - '.$rowsvalues['section_name'].'</td>
														<td class="center">'.$rowsvalues['present'].'</td>
														<td class="center">'.$rowsvalues['absent'].'</td>
														<td class="center">'.$rowsvalues['leave'].'</td>
														<td class="center">'.$total.'</td>
													</tr>';	
													$grandPresent = $grandPresent + $rowsvalues['present'];
													$grandAbsent = $grandAbsent + $rowsvalues['absent'];
													$grandLeave = $grandLeave + $rowsvalues['leave'];
													$grandTotal = $grandTotal + $total;							
												}
												echo'
												<tr>
													<th class="center" colspan="2">Grand Total</th>
													<th class="center">'.number_format($grandPresent).'</th>
													<th class="center">'.number_format($grandAbsent).'</th>
													<th class="center">'.number_format($grandLeave).'</th>
													<th class="center">'.number_format($grandTotal).'</th>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</section>';
					}else{
						echo'
						<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
							<h2 class="panel-body text-center mt-none font-bold text text-danger">No Record Found</h2>
						</section';
					}
				}
				echo'
			</div>
		</div>';
}else{
	header("Location: dashboard.php");
}
?>
<script type="text/javascript">
	function get_classsection(id_class) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_classsection.php",
			data: "id_class="+id_class,  
			success: function(msg){  
				$("#getclasssection").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	// PRINT
	function print_report(printResult) {
        document.getElementById('header').style.display = 'block';
        var printContents = document.getElementById(printResult).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
		var css = '@page {   }',
		head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
		style.type = 'text/css';
		style.media = 'print';
		if (style.styleSheet){
		style.styleSheet.cssText = css;
		} else {
		style.appendChild(document.createTextNode(css));
		}
		head.appendChild(style);
        window.print();
        document.body.innerHTML = originalContents;
		document.getElementById('header').style.display = 'none';
    }
	// EXPORT TO EXCEL
	function html_table_to_excel(type){
		var data = document.getElementById('printResult');
		var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
		XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
		XLSX.writeFile(file, 'report.' + type);
	}

	const export_button = document.getElementById('export_button');
	export_button.addEventListener('click', () =>  {
		html_table_to_excel('xlsx');
	});
</script>