<?php
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
	if(!empty($_SESSION['userlogininfo']['LOGINCAMPUS'])){
		$sql = " WHERE campus_id = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ";
	}
	else{
		$sql = "";
	}
	// CAMPUS
	$sqllmscampus	= $dblms->querylms("SELECT campus_id, campus_code, campus_name, campus_address, campus_phone
										FROM ".CAMPUS." $sql LIMIT 1");
	$value_campus = mysqli_fetch_array($sqllmscampus);

	// STUDENT
	$sqllms_std	= $dblms->querylms("SELECT  s.std_name, s.std_fathername, s.std_rollno, s.std_regno, s.std_photo, s.std_dob, s.std_gender, s.id_guardian, s.std_phone, s.std_whatsapp, s.std_admissiondate, s.std_nic, s.std_c_address, c.class_name, cs.section_name, g.group_name
													FROM ".STUDENTS." 		s
													INNER JOIN ".CLASSES."  c  ON c.class_id = s.id_class
													INNER JOIN ".CLASS_SECTIONS."  cs  ON cs.section_id = s.id_section
													LEFT JOIN ".GROUPS." g ON g.group_id = s.id_group
													WHERE s.std_id != '' AND s.std_id = '".$_GET['id']."'
													AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ");
	$value_std = mysqli_fetch_array($sqllms_std);
	if($value_std['std_photo']) { 
		$photo = "uploads/images/students/".$value_std['std_photo']."";
	}
	else{
		$photo = "uploads/default-student.jpg";
	}
	echo '
	<div id="print" style="orientation: landscape">
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css"/>
		<link rel="stylesheet" href="assets/stylesheets/theme.css"/>

		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css"/>
		<script src="assets/vendor/jquery/jquery.js"></script>
		<style type="text/css">
			body .btn-primary {
				color: #ffffff;
				text-shadow: 0 -1px 0 rgb(0 0 0 / 25%);
				background-color: #cb3f44;
				border-color: #cb3f44;
			}
			body .btn {
				white-space: normal;
			}
			.ml-sm {
				margin-left: 10px !important;
			}
			.mb-xs {
				margin-bottom: 5px !important;
			}
			.pull-right {
				float: right !important;
			}
			.btn {
				margin-right:20px;
				display: inline-block;
				padding: 6px 12px;
				font-size: 14px;
				font-weight: normal;
				line-height: 1.42857143;
				text-align: center;
				vertical-align: middle;
				touch-action: manipulation;
				cursor: pointer;
				user-select: none;
				background-image: none;
				border: 1px solid transparent;
				border-radius: 4px;
			}
			@media print {
				#printPageButton {
					display: none;
				}
			}
			td {
				padding: 5px;
				border: 1px solid #ffcfcf;
			}
			th {
				border: 1px solid #ffcfcf;
			}
		</style>
		<br>
		
			<img src="uploads/logo.png" style="max-height : 100px;">
			<center style="margin-top: -100px;  text-align:right">
				<h3 style="font-weight: 100;">Laurel Home International Schools <span style="text-transform: capitalize;">('.$value_campus['campus_name'].')</span></h3>
				<h4 class="center">Student Profile</h4>
			</center>
		<br>
		<table class="table mt-sm">
			<tr>
				<td style="text-align: left;">
					Student: <u>'.$value_std['std_name'].'</u><br>
					Father: <u>'.$value_std['std_fathername'].'</u><br>
					Class: <u>'.$value_std['class_name'].'</u><br>
					Section: <u>'.$value_std['section_name'].'</u><br>
					Registration# <u>'.$value_std['std_regno'].'</u><br>
					Roll non: <u>'.$value_std['std_rollno'].'</u><br>
				</td>
				<td style="width: 150px; text-align: center;" rowspan="3"><img src="'.$photo.'" style="width:120px; height:150px;"></td>
			</tr>
		</table>
		<section class="panel mt-md">
			<div>
				<table class="table">
					<tbody>	
						<tr>
							<td style="text-align: left; width: 150px;">Group</td>
							<td>'.$value_std['group_name'].'</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">Date of Birth</td>
							<td>'.date('d M, Y', strtotime($value_std['std_dob'])).'</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">Gender</td>
							<td>'.$value_std['std_gender'].'</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">Guardian</td>
							<td>';
								if($value_std['id_guardian']){
									echo''.get_guardian($value_std['id_guardian']).' '; 
								}
							echo'
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">Phone</td>
							<td>'.$value_std['std_phone'].'</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">Whatsapp</td>
							<td>'.$value_std['std_whatsapp'].'</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">Admission Date</td>
							<td>'.date('d M, Y', strtotime($value_std['std_admissiondate'])).'</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">CNIC / B-from#</td>
							<td>'.$value_std['std_nic'].'</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 150px;">Address</td>
							<td>'.$value_std['std_c_address'].'</td>
						</tr>
					</tbody>
				</table>
				<h4 style="margin-top: 50px; text-align:center;">'.$value_campus['campus_address'].' | '.$value_campus['campus_phone'].'</h4>
			</div>
		</section>
	</div>
	<button type="button" id="printPageButton" onClick="window.print();" class="modal-with-move-anim ml-sm mb-xs btn btn-primary btn-xs pull-right">Print</button>';
?>

