<?php
$sqlFeedback  	= $dblms->querylms("SELECT * FROM ".FEEDBACK."
									WHERE id_added = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
									AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
									ORDER BY id DESC LIMIT 1");
if(mysqli_num_rows($sqlFeedback)==0){	
	$_SESSION['msg']['title'] 	= 'Message';
	$_SESSION['msg']['text'] 	= 'Feedback already shared';
	$_SESSION['msg']['type'] 	= 'info';
	header("Location: feedback.php", true, 301);
}else{
echo'
<title> Dashboard | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Admin Panel</h2>
	</header>
    <div class="row">';
		include "dashboard/modal.php";
		include "dashboard/button_links.php";
		include "dashboard/main_counter.php";
		// include "dashboard/list_today_birthday.php";
		include "dashboard/event_calendar.php";
		// include "dashboard/studentAttendancegraph.php";
		// include "dashboard/employeeAttendancegraph.php";
		// include "dashboard/financegraph.php";
		// include "dashboard/classwisestudents.php";
		// include "dashboard/catwisestudents.php";
		// include "dashboard/calculation.php";
    	echo'
    </div>
</section>';
}
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
	});
</script>