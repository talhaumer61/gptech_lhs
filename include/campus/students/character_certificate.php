<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))){
	$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];

	// VARIABLES
	$sql1 = "";
	$sql2 = "";
	$sql3 = "";
	$sql4 = "";
	$class = "";
	$std_status = "";
	$std_gender = "";
	$search_word = "";
	$filters = "";

	// FILTERS
	if(isset($_POST['show_students'])){
		// GENDER
		if($_POST['std_gender']){
			$std_gender = $_POST['std_gender'];
			$sql1 = "AND s.std_gender = '".$std_gender."'";
		}
		// CLASS
		if($_POST['id_class']){
			$class = $_POST['id_class'];
			$sql2 = "AND s.id_class = '".$class."'";
		}
		// STATUS
		if($_POST['status']){
			$std_status = $_POST['status'];
			$sql3 = "AND s.std_status = '".$std_status."'";
		}
		// SEARCH WORDS
		if($_POST['search_word']){
			$search_word = $_POST['search_word'];
			$sql4 = "AND (s.std_name LIKE '%".$search_word."%' OR s.std_fathername LIKE '%".$search_word."%' OR s.std_regno LIKE '%".$search_word."%')";
		}
		// ID_CLASSLEVEL
		if($_POST['id_classlevel']){
			$id_classlevel = $_POST['id_classlevel'];
			$sql5 = "AND c.id_classlevel = '".$id_classlevel."'";
		}
	}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select Filter</h2>
		</header>
		<div class="panel-body">
			<form action="#" method="post" autocomplete="off">
				<div class="row form-group mb-lg">
					<div class="col-md-3">
						<label class="control-label">Search </label>
						<div class="form-group">
							<input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="student name or registration number or father name" aria-controls="table_export">
						</div>
					</div>
					<div class="col-md-2">
						<label class="control-label">Level </label>
						<select data-plugin-selectTwo data-width="100%" name="id_classlevel" id="id_classlevel" title="Must Be Required" class="form-control populate" onchange="get_classlevel(this.value)">
							<option value="">Select</option>';
							foreach ($classlevel as $level):
								echo'<option value="'.$level['id'].'" '.($id_classlevel==$level['id'] ? 'selected' : '').'>'.$level['name'].'</option>';
							endforeach;
							echo'
						</select>
					</div>
					<div class="col-md-2">
						<label class="control-label">Class </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class">
							<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
													FROM ".CLASSES." 
													WHERE class_status = '1'
													AND is_deleted != '1'
													ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'"'; if($class == $valuecls['class_id']){ echo'selected';} echo'>'.$valuecls['class_name'].'</option>';
								}
								echo '
						</select>
					</div>
					<div class="col-md-2">
						<label class="control-label">Gender </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="std_gender">
							<option value="">Select</option>';
							foreach($gender as $gndr){
								echo '<option value="'.$gndr.'"'; if($std_gender == $gndr){ echo'selected';} echo'>'.$gndr.'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-2">
						<label class="control-label">Status </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="status">
							<option value="">Select</option>';
							foreach($admstatus as $stat){
								echo '<option value="'.$stat['id'].'"'; if($std_status == $stat['id']){ echo'selected';} echo'>'.$stat['name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-1">
						<div class="form-group mt-xl">
							<button type="submit" name="show_students" class="btn btn-primary btn-block"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>';
	
	// FILTER RESULTS
	if(isset($_POST['show_students'])){
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
									$sql1 $sql2 $sql3 $sql4 $sql5
									ORDER BY s.std_id DESC");
		if(mysqli_num_rows($sqllms) > 0){
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i>  Character Certificate</h2>
				</header>
				<div class="panel-body">
					<div class="text-right mr-lg on-screen">
						<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
					</div>
					<div id="printResult">
						<style>
							.page-break {
								-webkit-print-color-adjust: exact !important;
							}
							@media print {
								.page-break	{
									page-break-before: always;
								}
								@page { 
									
								}
							}
							.page-break{
								color: #333;
								padding: 0 5rem;
							}
							.font-times{
								font-family:"Times New Roman", Times, serif; 
								color: #333; 
								font-weight:bold;
							}
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
													<h5>'.$rowsvalues['campus_address'].'</h5>
													<h5 class="font-times"><b>'.$rowsvalues['campus_phone'].'</b></h5>
												</td>
											</tr>
										</tbody>
									</table>
									<table width="100%">
										<tbody>
											<tr>
												<td class="text-center">
													<h3 class="font-times mb-lg">CHARACTER CERTIFICATE</h3>
												</td>
											</tr>
											<tr>
												<td class="text-center">
													<img src="'.$photo.'" class="mb-lg" width="100" height="100" style="border-radius: 50%;">
													<h4 style="text-align: justify;">Is is certify that <b>'.$rowsvalues['std_name'].'</b> s/o d/o <b>'.$rowsvalues['std_fathername'].'</b> who has studied in this institute from <b>'.date('d F, Y', strtotime($rowsvalues['std_admissiondate'])).'</b> to <b>'.date('d F, Y').'</b> bears a good moral character. His/Her behaviour was good with teachers and students. He/She has neither displayed presistent violent of or aggressive behaviour nor any desire to harm others.</h4>
												</td>
											</tr>
										</tbody>
									</table>
									<table width="100%" style="margin-top: 20rem;">
										<body>
											<tr>
												<td width="90">Date of issue:</td>
												<td width="100" class="border-bottom center">'.date('d-m-Y').'</td>
												<td width=""></td>
												<td width="80">Principal:</td>
												<td width="200" class="border-bottom"></td>
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
	}
}else{
	header("Location: dashboard.php");
}
?>