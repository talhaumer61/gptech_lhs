<?php 
echo '
<title> Admssion Letter | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Admission Letter </h2>
	</header>
	<div class="row">
		<div class="col-md-12">';
			// FILTER RESULTS
			$sqllms	= $dblms->querylms("SELECT e.*, d.dept_name, dp.designation_name, cm.campus_name, cm.campus_email, cm.campus_phone, cm.campus_address, ql.q_l_name, a.adm_username
										FROM ".EMPLOYEES." e
										INNER JOIN ".CAMPUS." cm ON cm.campus_id = e.id_campus
										LEFT JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
										LEFT JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
										LEFT JOIN ".QUALIFICATION_LEVELS." ql ON ql.q_l_id = e.id_q_l
										LEFT JOIN ".ADMINS." a ON a.adm_id = e.id_loginid
										WHERE e.emply_id   != ''
										AND e.is_deleted	= '0'
										AND e.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
										AND e.id_loginid	= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
										ORDER BY e.emply_id ASC");
			if(mysqli_num_rows($sqllms) > 0){
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i>  Job Letter</h2>
				</header>
				<div class="panel-body">
					<div class="text-right mr-lg on-screen">
						<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
					</div>
					<div id="printResult">
						<style>
							.border-bottom{
								border-bottom: 1px solid #777;
							}
							.pr-10{
								padding-right: 5px;
							}
							.pl-10{
								padding-left: 5px;
							}
							td{
								font-size: 12px;
							}
						</style>';
						while($rowsvalues = mysqli_fetch_array($sqllms)){
							$year = date('Y',strtotime($rowsvalues['emply_joindate']));
							$sr = $year.$rowsvalues['emply_id'];

							if($rowsvalues['emply_photo']){ 
								$photo = "uploads/images/employees/".$rowsvalues['emply_photo']."";
							}else{
								$photo = "uploads/defualt.png";
							}
							echo'
							<div class="page-break">
								<div class="table-responsive">
									<table width="100%" class="mt-md">
										<tbody>
											<tr>
												<td class="text-center" width="100"><img src="uploads/logo.png" style="max-height : 100px;"></td>
											</tr>
											<tr>
												<td class="text-center">
													<h3 class="font-times">Laurel Home International Schools <br> <span style="text-decoration:underline">'.$rowsvalues['campus_name'].'</span></h3>
													<h5 class="font-times">'.$rowsvalues['campus_address'].'</h5>
													<h5 class="font-times"><b>'.$rowsvalues['campus_phone'].'</b></h5>
													<h3 class="font-times" style="text-decoration: underline">Job Letter</h3>
												</td>
											</tr>
										</tbody>
									</table>
									<table width="100%" class="mt-md">
										<tbody>
											<tr>
												<td class="text-center" rowspan="5" width="150"><img src="'.$photo.'" width="100" height="100" style="border-radius: 50%;"></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Serial No: <b>'.$sr.'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">CNIC: <b>'.$rowsvalues['emply_cnic'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Joining Date: <b>'.$rowsvalues['emply_joindate'].'</b></div></td>
											</tr>
											<tr>
												<td class="pr-10 pl-10"><div class="border-bottom">Reg No: <b>'.$rowsvalues['emply_regno'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Type: <b>'.($rowsvalues['id_type']==1 ? 'Teaching' : 'Non-Teaching').'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Username: <b>'.$rowsvalues['adm_username'].'</b></div></td>
											</tr>
											<tr>
												<td class="pr-10 pl-10"><div class="border-bottom">Employee Name: <b>'.$rowsvalues['emply_name'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Salary: <b>'.$rowsvalues['emply_basicsalary'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Rreligion: <b>'.$rowsvalues['emply_religion'].'</b></div></td>
											</tr>											
											<tr>
												<td class="pr-10 pl-10"><div class="border-bottom">Date of Birth: <b>'.$rowsvalues['emply_dob'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Blood Group: <b>'.$rowsvalues['emply_bloodgroup'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Education: <b>'.$rowsvalues['q_l_name'].'</b></div></td>
											</tr>
											<tr>
												<td class="pr-10 pl-10"><div class="border-bottom">Gender: <b>'.$rowsvalues['emply_gender'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Experience: <b>'.$rowsvalues['emply_experence'].'</b></div></td>
												<td class="pr-10 pl-10"><div class="border-bottom">Contact No: <b>'.$rowsvalues['emply_phone'].'</b></div></td>
											</tr>
										</tbody>
									</table>
									<table width="100%" class="mt-md">
										<tbody>
											<tr>
												<th class="pr-10 pl-10">Rules And Regulations</th>
											</tr>
											<tr>
												<td class="pr-10 pl-10">The School have been established in partnership with the headoffice Laurel Home International School and campus management over a long period of time.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Staff members are expected to follow the school rules at all.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10"><b>Major responsibility:</b></td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">To attend school regularly.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Establishing and enforcing rules of behavior for students in the classroom.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Establishing and communicating clear objectives for lessons, unit and projects.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Adapting teaching methods and materials to meet the interests and learning styles of students.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Encouraging students to explore learning opportunities and career paths.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Creating, assigning and grading various assessments for students, including tests, quizzes, essays and projects.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Working with students one-on-one when they need extra help or attention.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Tracking and evaluating student academic progress.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Leading Parent-Teacher meetings.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Maintaining positive relationships with students, parents, coworkers and supervisors.</td>
											</tr>											
											<tr>
												<td class="pr-10 pl-10">Managing stuedent behaviour.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Creating a safe, respectful and inclusive classroom environment.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Comminicating regularly with parents.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Helping students improve study mehtods and habits.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">Administering tests to evaluate students\' progress.</td>
											</tr>
											<tr>
												<td class="pr-10 pl-10">To read all school notices and follow them accordingly.</td>
											</tr>
										</tbody>
									</table>
									<table width="100%" class="mt-md">
										<tbody>
											<tr>
												<td width="120" class="pr-10 pl-10">Signature of Staff:</td>
												<td width="220" class="pr-10 pl-10 border-bottom"></td>
												<td></td>
											</tr>
										</body>
									</table>
									<table width="100%" class="mt-md">
										<body>
											<tr>
												<td width="160" class="pr-10 pl-10">Signature of Authority:</td>
												<td width="220" class="pr-10 pl-10 border-bottom"></td>
												<td width="20"></td>
												<td width="120" class="pr-10 pl-10">Institure Stamp:</td>
												<td width="220" class="pr-10 pl-10 border-bottom"></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>';
						}
						echo'
					</div>
				</div>
			</section>';
		}else{
			echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
		}
		echo'
		</div>
	</div>
</section>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
	var datatable = $('#table_export').dataTable({
				bAutoWidth : false,
				ordering: false,
			});
	});
	
	function print_report(printResult) {
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
<?php
echo '
</section>
</div>
</section>';
?>