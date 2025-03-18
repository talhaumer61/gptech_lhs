<?php	
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '55', 'view' => '1'))){ 
	error_reporting(0);
	$month		=	$_POST['month'];
	$dept 		=	$_POST['dept'];	
	$date_from 	=	$_POST['date_from'];
	$date_to   	=	$_POST['date_to'];
	echo'
	<title> Attendance Panel | '.TITLE_HEADER.'</title>

	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Attendance Panel </h2>
		</header>
		<!-- INCLUDEING PAGE -->
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<header class="panel-heading">
							<h2 class="panel-title">
								<i class="fa fa-list"></i> <span class="hidden-xs">Employees Attendance	Report		
							</h2>
						</header>
						<div class="panel-body">
							<div class="row mb-lg">
								<div class="col-md-4">
									<div class="input-group"> 
										<label class="control-label">
											Department <span class="required">*</span>
										</label>
										<select name="dept" class="form-control"  data-plugin-selectTwo data-width="100%" required>
											<option value="">Select</option>';
											$sqllmscls	= $dblms->querylms("SELECT dept_id, dept_name 
																			FROM ".DEPARTMENTS."
																			WHERE is_deleted != '1'
																			ORDER BY dept_name ASC");
											while($valuecls = mysqli_fetch_array($sqllmscls)) {
												if($valuecls['dept_id'] == $dept) { 
													echo '<option value="'.$valuecls['dept_id'].'" selected>'.$valuecls['dept_name'].'</option>';
												} else { 
													echo '<option value="'.$valuecls['dept_id'].'">'.$valuecls['dept_name'].'</option>';
												}
											}
											echo '
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="input-group"> 
										<label class="control-label">Month </label>
										<select name="month" class="form-control"  data-plugin-selectTwo data-width="100%">
											<option>Select Month</option>';
												foreach($monthtypes as $listtype) 
												{ 
													echo '<option value="'.$listtype['id'].'" '.($month == $listtype['id'] ? 'selected' : '').'>'.$listtype['name'].'</option>';
													
												}
											echo'
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="input-group"> 
										<label class="control-label">Duration </label>
										<div class="input-daterange input-group" data-plugin-datepicker><span class="input-group-addon text-danger"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control valid" name="date_from" id="date_from" value="'.$date_from.'" aria-required="true" aria-invalid="false">
											<span class="input-group-addon">To</span>
											<input type="text" class="form-control" name="date_to" id="date_to" value="'.$date_to.'" aria-required="true">
										</div>
									</div>
								</div>
							</div>
							<div class="center">
								<button type="submit" class="btn btn-primary" id="view_attendance" name="view_attendance">
									<i class="fa fa-check-square-o"></i> View Attendance
								</button>
								<button type="submit" class="btn btn-primary" id="attendance_report" name="attendance_report">
									<i class="fa fa-check-square-o"></i> Attendance Report
								</button>
							</div>
						</div>
					</form>
				</section>';
				if(isset($_POST['view_attendance'])){
					echo'
					<div id="" class="" style=" overflow: auto;">
						<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
							<form action="attendance_employees.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
								<header class="panel-heading">
									<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> 
										Employees  Attendance Report Of <b>'.get_monthtypes($month).'</b> 
									</h2>
								</header>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-condensed mb-none ">
											<thead>
												<tr>
													<th style="width:40px; text-align: center;">Sr#</th>
													<th style="text-align: center;">Photo</th>
													<th> Employees <i class="fa fa-hand-o-down"></i> | Date <i class="fa fa-hand-o-right"></i>
													</th>';
													$days =  cal_days_in_month(CAL_GREGORIAN, $_POST['month'], date('Y'));
														for($i = 1; $i<=$days; $i++) { 
															$datearray[] = $i;
														echo '<th style="text-align: center;">'.$i.'</th>';
													}
													echo'
												</tr>
											</thead>
											<tbody>';
											
											$sqllms	= $dblms->querylms("SELECT e.emply_id, e.emply_status, e.emply_photo, e.emply_name, e.id_designation,
																		e.emply_email, e.id_campus,
																		d.designation_id, d.designation_status, d.designation_name
																		FROM ".EMPLOYEES." e
																		INNER JOIN ".DESIGNATIONS." d ON d.designation_id = e.id_designation
																		WHERE e.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
																		AND  e.id_dept = '".$dept."' AND e.emply_status = '1' AND e.is_deleted != '1' ");
											$srno = 0;
											
											while($rowsvalues = mysqli_fetch_array($sqllms)) {
												
												$srno++;
											
												echo'
												<tr>
													<td style="width:40px; text-align: center;">'.$srno.'</td>
													<td class="center"><img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" width="35" height="35"></td>
													<td>
														<b>'.$rowsvalues['emply_name'].'</b>
														<span class="ml-sm label label-primary"> '.$rowsvalues['designation_name'].'</span>
													</td>';
													
													foreach($datearray as $date) {
														
														$sqlatten	= $dblms->querylms("SELECT *
																						FROM ".EMPLOYEES_ATTENDCE." a
																						INNER JOIN ".EMPLOYEES_ATTENDCE_DETAIL." d ON d.id_setup = a.id
																						WHERE a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																						AND a.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
																						AND MONTH(a.dated) = '".$month."' AND DAY(a.dated) = '".$date."'
																						AND d.id_emply = '".$rowsvalues['emply_id']."'
																					  ");
																						
														$rowsatten = mysqli_fetch_array($sqlatten);
														echo'<td style="text-align: center;"> '. get_attendtype($rowsatten['status']).' </td>';													
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
										<i class="glyphicon glyphicon-print"></i> Print </a>
									</div>
								</div> -->
							</form>
						</section>
					</div>';
				}elseif(isset($_POST['attendance_report'])){
					$sqlDateFrom = "";
					$sqlDateTo = "";
					if(!empty($date_from)){
						$sqlDateFrom	=	"AND a.dated >= '".date('Y-m-d', strtotime($date_from))."'";
					}
					if(!empty($date_to)){
						$sqlDateTo		=	"AND a.dated <= '".date('Y-m-d', strtotime($date_to))."'";
					}
					$sqllms	= $dblms->querylms("SELECT e.emply_photo, e.emply_name, e.emply_email, d.designation_status, d.designation_name, 
												COUNT(CASE WHEN ad.status = '1' THEN 1 else null end) as `present`, 
												COUNT(CASE WHEN ad.status = '2' THEN 1 else null end) as `absent`, 
												COUNT(CASE WHEN ad.status = '3' THEN 1 else null end) as `leave`, 
												COUNT(CASE WHEN ad.status = '4' THEN 1 else null end) as `late`
												FROM ".EMPLOYEES_ATTENDCE." a
												INNER JOIN ".EMPLOYEES_ATTENDCE_DETAIL." ad ON ad.id_setup = a.id 
												INNER JOIN ".EMPLOYEES." e ON e.emply_id = ad.id_emply AND e.id_dept = '".$dept."' AND e.emply_status = '1' AND e.is_deleted != '1'
												INNER JOIN ".DESIGNATIONS." d ON d.designation_id = e.id_designation 
												WHERE a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
												$sqlDateFrom
												$sqlDateTo
												GROUP BY ad.id_emply
											  ");
					if(mysqli_num_rows($sqllms) > 0){
						echo'
						<div id="" class="" style=" overflow: auto;">
							<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
								<header class="panel-heading">
									<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> 
										Employees  Attendance Report <b>('.date('d M, Y', strtotime($date_from)).' To '.(!empty($date_to) ? date('d M, Y', strtotime($date_to)) : date('d M, Y')).')</b> 
									</h2>
								</header>
								<div class="panel-body">								
									<div class="text-right on-screen mb-md">
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
											<h5 style="text-align: center;">'; if($date_from && $date_to){echo' From '.date('d M, Y' , strtotime($date_from)).' To '.date('d M, Y' , strtotime($date_to)).' ';} echo'</h5>
										</div>
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-condensed mb-none ">
												<thead>
													<tr>
														<th style="width:40px; text-align: center;">Sr#</th>
														<th style="text-align: center;">Photo</th>
														<th> Employees <i class="fa fa-hand-o-down"></i></th>
														<th style="text-align: center;">Present</th>
														<th style="text-align: center;">Absent</th>
														<th style="text-align: center;">Leave</th>
														<th style="text-align: center;">Late</th>
													</tr>
												</thead>
												<tbody>';
													$srno = 0;
													while($rowsvalues = mysqli_fetch_array($sqllms)) {
														
														$srno++;
													
														echo'
														<tr>
															<td style="width:40px; text-align: center;">'.$srno.'</td>
															<td class="center"><img src="https://cms.laurelhomeschools.com/uploads/images/employees/'.$rowsvalues['emply_photo'].'" width="35" height="35"></td>
															<td>
																<b>'.$rowsvalues['emply_name'].'</b>
																<span class="ml-sm label label-primary"> '.$rowsvalues['designation_name'].'</span>
															</td>
															<td class="center">'.$rowsvalues['present'].'</td>
															<td class="center">'.$rowsvalues['absent'].'</td>
															<td class="center">'.$rowsvalues['leave'].'</td>
															<td class="center">'.$rowsvalues['late'].'</td>
														</tr>';								
													}
													echo'				
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<!-- <div class="panel-footer">
									<div class="text-right">
										<a href="attendance/employees_report_print/1/b" class="btn btn-sm btn-primary " target="_blank">
										<i class="glyphicon glyphicon-print"></i> Print			</a>
									</div>
								</div> -->
							</section>
						</div>';
					}else{
						echo'
						<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
							<h2 class="panel-body text-center font-bold text text-danger">No Record Found</h2>
						</section';
					}
				}
				echo '
			</div>
		</div>
	</section>';
}
else{
	header("Location: dashboard.php");
}
?>

<script type="text/javascript">
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