<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
include_once("include/header.php");

if($_SESSION['userlogininfo']['LOGINAFOR'] == 2){
	echo'
	<title>Fee Report | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fee Report</h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if(isset($_POST['status'])){
					$status = $_POST['status'];
					if($status == 'total'){
						$sqlStatus	=	"";  
					}else{
						$sqlStatus	=	"AND f.status = '".$status."'";
					}
				}		
				echo'
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-list"></i>  Select Report Status</h2>
					</header>
					<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="panel-body">
						<div class="row mb-lg">
							<div class="col-md-offset-3 col-md-6">
								<div class="form-group">
									<label class="control-label">Status <span class="required">*</span></label>
									<select data-plugin-selectTwo data-width="100%" id="status" name="status" required title="Must Be Required" class="form-control populate">
										<option value="">Select</option>
										<option value="total" '.($status == 'total' ? 'selected' : '').'>Total Challan</option>';
										foreach($payments as $payment){
											if($payment['id'] == $status){
												echo'<option value="'.$payment['id'].'" selected>'.$payment['name'].'</option>';
												}else{
													echo'<option value="'.$payment['id'].'">'.$payment['name'].'</option>';
													}
										}
										echo'
									</select>
								</div>
							</div>
						</div>
						<center>
							<button type="submit" name="view_students" id="view_students" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
						</center>
					</div>
					</form>
				</section>';
				if(isset($_POST['view_students'])){
					echo'
					<section class="panel panel-featured panel-featured-primary appear-animation fadeInRight appear-animation-visible" data-appear-animation="fadeInRight" data-appear-animation-delay="100" style="animation-delay: 100ms;">
						<header class="panel-heading">
							<h2 class="panel-title"> <i class="fa fa-pie-chart"></i> ';
								if($status == 'total'){
									echo 'Total Challan'.' Fee Report';
								}else{
									echo get_payments1($status).' Fee Report';
								}
								echo '
							</h2>
						</header>
						<div class="panel-body">';
							$sqllmsfee	= $dblms->querylms("SELECT f.challan_no, f.total_amount, s.std_name, s.std_phone, s.std_whatsapp, s.std_rollno, s.std_regno, c.class_name, se.session_name, sec.section_name
																FROM ".FEES." f
																INNER JOIN ".STUDENTS." s ON s.std_id = f.id_std
																INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
																INNER JOIN ".CLASS_SECTIONS." sec ON sec.section_id  = s.id_section
																INNER JOIN ".SESSIONS." se ON se.session_id = f.id_session
																WHERE f.is_deleted != '1' ".$sqlStatus."
																AND s.std_status = '1' AND s.is_deleted != '1'
																AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																ORDER BY f.id_class ASC");
							if(mysqli_num_rows($sqllmsfee) > 0){
								echo'
                                <div class="text-right mr-lg on-screen">
                                    <button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
                                    <button id="export_button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</button>
                                </div>
								<div id="printResult">
									<div class="invoice mt-md">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-condensed">
												<thead>
													<tr class="h5 text-dark">
														<th width="80">No#</th>
														<th>Challan No</th>
														<th>Amount</th>
														<th>Name</th>
														<th>Session</th>
														<th>Roll #</th>
														<th>Class</th>
														<th>Section</th>
														<th>Phone</th>
													</tr>
												</thead>
												<tbody>';
													$srno = 0;
													$total_amount = 0;
													while($value_fee = mysqli_fetch_array($sqllmsfee)) {
														$srno++;
														$total_amount = $total_amount + $value_fee['total_amount'];
														echo'
														<tr>
															<td class="center">'.$srno.'</td>
															<td>'.$value_fee['challan_no'].'</td>
															<td>'.$value_fee['total_amount'].'</td>
															<td>'.$value_fee['std_name'].'</td>
															<td>'.$value_fee['session_name'].'</td>
															<td>'.$value_fee['std_rollno'].'</td>
															<td>'.$value_fee['class_name'].'</td>
															<td>'.$value_fee['section_name'].'</td>
															<td>'.$value_fee['std_phone'].'</td>
														</tr>';
													}
													echo'
												</tbody>
											</table>
										</div>
										<div class="invoice-summary">
											<div class="row">
												<div class="col-sm-4 col-sm-offset-8">
													<table class="table h5 text-dark">
														<tbody>
															<tr class="b-top-none">
																<td colspan="2">'.get_payments1($status).' Amount</td>
																<td class="text-left">Rs. '.$total_amount.'</td>
															</tr>
															<tr>
																<td colspan="2"></td>
																<td class="text-left"></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>		
									</div>
								</div>';
							}else{
								echo '<h2 class="center">No Record Found</h2>';
							}
							echo'
						</div>
					</section>';
				}
				echo'
			</div>
		</div>
	</section>';
}else{
    header("Location: dashboard.php");
}
include_once("include/footer.php");
?>
<script type="text/javascript">
	function print_report(printResult) {
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	jQuery(document).ready(function($) {	
		var datatable = $('#table_export').dataTable({
			bAutoWidth : false,
			ordering: false,
		});
	});

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