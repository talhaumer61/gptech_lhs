<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'edit' => '1'))){
//-----------------------------------------------
	
if(isset($_GET['id']))
{
echo '
<section role="main" class="content-body">
	<!-- INCLUDEING PAGE -->
	<div class="row appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
		//-----------------------------------------------
		include_once("profile/detail.php");
		//-----------------------------------------------
		echo '
		<div class="col-md-8">
			<div class="tabs tabs-primary">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#edit" data-toggle="tab"><i class="fa fa-user"></i> <span class="hidden-xs"> Profile</span></a>
					</li>
					<li>
						<a href="#feechallans" data-toggle="tab"><i class="fa fa-money"></i> <span class="hidden-xs"> Fee Challans</span></a>
					</li>
					<li>
						<a href="#attendance" data-toggle="tab"><i class="fa fa-pie-chart"></i> <span class="hidden-xs"> Attendance</span></a>
					</li>
					<li>
						<a href="#resetpass" data-toggle="tab"><i class="fa fa-lock"></i> <span class="hidden-xs"> Change Password</span></a>
					</li>
				</ul>
				<div class="tab-content">';
					//-----------------------------------------------
					include_once("profile/edit_profile.php");
					include_once("profile/fee_challans.php");
					include_once("profile/attendance.php");
					// include_once("profile/bank_details.php");
					include_once("profile/change_password.php");
					//-----------------------------------------------
					echo'
				</div>
			</div>
		</div>
	</div>
</section>';
//-----------------------------------------------
}
}
else{
	header("Location: students.php");
}
?>