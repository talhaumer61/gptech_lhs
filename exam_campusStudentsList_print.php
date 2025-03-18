<?php
//-----------------------------------------------
error_reporting(E_ALL);
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
//-----------------------------------------------
//------------------------ CAMPUS INFO -----------------------
$sqllmscampus	= $dblms->querylms("SELECT campus_id, campus_code, campus_name, campus_address, campus_email
									FROM ".CAMPUS." 
									WHERE campus_id = '".$_POST['id_campus']."' LIMIT 1");
$value_campus = mysqli_fetch_array($sqllmscampus);
//---------------------- DATESHEET -------------------------
$sqllms	= $dblms->querylms("SELECT t.id, t.status, t.id_exam, t.id_session, t.id_class, t.id_section, t.id_campus,
									e.type_name, c.class_name
									FROM ".DATESHEET." t
									INNER JOIN ".EXAM_TYPES."  	 e	ON 	e.type_id 		= t.id_exam
									INNER JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
									WHERE e.type_id = '".$_POST['id_type']."' AND t.id_campus = '".$_POST['id_campus']."' 
                                    AND t.is_deleted != '1'
									LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
//------------------ EXAM SESSION NAME -----------------------------
$sqllms_setting	= $dblms->querylms("SELECT session_name 
											FROM ".SESSIONS."
											WHERE session_status ='1' AND is_deleted != '1' AND session_id = '".$_SESSION['userlogininfo']['EXAM_SESSION']."' LIMIT 1");
$values_setting = mysqli_fetch_array($sqllms_setting);
//-----------------------------------------------------
echo '
<div id="print" style="orientation: landscape">
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="assets/stylesheets/theme.css"/>

	<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css"/>
	<script src="assets/vendor/jquery/jquery.js"></script>
	<style type="text/css">
		td {
			padding: 5px;
			border: 1px solid #ffcfcf;
		}
		th {
			border: 1px solid #ffcfcf;
		}
		
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
	@page { 
		size: A4 potrait;
	   margin: 4mm 4mm 4mm 4mm; 
	}
}
	</style>
	<br>
	
		<img src="uploads/logo.png" style="max-height : 100px;">
		<center style="margin-top: -100px;  text-align:right">
			<h3 style="font-weight: 100;">Laurel Home International Schools </h3>
			Students List ('.$rowsvalues['type_name'].' - '.$values_setting['session_name'].')
		</center>
	<br>
	<h4 style="font-weight: 100;">Campus: <span style="text-transform: capitalize;"><u>'.$value_campus['campus_name'].'</u></span></h4>
	<h4 style="font-weight: 100; text-align: right; margin-top: -30px;">Campus Code: <span style="text-transform: capitalize;"><u>'.$value_campus['campus_code'].'</u></span></h4>
    <table class="table mt-md">
	<thead>
		<tr>
			<th style="text-align:center;">Sr. #</th>
			<th>Registration#</th>
			<th>Class</th>
			<th>Student Name</th>
			<th>Father\'s Name</th>
			<th width="70px;" style="text-align:center;">Photo</th>
		</tr>
	</thead>
	<tbody>';
	//------------------- STUDENT DETAILS ---------------------------
	$sqllms_std	= $dblms->querylms("SELECT  s.std_name, s.std_fathername, s.std_rollno, s.std_regno, c.class_name, cs.section_name, s.std_photo
													FROM ".STUDENTS." 		s
													INNER JOIN ".CLASSES."  c  ON c.class_id = s.id_class
													INNER JOIN ".CLASS_SECTIONS."  cs  ON cs.section_id = s.id_section
													WHERE s.std_id != '' 
													AND s.id_campus ='".$_POST['id_campus']."' 
													ORDER BY c.class_id");
	//--------------------------------------
	$srno = 0;
	//--------------------------------------
	while($value_std = mysqli_fetch_array($sqllms_std)){
		//--------------------------------------
		if($value_std['std_photo']) { 
			$photo = "uploads/images/students/".$value_std['std_photo']."";
		}
		else{
			$photo = "uploads/default-student.jpg";
		}
		//--------------------------------------
		$srno++;
		//--------------------------------------
		echo'
			<tr>
				<td style="text-align: center;">'.$srno.'</td>
				<td>'.$value_std['std_regno'].'</td>
				<td>'.$value_std['class_name'].'</td>
				<td>'.$value_std['std_name'].' </td>
				<td>'.$value_std['std_fathername'].'</td>
				<td style="width: 150px; text-align: center;"><img src="'.$photo.'" style="width:50px; height:50px;"></td>
			</tr>';
	}
	//--------------------------------------
echo'
</tbody>
    </table>
</div>
<div class="page-break"></div>';
?>
<script type="text/javascript" language="javascript1.2">
 //Do print the page
if (typeof(window.print) != "undefined") {
    window.print();
}
</script>
