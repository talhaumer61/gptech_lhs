<?php
// VARIABLES
if($_POST['dated']){
	$dated = date('Y-m-d', strtotime($_POST['dated']));
}else{
	$dated = date('Y-m-d');
}



$sqllms_std	= $dblms->querylms("SELECT s.id_class, s.id_section, c.class_name, cm.principal_sign, cm.campus_name
								FROM ".STUDENTS." s
								INNER JOIN ".CAMPUS." cm ON cm.campus_id = s.id_campus
								INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
								WHERE s.id_loginid = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
								AND   s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
								LIMIT 1");
$values_std = mysqli_fetch_array($sqllms_std);

$sqllmsdetail	= $dblms->querylms("SELECT s.*, d.note
									FROM ".CLASSES." c 
									INNER JOIN ".CLASS_SUBJECTS." s ON s.id_class = c.class_id
									LEFT JOIN ".DIARY." d ON (
										d.id_subject		= s.subject_id
										AND d.id_class		= '".$values_std['id_class']."'
										AND d.id_section	= '".$values_std['id_section']."'
										AND d.dated			= '".$dated."'
										AND d.status		= '1'
										AND d.is_deleted	= '0'
									)
									WHERE c.class_id = '".$values_std['id_class']."' AND s.subject_status = '1' 
									ORDER BY s.subject_id
									");
echo'
<title> Diary Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Diary Panel </h2>
	</header>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-filter"></i> Filter Date</h2>
				</header>
				<div class="panel-body">
					<form action="#" method="post" autocomplete="off">
						<div class="form-group">
							<div class="col-md-4 col-md-offset-4">
								<label class="control-label">Dated <span class="required">*</span></label>
								<input type="text" class="form-control" data-plugin-datepicker name="dated" id="dated" value="'.$_POST['dated'].'" title="Must Be Required" aria-required="true" aria-invalid="false">
							</div>
						</div>
						<div class="form-group center">
							<button type="submit" name="show_result" class="btn btn-primary"><i class="fa fa-search"></i> Get Result</button>
						</div>
					</form>
				</div>
			</section>
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h1 class="panel-title"><i class="fa fa-list"></i> My Diary</h1>
				</header>
				<div class="panel-body">
					<div class="text-right mr-lg on-screen">
						<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
					</div>
					<div id="printResult">
						<style>
							@media print {
								* {
									-webkit-print-color-adjust: exact !important;
									color-adjust: exact !important;
									background: none !important;
									box-shadow: none !important;
								}
								.table-data{
									width: 80%;
									background-image: url("uploads/logo.png") !important;
									background-size: auto 80vh !important;
									background-position:center !important;
									background-repeat:no-repeat !important;
								}
								.table-data thead th{
									background: #333333 !important;
									padding: 5px !important;
									color: #fff !important;
								}
							}
							.title{
								font-weight:bold;
								font-size: 50px;
								color: #333333;
							}
							.border-bottom{
								border-bottom: 1px solid #333333;
							}
							.pr-10{
								padding-right: 5px;
							}
							.pl-10{
								padding-left: 5px;
							}
							td{
								font-size: 12px;
								height: 80px;
							}
							.table-data thead th{
								background: #333333;
								padding:5px;
								color: #fff;
							}
							.table-data{
								width: 80%;
								background-image: url("uploads/logo.png");
								background-size: auto 80vh;
								background-position: center;
								background-repeat:no-repeat;
							}
							.table-data tr{
								background-color: #ffffff99;
							}
							.table-data tr td,.table-data thead tr th{
								border: 2px solid #333333;
							}
							.first td:last-child{
								border-bottom-style: dashed !important;
							}
							.last td{
								border-top-style: dashed !important;
							}
							.middle td{
								border-bottom-style: dashed !important;
								border-top-style: dashed !important;
							}
						</style>
						<div class="page-break">
							<div class="table-responsive" align="center">
								<table width="80%">
									<tr>
										<td class="text-center" colspan="2">
											<h1 class="title">My Diary</h1>
											<h3 class="font-times">Laurel Home International Schools</h3>
											<h4 class="font-times"><span style="text-decoration:underline">'.$values_std['campus_name'].'</span></h4>
										</td>
									</tr>
									<tr>
										<th><h5>Date: <u>'.date('d F, Y', strtotime($dated)).'</u></h5></th>
										<th><h5>Class:<u>'.$values_std['class_name'].'</u></h5></th>
									</tr>
								</table>
								<table class="table table-data table-borderless align-middle"  width="80%" >
									<thead class="table-dark">
										<tr>
											<th width="15%">Subject</th>
											<th>Detail</th>
										</tr>
									</thead>
									<tbody>';
										while($value_detail = mysqli_fetch_array($sqllmsdetail)){
											echo '
											<tr>
												<td class="text-center"><b>'.$value_detail['subject_name'].'</b></td>
												<td>'.$value_detail['note'].'</td>
											</tr>
											';
										}
										echo '
									</tbody>
								</table>
								<table width="80%">
									<body>
										<tr>
											<td></td>
											<td width="220" class="pr-10 pl-10">Signature: <img src="uploads/images/signature/principal/'.$values_std['principal_sign'].'" width="100"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>';
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
				bAutoWidth : false,
				ordering: false,
			});
		});
		
		// SECTION
		$(document).on('change', '#id_class', function() {
			var id_class = $(this).val();
			$.ajax({
				url: "include/ajax/get_section.php",
				type: 'POST',
				data: { 
						id_class: id_class
						},
				success: function(data) {
					$('#id_section').html(data);
				}
			});
		});

		// SECTION
		$(document).on('change', '#id_section', function() {
			var id_section	= $(this).val();
			var id_class	= document.getElementById("id_class").value;
			$.ajax({
				url: "include/ajax/get_classsubject.php",
				type: 'POST',
				data: { 
						 id_section: id_section
						,id_class: id_class
						},
				success: function(data) {
					$('#id_subject').html(data);
				}
			});
		});
		function print_report(printResult){
			var printContents = document.getElementById(printResult).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
	</script>
	<?php
	echo'
</section>
<!-- INCLUDES MODAL -->
<script type="text/javascript">
	function showAjaxModalZoom( url ) {
		// PRELODER SHOW ENABLE / DISABLE
		jQuery( \'#show_modal\' ).html( \'<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>\' );
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax( {
			url: url,
			success: function ( response ) {
				jQuery( \'#show_modal\' ).html( response );
			}
		} );
	}
</script>

<!-- (STYLE AJAX MODAL)-->
<div id="show_modal" class="mfp-with-anim modal-block modal-block-primary mfp-hide"></div>
<script type="text/javascript">
	function confirm_modal( delete_url ) {
		swal( {
			title: "Are you sure?",
			text: "Are you sure that you want to delete this information?",
			type: "warning",
			showCancelButton: true,
			showLoaderOnConfirm: true,
			closeOnConfirm: false,
			confirmButtonText: "Yes, delete it!",
			cancelButtonText: "Cancel",
			confirmButtonColor: "#ec6c62"
		}, function () {
			// location.reload();
			window.location.href = delete_url;
		} );
	}
</script>';
?>