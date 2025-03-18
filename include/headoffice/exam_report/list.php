<?php 	
echo '
<style type="text/css">
@media all {
	.page-break	{ display: none; }
}
@media print {
	.page-break	{ display: block; page-break-before: always; }
	@page {
		margin: 4mm 4mm 4mm 4mm; 
	}
}
</style>
';
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)){ 
	if (empty(LMS_VIEW) && empty($_GET['edit_id'])){
		$sql1 			= "AND d.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'";
		$sql2 			= '';
		$sql3 			= '';
		$sql4 			= '';
		$id_session 	= "";
		$id_campus 		= "";
		$id_type 		= "";
		$campus_name	= "";

		$sqllmsSession	= $dblms->querylms("SELECT s.session_id, s.session_name
											FROM ".SESSIONS." s
											WHERE s.is_deleted = '0' 
											ORDER BY s.session_name DESC
		");
		$sqllmsCampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
											FROM ".EXAM_REGISTRATION." er
											INNER JOIN ".CAMPUS." c ON c.campus_id = er.id_campus
											WHERE c.is_deleted		= '0' 
											AND c.campus_status		= '1'
											AND er.reg_status		= '1'
											AND er.is_deleted		= '0'
											AND er.is_publish		= '1'
											GROUP BY er.id_campus
											ORDER BY c.campus_name ASC
		");
		$sqllmsExamType	= $dblms->querylms("SELECT et.type_id, et.type_name
											FROM ".EXAM_REGISTRATION." er
											INNER JOIN ".EXAM_TYPES." et ON et.type_id = er.id_type
											WHERE et.is_deleted		= '0' 
											AND et.type_status		= '1'
											AND er.reg_status		= '1'
											AND er.is_deleted		= '0'
											AND er.is_publish		= '1'
											GROUP BY er.id_type
											ORDER BY et.type_name ASC
		");

		if (isset($_POST['search_exam_report'])):
			if (!empty($_POST['id_session'])):
				$sql1 			= "AND d.id_session = '".cleanvars($_POST['id_session'])."'";
				$id_session 	= cleanvars($_POST['id_session']);
			endif;
			if (!empty($_POST['id_campus'])):
				$aray = explode("|", $_POST['id_campus']);
				$id_campus		= $aray[0];
				$campus_name	= $aray[1];

				$sql2 			= "AND e.id_campus = '".cleanvars($id_campus)."'";
			endif;
			if (!empty($_POST['id_type'])):
				$sql3 			= "AND d.id_type = '".cleanvars($_POST['id_type'])."'";
				$id_type 		= cleanvars($_POST['id_type']);
			endif;
		endif;

		$sqllms	= $dblms->querylms("SELECT GROUP_CONCAT(e.reg_id) reg_ids, c.class_id, c.class_name
									FROM ".EXAM_REGISTRATION." e
									INNER JOIN ".CLASSES." c ON e.id_class = c.class_id
									WHERE c.is_deleted = '0'
									AND c.class_status = '1'
									AND e.is_deleted = '0'
									AND e.reg_status = '1'
									AND e.is_publish = '1'
									AND e.id_session = '".cleanvars($id_session)."'
									$sql2
									AND e.id_type = '".cleanvars($id_type)."'
									GROUP BY e.id_class
									ORDER BY c.class_id ASC
		");

		$sql = "SELECT s.subject_id  , s.subject_name
				FROM ".CLASS_SUBJECTS." s
				WHERE s.is_deleted = '0'
				AND s.subject_status = '1'";
		echo '
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Filters </h2>
			</header>
			<div class="panel-body">
				<form action="exam_report.php" method="POST">
					<div class="row mt-sm">
						<div class="col-md-4">
							<label class="control-label">Session <span class="text-danger">*</span></label>
							<select data-plugin-selectTwo data-width="100%" required name="id_session" id="id_class" class="form-control populate">
								<option value="">Select</option>';
								while($val = mysqli_fetch_array($sqllmsSession)):
									echo'<option value="'.$val['session_id'].'" '.(($val['session_id'] == $id_session) ? 'selected': '').'>'.$val['session_name'].'</option>';
								endwhile;
								echo'
							</select>
						</div>
						<div class="col-md-4">
							<label class="control-label">Campus </label>
							<select data-plugin-selectTwo data-width="100%" name="id_campus" id="id_class" class="form-control populate">
								<option value="">Select</option>';							
								while($val = mysqli_fetch_array($sqllmsCampus)):
									echo'<option value="'.$val['campus_id'].'|'.$val['campus_name'].'" '.(($val['campus_id'] == $id_campus) ? 'selected': '').'>'.$val['campus_name'].'</option>';
								endwhile;
								echo '
							</select>
						</div>
						<div class="col-md-4">
							<label class="control-label">Exam Term <span class="text-danger">*</span></label>
							<select data-plugin-selectTwo data-width="100%" required name="id_type" id="id_class" class="form-control populate">
								<option value="">Select</option>';							
								while($val = mysqli_fetch_array($sqllmsExamType)):
									echo'<option value="'.$val['type_id'].'" '.(($val['type_id'] == $id_type) ? 'selected': '').'>'.$val['type_name'].'</option>';
								endwhile;
								echo'
							</select>
						</div>
						<div class="col-md-12">
							<center style="padding-top: 2rem;">
								<input type="submit" name="search_exam_report" class="btn btn-primary" value="Search">
							</center>
						</div>
					</div>
				</form>
			</div>
		</section>';

		if (isset($_POST['search_exam_report'])){
			if (mysqli_num_rows($sqllms) > 0){
				echo '
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-list"></i> Subject Wise Registration Report</h2>
					</header>
					<div class="panel-body">';
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) ||($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '13', 'added' => '1'))){
							echo '
							<div class="text-right mb-lg on-screen">
								<button onclick="print_report(\'printResult\');" class="mr-xs btn btn-danger btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
								<button id="export_button" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-file"></i> Excel</button>
							</div>';
						}
						echo'
						<div id="printResult">
							<div id="header" style="display:none;">
								<h2 style="text-align: center;">
									<img src="uploads/logo.png" class="img-fluid" style="width: 70px; height: 70px;"> 
									<span><b>Laurel Home Schoool</b></span>
								</h2>
								<h4 style="text-align: center;"><b>Exam Registration Report</b></h4>
								<h4 style="text-align: center;"><b>'.$campus_name.'</b></h4>
							</div>
							<table class="table table-bordered table-striped table-condensed mb-none">';
								while($val = mysqli_fetch_array($sqllms)):
									$total = 0;
									echo'
									<thead>
										<tr>
											<th class="center" style="background: #cb3f44; color: #fff;">'.$val['class_name'].'</th>';
											$sqllmsSubject	= $dblms->querylms("$sql AND s.id_class = '".cleanvars($val['class_id'])."'");
											while($valSubject = mysqli_fetch_array($sqllmsSubject)):
												echo'<th class="center" ><b>'.$valSubject['subject_name'].'</b></th>';
											endwhile;
											echo'
											<th class="center" style="background: #000; color: #fff;">Total</th>
										</tr>										
									</thead>
									<tbody>
										<tr>
											<td class="center">Papers Print</td>';
											$sqllmsSubject	= $dblms->querylms("$sql AND s.id_class = '".cleanvars($val['class_id'])."'");
											while($valSubject = mysqli_fetch_array($sqllmsSubject)):
												$sqllmsSubjectCount	= $dblms->querylms("SELECT ed.id_std
																							FROM ".EXAM_REGISTRATION_DETAIL." ed
																							INNER JOIN ".EXAM_REGISTRATION." e ON e.reg_id = ed.id_reg
																							AND e.reg_id 		IN (".cleanvars($val['reg_ids']).")
																							AND e.id_type 		= '".cleanvars($id_type)."'
																							AND FIND_IN_SET(".cleanvars($valSubject['subject_id']).", ed.id_subjects)
																						");
												$total += mysqli_num_rows($sqllmsSubjectCount);
												echo '<td class="center" >'.mysqli_num_rows($sqllmsSubjectCount).'</td>';

											endwhile;
											echo '
											<th class="center">'.$total.'</th>
										</tr>
									</tbody>';
								endwhile;
								echo '
							</table>
						</div>
					</div>
				</section>';
			}
			else
			{
				echo '
				<section class="panel panel-featured panel-featured-primary">
					<h2 class="panel-body center text-danger mt-none">Record Not Found!</h2>
				</section>';
			}
		}
	}
}
else{
	header("Location: dashboard.php");
}

?>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    // PRINT THE TABLE 
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