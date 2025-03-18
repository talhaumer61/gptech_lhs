<?php
// CURRENT MONTH
$month 		= date('m');
$today 		= date('Y-m-d');
$todayDate	= date('m-d');
$tomorrow	= date ("m-d", strtotime("+1 day", strtotime($today)));

// STUDENTS
$sqllmsstudents	= $dblms->querylms("SELECT COUNT(std_id) as total
										FROM ".STUDENTS."
										WHERE id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
										AND std_status	= '1' 
										AND is_deleted	= '0'
									");								  
									// AND id_session		= '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
$value_std = mysqli_fetch_array($sqllmsstudents);

// STUDENT ATTENDANCE
$sqlStdAtnd	= $dblms->querylms("SELECT
									COUNT(CASE WHEN ad.status = '1' THEN ad.id ELSE NULL END) as stdPresent,
									COUNT(CASE WHEN ad.status = '2' THEN ad.id ELSE NULL END) as stdAbsent,
									COUNT(CASE WHEN ad.status = '3' THEN ad.id ELSE NULL END) as stdLeave,
									COUNT(CASE WHEN ad.status = '4' THEN ad.id ELSE NULL END) as stdLate
									FROM ".STUDENT_ATTENDANCE." a
									INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." ad ON ad.id_setup = a.id
									WHERE a.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
									AND a.dated			= '".cleanvars($today)."' 
									AND a.status		= '1' 
									AND a.is_deleted	= '0'
								  ");
$valStdAtnd = mysqli_fetch_array($sqlStdAtnd);

// EMPLOYEE ATTENDANCE
$sqlEmpAtnd	= $dblms->querylms("SELECT
									COUNT(CASE WHEN ad.status = '1' THEN ad.id ELSE NULL END) as empPresent,
									COUNT(CASE WHEN ad.status = '2' THEN ad.id ELSE NULL END) as empAbsent,
									COUNT(CASE WHEN ad.status = '3' THEN ad.id ELSE NULL END) as empLeave,
									COUNT(CASE WHEN ad.status = '4' THEN ad.id ELSE NULL END) as empLate
									FROM ".EMPLOYEES_ATTENDCE." a
									INNER JOIN ".EMPLOYEES_ATTENDCE_DETAIL." ad ON ad.id_setup = a.id
									WHERE a.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
									AND a.dated			= '".cleanvars($today)."'
									AND a.status		= '1'
									AND a.is_deleted	= '0'
								  ");
$valEmpAtnd = mysqli_fetch_array($sqlEmpAtnd);

// EMPLOYEES
$sqllmsemployee	= $dblms->querylms("SELECT COUNT(emply_id) as total
									FROM ".EMPLOYEES."
									WHERE id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
									AND emply_status	= '1' 
									AND is_deleted		= '0'
								  ");
$value_emp = mysqli_fetch_array($sqllmsemployee);

// TODAY COLLECTION
$sqlAmount	= $dblms->querylms("SELECT SUM(f.paid_amount) as paid, SUM(f.total_amount) as total
								FROM ".FEES." f
								WHERE f.paid_date	= '".cleanvars($today)."'
								AND f.id_month		= '".cleanvars($month)."'
								AND f.id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
								AND f.is_deleted	= '0'
							");
$valAmount = mysqli_fetch_array($sqlAmount);

$paid_amount	= 0;
$total_amount 	= 0;
$paid_percent = 0;

if(isset($valAmount['total'])){ $total_amount = $valAmount['total']; }else{ $total_amount = 0; }
if(isset($valAmount['paid'])){ $paid_amount = $valAmount['paid']; }else{ $paid_amount = 0; }

if ($paid_amount != 0 && $total_amount != 0)
{
	$paid_percent = ($paid_amount / $total_amount) * 100;	
}
else
{
	$paid_percent = 0;
}


// PAID SALARIES
$sqlPaidSal	= $dblms->querylms("SELECT SUM(net_pay) as paidSal
								FROM ".SALARY."
								WHERE status	= '1'
								AND month		= '".cleanvars($month)."'
								AND id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
							");
$valPaidSal = mysqli_fetch_array($sqlPaidSal);
if($valPaidSal['paidSal']){$paidSal = $valPaidSal['paidSal'];}else{$paidSal = 0;}

// PAID AMOUNT
$sqllmspaid	= $dblms->querylms("SELECT SUM(f.paid_amount) as paid
								FROM ".FEES." f
								WHERE f.status IN (1,4) 
								AND f.id_type IN (1,2)
								AND f.id_month		= '".cleanvars($month)."'
								AND f.id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
								AND f.is_deleted	= '0'
							");
$value_paid = mysqli_fetch_array($sqllmspaid);
if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}

// PENDING AMOUNT
$sqllmspending	= $dblms->querylms("SELECT SUM(f.paid_amount) as paid,
									SUM(
										(case when f.due_date > '".date('Y-m-d')."' then f.total_amount
										else f.total_amount + '".LATEFEE."'
										end)
									) as total
									FROM ".FEES." f
									WHERE f.status IN (2,4)
									AND f.id_type IN (1,2)
									AND f.id_month		= '".cleanvars($month)."'
									AND f.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
									AND f.is_deleted	= '0'
								");
$value_pending = mysqli_fetch_array($sqllmspending);
$TotalPending = $value_pending['total'] - $value_pending['paid'];
if($TotalPending){$pending = $TotalPending;}else{$pending = 0;}

// TOTAL AMOUNT
$totalreceivable = $pending + $paid;

// BIRTHDAYS
$sqlBdayStd	= $dblms->querylms("SELECT
								COUNT(CASE WHEN s.std_dob LIKE '%".$todayDate."' THEN s.std_id ELSE NULL END) as today,
								COUNT(CASE WHEN s.std_dob LIKE '%".$tomorrow."' THEN s.std_id ELSE NULL END) as tomorrow
								FROM ".STUDENTS." s
								WHERE s.std_id != '' AND s.is_deleted != '1' AND s.std_status = '1'
								AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'");
$valBdayStd = mysqli_fetch_array($sqlBdayStd);

$sqlBdayEmp	= $dblms->querylms("SELECT
								COUNT(CASE WHEN e.emply_dob LIKE '%".$todayDate."' THEN e.emply_id ELSE NULL END) as today,
								COUNT(CASE WHEN e.emply_dob LIKE '%".$tomorrow."' THEN e.emply_id ELSE NULL END) as tomorrow
								FROM ".EMPLOYEES." e
								WHERE e.emply_id != '' AND e.is_deleted != '1' AND e.emply_status = '1'
								AND e.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'");
$valBdayEmp = mysqli_fetch_array($sqlBdayEmp);

echo'
<div class="counter-link">
	<a href="reportlistprint.php?type=3" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-money"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Fee Expected ('.date('F', strtotime($today)).')</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> Rs. '.$totalreceivable.'</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="reportlistprint.php?type=2" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-star"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Fee Received ('.date('F', strtotime($today)).')</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> Rs. '.$paid.'</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="reportlistprint.php?type=1" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-refresh"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Fee Receivable ('.date('F', strtotime($today)).')</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> Rs. '.$pending.'</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="reportlistprint.php?type=4" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-star"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Today Collection ('.date('F', strtotime($today)).')</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> Rs. '.(isset($valAmount['paid']) ? $valAmount['paid'] : 0).'</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="attendance_report.php?type=std" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-line-chart"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Today Student Attendance</b></h4>
								<div class="info">
									<h4 class="title">
										<b><span>Present</span> <span class="text-primary">('.$valStdAtnd['stdPresent'].')</span></b>
										<b><span>Absent</span> <span class="text-primary">('.$valStdAtnd['stdAbsent'].')</span></b>
										<b><span>Leave</span> <span class="text-primary">('.$valStdAtnd['stdLeave'].')</span></b>
										<!--<b><span>Late</span> <span class="text-primary">('.$valStdAtnd['stdLate'].')</span></b>-->
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="attendance_report.php?type=emp" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-line-chart"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Today Staff Attendance</b></h4>
								<div class="info">
									<h4 class="title">
										<b><span>Present</span> <span class="text-primary">('.$valEmpAtnd['empPresent'].')</span></b>
										<b><span>Absent</span> <span class="text-primary">('.$valEmpAtnd['empAbsent'].')</span></b>
										<b><span>Leave</span> <span class="text-primary">('.$valEmpAtnd['empLeave'].')</span></b>
										<!--<b><span>Late</span> <span class="text-primary">('.$valEmpAtnd['empLate'].')</span></b>-->
									</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="students.php">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-users"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Total Students</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> '.$value_std['total'].'</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="employee.php">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-user-o"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Total Employees</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> '.$value_emp['total'].'</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<a href="reportlistprint.php?salary=1" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-money"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Paid Salary ('.date('F', strtotime($today)).')</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> Rs. '.$paidSal.'</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>
	<div class="col-md-4">
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary widget-summary-sm">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-birthday-cake"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><b>Students Birthdays</b></h4>
							<div class="info">
								<h4 class="title">
									<a href="birthday_report.php?view=std&date='.$todayDate.'" target="_blank" class="text-dark">
										<b><span>Today</span> <span class="text-primary">('.$valBdayStd['today'].')</span></b>
									</a>
									<a href="birthday_report.php?view=std&date='.$tomorrow.'" target="_blank" class="text-dark">
										<b><span>Tomorrow</span> <span class="text-primary">('.$valBdayStd['tomorrow'].')</span></b>
									</a>
								</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-4">
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary widget-summary-sm">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-birthday-cake"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title"><b>Employees Birthdays</b></h4>
							<div class="info">
								<h4 class="title">
									<a href="birthday_report.php?view=emp&date='.$todayDate.'" target="_blank" class="text-dark">
										<b><span>Today</span> <span class="text-primary">('.$valBdayEmp['today'].')</span></b>
									</a>
									<a href="birthday_report.php?view=emp&date='.$tomorrow.'" target="_blank" class="text-dark">
										<b><span>Tomorrow</span> <span class="text-primary">('.$valBdayEmp['tomorrow'].')</span></b>
									</a>
								</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<a href="reportlistprint.php?events=1" target="_blank">
		<div class="col-md-4">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary widget-summary-sm">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="fa fa-money"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title"><b>Upcoming Events</b></h4>
								<div class="info">
									<strong class="amount text-primary text-uppercase"> Print Report</strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</a>


	<!--
	<a href="reportlistprint.php?type=3" target="_blank">
		<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
			<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #2ed8b6, #59e0c5); border-radius:4px;">
				<div class="card-body w_sparkline p-lg">
					<div class="details">
						<span><b>Fee Expected ('.date('M', strtotime($today)).')</b></span>
						<h3 class="mt-none mb-none counter"> Rs. '.$totalreceivable.' </h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-money"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="reportlistprint.php?type=2" target="_blank">
		<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
			<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #268d1f, #87d82e); border-radius:4px;">
				<div class="card-body w_sparkline p-lg">
					<div class="details">
						<span><b>Fee Received ('.date('M', strtotime($today)).')</b></span>
						<h3 class="mt-none mb-none counter"> Rs. '.$paid.' </h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-star"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="reportlistprint.php?type=1" target="_blank">
		<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
			<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #d58a1e, #ff9600); border-radius:4px;">
				<div class="card-body w_sparkline p-lg">
					<div class="details">
						<span><b>Fee Receivable ('.date('M', strtotime($today)).')</b></span>
						<h3 class="mt-none mb-none counter"> Rs. '.$pending.' </h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-refresh"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
		<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #268d1f, #87d82e); border-radius:4px;">
			<div class="card-body w_sparkline p-lg">
				<div class="details">
					<span><b>Today Student Attendance</b></span>
					<br>
					<span><b>Present / Absent / Late</b></span>
					<h4	class="mt-none mb-none counter"> '.$valBdayStd['stds'].' / '.$valStdAtnd['stdAbsent'].' / '.$valStdAtnd['stdLate'].'</h4>					
				</div>
				<div class="counter-box">
					<i class="fa fa-line-chart"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
		<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #d58a1e, #ff9600); border-radius:4px;">
			<div class="card-body w_sparkline p-lg">
				<div class="details">
					<span><b>Today Staff Attendance</b></span>
					<br>
					<span><b>Present / Absent / Late</b></span>
					<h4 class="mt-none mb-none counter"> '.$valEmpAtnd['empPresent'].' / '.$valEmpAtnd['empAbsent'].' / '.$valEmpAtnd['empLate'].'</h4>		
				</div>
				<div class="counter-box">
					<i class="fa fa-line-chart"></i>
				</div>
			</div>
		</div>
	</div>
	<a href="reportlistprint.php?type=4" target="_blank">
		<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
			<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #2ed8b6, #59e0c5); border-radius:4px;">
				<div class="card-body w_sparkline p-lg">
					<div class="details">
						<span><b>Today Collection</b></span>
						<h3 class="mt-none mb-none counter">Rs. '.$valAmount['paid'].'</h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-star"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="students.php">
		<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
			<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #d58a1e, #ff9600); border-radius:4px;">
				<div class="card-body w_sparkline p-lg">
					<div class="details">
						<span><b>Total Students</b></span>
						<h3 class="mt-none mb-none counter"> '.$value_std['total'].' </h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-users"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="employee.php">
		<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
			<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #2ed8b6, #59e0c5); border-radius:4px;">
				<div class="card-body w_sparkline p-lg">
					<div class="details">
						<span><b>Total Employees</b></span>
						<h3 class="mt-none mb-none counter"> '.$value_emp['total'].' </h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-user-o"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="reportlistprint.php?salary=1" target="_blank">
		<div class="col-lg-4 col-md-6 col-sm-6 mb-md">
			<div class="card panel-featured-left panel-featured-secondary" style="background: linear-gradient(45deg, #268d1f, #87d82e); border-radius:4px;">
				<div class="card-body w_sparkline p-lg">
					<div class="details">
						<span><b>Paid Salaries</b></span>
						<h3 class="mt-none mb-none counter"> Rs. '.$paidSal.' </h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-money"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	-->
</div>

<!--
<div class="col-md-6 col-lg-12 col-xl-6">
	<div class="row">
		<a href="students.php" class="col-md-12 col-lg-6 col-xl-6">
			<section class="panel panel-featured-left panel-featured-secondary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-secondary">
								<i class="fa fa-users"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title">Student</h4>
								<div class="info"><strong class="amount">'.$value_std['total'].'</strong></div>
							</div>
							<div class="summary-footer">
								<span class="text-muted text-uppercase">total students</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</a>	
		<a href="employee.php" class="col-md-12 col-lg-6 col-xl-6">
			<section class="panel panel-featured-left panel-featured-primary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-primary">
								<i class="glyphicon glyphicon-user"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title">Employees</h4>
								<div class="info"><strong class="amount">'.$value_emp['total'].'</strong></div>
							</div>
							<div class="summary-footer">
								<span class="text-muted text-uppercase">total employees</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</a>
		<div class="col-md-12 col-lg-6 col-xl-6">
			<section class="panel panel-featured-left panel-featured-tertiary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-tertiary">
								<i class="fa fa-snowflake-o"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title">Leave Application</h4>
								<div class="info"><strong class="amount">1</strong></div>
							</div>
							<div class="summary-footer">
								<span class="text-muted text-uppercase">total pending</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>		
		<div class="col-md-12 col-lg-6 col-xl-6">
			<section class="panel panel-featured-left panel-featured-quaternary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-quaternary">
								<i class="fa fa-credit-card"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title">Invoice</h4>
								<div class="info"><strong class="amount">0</strong></div>
							</div>
							<div class="summary-footer">
								<span class="text-muted text-uppercase">today payments</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
-->';
?>