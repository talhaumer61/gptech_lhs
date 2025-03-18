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
			$sqllms	= $dblms->querylms("SELECT s.*, c.class_name, c.id_classlevel, se.section_name, sn.session_name, cm.campus_name, cm.campus_email, cm.campus_phone, cm.campus_address, a.adm_username
										FROM ".STUDENTS." s
										INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
										LEFT JOIN ".CLASS_SECTIONS." se ON se.section_id = s.id_section
										LEFT JOIN ".SESSIONS." sn ON sn.session_id = s.id_session
										LEFT JOIN ".ADMINS." a ON a.adm_id = s.id_loginid
										INNER JOIN ".CAMPUS." cm ON cm.campus_id = s.id_campus
										WHERE s.std_id	   != ''
										AND s.is_deleted	= '0'
										AND s.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
										AND id_loginid		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
										ORDER BY s.std_id DESC");
			if(mysqli_num_rows($sqllms) > 0){
				echo'
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-list"></i>  Admission Letter</h2>
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
							$sr = 0;
							while($rowsvalues = mysqli_fetch_array($sqllms)){
								$sr++;
								if($rowsvalues['std_photo']) { 
									$photo = "uploads/images/students/".$rowsvalues['std_photo']."";
								}
								else{
									$photo = "uploads/default-student.jpg";
								}
								echo'
								<div class="page-break">
									<div class="table-responsive">
										<table width="100%" class="mt-lg">
											<tbody>
												<tr>
													<td class="text-center" width="100"><img src="uploads/logo.png" style="max-height : 100px;"></td>
												</tr>
												<tr>
													<td class="text-center">
														<h1 class="font-times">Laurel Home International Schools</h1>
														<h3 class="font-times"><span style="text-decoration:underline">'.$rowsvalues['campus_name'].'</span></h3>
														<h5 class="font-times">'.$rowsvalues['campus_address'].'</h5>
														<h5 class="font-times"><b>'.$rowsvalues['campus_phone'].'</b></h5>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%">
											<tbody>
												<tr>
													<td class="text-center">
														<h3 class="font-times" style="text-decoration: underline">Admission Letter</h3>
														<h5 class="font-times">Thank you for selection <b>Laural Home International School '.$rowsvalues['campus_name'].'</b> and giving us honor for the admission of your child. We have assessed <b>'.$rowsvalues['std_name'].'</b> who has successfully Obtained Admission.</h5>
													</td>
												</tr>
											</tbody>
										</table>
										<table width="100%" class="mt-xl">
											<tbody>
												<tr>
													<td class="text-center" rowspan="4" width="150"><img src="'.$photo.'" width="100" height="100" style="border-radius: 50%;"></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Serial No: <b>'.$sr.'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Date of Birth: <b>'.$rowsvalues['std_dob'].'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Admission Date: <b>'.$rowsvalues['std_admissiondate'].'</b></div></td>
												</tr>
												<tr>
													<td class="pr-10 pl-10"><div class="border-bottom">Reg No: <b>'.$rowsvalues['std_regno'].'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">CNIC No: <b>'.$rowsvalues['std_nic'].'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Session: <b>'.$rowsvalues['session_name'].'</b></div></td>
												</tr>
												<tr>
													<td class="pr-10 pl-10"><div class="border-bottom">Student Name: <b>'.$rowsvalues['std_name'].'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Gender: <b>'.$rowsvalues['std_gender'].'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Class: <b>'.$rowsvalues['class_name'].'</b></div></td>
												</tr>
												<tr>
													<td class="pr-10 pl-10"><div class="border-bottom">Blood Group: <b>'.$rowsvalues['std_bloodgroup'].'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Father Name: <b>'.$rowsvalues['std_fathername'].'</b></div></td>
													<td class="pr-10 pl-10"><div class="border-bottom">Username: <b>'.$rowsvalues['adm_username'].'</b></div></td>
												</tr>
											</tbody>
										</table>
										<table width="100%" class="mt-xl">
											<tbody>
												<tr>
													<th class="pr-10 pl-10">Rules And Regulations</th>
												</tr>
												<tr>
													<td class="pr-10 pl-10">The School have been established in partnership with the headoffice Laurel Home International School and campus management over a long period of time.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">Students are expected to follow the school rules at all times when on the school grounds, representing the school, attending a school activity or when clearly associated with school i.e when wearing school uniform.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10"><b>Students have the responsibility:</b></td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To attend school regularly.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To respect the right of other to learn.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To respect their peers and teachers regardless of ethnicity, religion or gender.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To respect the property and equipment of the school and others.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To carry out reasonable instructions to the best of their ability.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To conduct themselves in a courteous and approriate manner in school and in public.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To keep the school environment and the local community free from litter.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To observe the uniform code of the school.</td>
												</tr>
												<tr>
													<td class="pr-10 pl-10">To read all school notices and bring them to their parents\'/guadians\' attention.</td>
												</tr>
											</tbody>
										</table>
										<table width="100%" class="mt-xl">
											<tbody>
												<tr>
													<td width="220" class="pr-10 pl-10">Signature of parents\' / guadians\':</td>
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