<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
include_once("include/header.php");

echo'
<title>Feedback Report | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>CMS Feedback</h2>
	</header>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i> List</h2>
				</header>
				<div class="panel-body">				                               
					<div class="text-right mb-md on-screen">
						<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
					</div>
					<div id="printResult">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th class="center" colspan="9"><h4>CMS Feedback Report</h4></th>
								</tr>
								<tr>
									<th class="center" width="40">Sr.</th>
									<th>Campus Name</th>
									<th>User Name</th>
									<th>User Friendly</th>
									<th>Efficiency</th>
									<th>Meet Needs</th>
									<th>Security Measure</th>
									<th>Percentage</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>';
							$sqllms	= $dblms->querylms("SELECT f.*, c.campus_name, a.adm_fullname
														FROM ".FEEDBACK." f
														LEFT JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
														LEFT JOIN ".ADMINS." a ON a.adm_id = f.id_added
														");
							$srno = 0;
							while($rowsvalues = mysqli_fetch_array($sqllms)) {
								$srno++;
								if($rowsvalues['percentage'] == '1'){
									$percentage = '50%';
								}elseif($rowsvalues['percentage'] == '2'){
									$percentage = '70%';
								}elseif($rowsvalues['percentage'] == '3'){
									$percentage = '90%';
								}elseif($rowsvalues['percentage'] == '4'){
									$percentage = '100%';
								}
								echo'
								<tr>
									<td class="center">'.$srno.'</td>
									<td>'.($rowsvalues['id_campus']=='0' ? 'Headoffice' : $rowsvalues['campus_name']).'</td>
									<td>'.$rowsvalues['adm_fullname'].'</td>
									<td class="center">'.get_statusyesno($rowsvalues['user_friendly']).'</td>
									<td class="center">'.get_statusyesno($rowsvalues['content_efficiency']).'</td>
									<td class="center">'.get_statusyesno($rowsvalues['meet_needs']).'</td>
									<td class="center">'.get_statusyesno($rowsvalues['security_measure']).'</td>
									<td class="center">'.$percentage.'</td>
									<td class="center">'.date('d-m-Y',strtotime($rowsvalues['date_added'])).'</td>
								</tr>
								<tr>
									<th class="center">Remarks</th>
									<td colspan="8">'.$rowsvalues['remarks'].'</td>
								</tr>';
							}
							echo '
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</div>
</section>';
include_once("include/footer.php");
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		<?php 
		if(isset($_SESSION['msg'])) { 
			echo 'new PNotify({
				title	: "'.$_SESSION['msg']['title'].'"	,
				text	: "'.$_SESSION['msg']['text'].'"	,
				type	: "'.$_SESSION['msg']['type'].'"	,
				hide	: true	,
				buttons: {
					closer	: true	,
					sticker	: false
				}
			});';
			unset($_SESSION['msg']);
		}
		?>
		var datatable = $('#table_export').dataTable({
			bAutoWidth: false,
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