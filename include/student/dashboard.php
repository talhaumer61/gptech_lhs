<?php
echo'
<title> Dashboard | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Dashboard</h2>
	</header>
	<div class="row appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
		include_once("dashboard/profile_detail.php");
		echo'
		<div class="col-md-8">';
			include_once("dashboard/attendance_chart.php");
			include_once("dashboard/exam_marks.php");
			include_once("dashboard/fee_challans.php");
			echo'
		</div>
	</div>';
	include_once("dashboard/event_calendar.php");
	echo'
</section>';
// NOTIFICATION MODAL
$sqllms	= $dblms->querylms("SELECT not_title, dated, not_description
								FROM ".NOTIFICATIONS."
								WHERE not_status = '1' AND id_type = '1' AND is_deleted != '1' AND to_student = '1'
								AND (id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' OR id_campus = '0') ORDER BY not_id desc");
$rowsvalues = mysqli_fetch_array($sqllms);
if($rowsvalues['not_title'] || $rowsvalues['not_description']){
	echo'
	<div class="modal fade col-md-6 col-sm-10" id="myModal" style="position: absolute; left: 50%;top: 35%;transform: translate(-50%, -50%);">
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title">
					<span style="font-size: 30px; line-height: 30px;"><i class="fa fa-bell"></i> '.$rowsvalues['not_title'].'</span>
					<a class="close" data-dismiss="modal"><i class="fa fa-window-close"></i></a>
				</h2>
			</header>
			<div class="panel-body" style="height: 200px; line-height: 30px; padding: 20px; text-align:center; text-align: justify;">
				<h3>'.$rowsvalues['not_description'].'</h3>
			</div>
		</section>
	</div>';
}
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var datatable = $('#table_export').dataTable({
        bAutoWidth: false,
        ordering: false,
    });
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
});

$(window).on('load',function(){
	$('#myModal').modal('show');
});
</script>