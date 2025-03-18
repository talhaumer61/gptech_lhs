<?php

// STUDENTS
$sqllmscampus	= $dblms->querylms("SELECT COUNT(c.campus_id) as total_campus
										FROM ".CAMPUS." c
										WHERE c.campus_status = '1' 
										AND c.is_deleted = '0'
										".(!empty($id_campus) ? "AND c.campus_id IN (".$id_campus.")" : "")."
									");								  
$count_campus = mysqli_fetch_array($sqllmscampus);

// STUDENTS
$sqllmsstudents	= $dblms->querylms("SELECT COUNT(s.std_id) as total
										FROM ".CAMPUS." c
										LEFT JOIN ".STUDENTS." s ON (s.id_campus = c.campus_id AND s.is_deleted = '0')
										WHERE c.campus_id != '' 
										AND c.campus_status = '1' 
										".(!empty($id_campus) ? "AND c.campus_id IN (".$id_campus.")" : "")."
										AND c.is_deleted = '0'
									");								  
$value_std = mysqli_fetch_array($sqllmsstudents);

// EMPLOYEES
$sqllmsemployee	= $dblms->querylms("SELECT COUNT(e.emply_id) as total
										FROM ".CAMPUS." c
										LEFT JOIN ".EMPLOYEES." e ON (e.id_campus = c.campus_id AND e.is_deleted = '0')
										WHERE c.campus_id != '' 
										AND c.campus_status = '1' 
										".(!empty($id_campus) ? "AND c.campus_id  IN (".$id_campus.")" : "")."
										AND c.is_deleted = '0'
									");
$value_emp = mysqli_fetch_array($sqllmsemployee);

// EXAM FUND
$sqlexam = $dblms->querylms("SELECT f.id_examtype
								FROM ".EXAM_FEE_CHALLANS." f
								WHERE f.is_deleted = '0'
								".(!empty($id_campus) ? "AND f.id_campus  IN (".$id_campus.")" : "")."
								ORDER BY f.id DESC LIMIT 1
							");
$valexam = mysqli_fetch_array($sqlexam);

$sqlExamFund	= $dblms->querylms("SELECT f.status, SUM(f.paid_amount) as paid, SUM(f.total_amount) as total
									FROM ".EXAM_FEE_CHALLANS." f
									WHERE f.status IN (1,4)
									AND f.is_deleted	= '0'
									AND f.id_examtype	= '".cleanvars($valexam['id_examtype'])."'
									".(!empty($id_campus) ? "AND f.id_campus  IN (".$id_campus.")" : "")."
									AND f.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
								");
$valExamFund = mysqli_fetch_array($sqlExamFund);
if($valExamFund['paid']){$paidfund = $valExamFund['paid'];}else{$paidfund = 0;}
$ExamReceived 		= $paidfund;
$ExamReceivable 	= ($paidfund-$valExamFund['total']);

// MONTHLY ROYALTY
$sqlRoyaltyFund	= $dblms->querylms("SELECT f.status, SUM(f.paid_amount) as paid, SUM(f.total_amount) as total
								   FROM ".FEES." f
								   WHERE f.id_month	= '".cleanvars(date('m'))."'
								   AND f.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
								   ".(!empty($id_campus) ? "AND f.id_campus  IN (".$id_campus.")" : "")."
								   AND f.status		= '1'
								   AND f.id_type	= '3'
								   AND f.is_deleted	= '0'
								");
$valRoyaltyFund = mysqli_fetch_array($sqlRoyaltyFund);
if($valRoyaltyFund['paid']){$paidroyalty = $valRoyaltyFund['paid'];}else{$paidroyalty = 0;}
$RoyaltyReceived 	= $paidroyalty;
$RoyaltyReceivable 	= ($paidroyalty-$valRoyaltyFund['total']);

echo'
<div class="counter-link">
	<a href="campuses.php" class="col-md-6">
		<section class="panel panel-featured-left panel-featured-secondary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-building"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<div class="col-md-6 mt-xl">
								<h4 class="title">Campus</h4>
								<div class="info"><strong class="amount">'.$count_campus['total_campus'].'</strong></div>
							</div>
							<div class="col-md-3">';
								foreach (get_AreaZone() as $key => $zones) {
									$sqllmscampus	= $dblms->querylms("SELECT COUNT(c.campus_id) as total_zone_campus
																			FROM ".CAMPUS." c
																			WHERE c.campus_status = '1' 
																			AND c.id_zone = ".$key."
																			AND c.is_deleted = '0'
																			".(!empty($id_campus) ? "AND c.campus_id IN (".$id_campus.")" : "")."
																		");								  
									$count_campus = mysqli_fetch_array($sqllmscampus);
									echo'
									<h4 class="title '.(($key%2)==0?'mt-sm':'').'">'.$zones.'</h4>
									<div class="info"><strong class="amount">'.$count_campus['total_zone_campus'].'</strong></div>
									'.(($key%2)==0?'</div><div class="col-md-3">':'').'';
								}
								echo'
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</a>
	<a href="employee.php" class="col-md-6">
		<section class="panel panel-featured-left panel-featured-secondary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-user-o"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<div class="col-md-4 mt-xl">
								<h4 class="title">Employees</h4>
								<div class="info"><strong class="amount">'.$value_emp['total'].'</strong></div>
							</div>
							<div class="col-md-8">
								<div class="row">';
									$sqlemply	= $dblms->querylms("SELECT COUNT(e.emply_id) as total_emply
																		FROM ".EMPLOYEES." e
																		WHERE e.emply_status = '1' 
																		AND e.id_designation = '2'
																		".(!empty($id_campus) ? "AND e.id_campus  IN (".$id_campus.")" : "")."
																		AND e.is_deleted = '0'
																	");								  
									$sqlemply = mysqli_fetch_array($sqlemply);
									echo'
									<div class="col-md-4">
										<h4 class="title">Principal</h4>
										<div class="info"><strong class="amount">'.$sqlemply['total_emply'].'</strong></div>
									</div>';
									$sqlemply	= $dblms->querylms("SELECT COUNT(e.emply_id) as total_emply
																		FROM ".EMPLOYEES." e
																		WHERE e.emply_status = '1' 
																		AND e.id_designation = '6'
																		".(!empty($id_campus) ? "AND e.id_campus  IN (".$id_campus.")" : "")."
																		AND e.is_deleted = '0'
																	");								  
									$sqlemply = mysqli_fetch_array($sqlemply);
									echo'
									<div class="col-md-4">
										<h4 class="title">Teacher</h4>
										<div class="info"><strong class="amount">'.$sqlemply['total_emply'].'</strong></div>
									</div>';
									$sqlemply	= $dblms->querylms("SELECT COUNT(e.emply_id) as total_emply
																		FROM ".EMPLOYEES." e
																		WHERE e.emply_status = '1' 
																		AND e.id_designation NOT IN (2,6)
																		".(!empty($id_campus) ? "AND e.id_campus  IN (".$id_campus.")" : "")."
																		AND e.is_deleted = '0'
																	");								  
									$sqlemply = mysqli_fetch_array($sqlemply);
									echo'
									<div class="col-md-4">
										<h4 class="title">Others</h4>
										<div class="info"><strong class="amount">'.$sqlemply['total_emply'].'</strong></div>
									</div>';
									$sqlemply	= $dblms->querylms("SELECT COUNT(e.emply_id) as total_male_emply
																			FROM ".EMPLOYEES." e
																			WHERE e.emply_status = '1' 
																			AND e.emply_gender = 'Male'
																			".(!empty($id_campus) ? "AND e.id_campus  IN (".$id_campus.")" : "")."
																			AND e.is_deleted = '0'
																		");								  
									$sqlemply = mysqli_fetch_array($sqlemply);
									echo'
									<div class="col-md-4">
										<h4 class="title mt-sm">Male</h4>
										<div class="info"><strong class="amount">'.$sqlemply['total_male_emply'].'</strong></div>
									</div>';
									$sqlemply	= $dblms->querylms("SELECT COUNT(e.emply_id) as total_female_emply
																			FROM ".EMPLOYEES." e
																			WHERE e.emply_status = '1' 
																			AND e.emply_gender = 'Female'
																			".(!empty($id_campus) ? "AND e.id_campus  IN (".$id_campus.")" : "")."
																			AND e.is_deleted = '0'
																		");								  
									$sqlemply = mysqli_fetch_array($sqlemply);
									echo'
									<div class="col-md-4">
										<h4 class="title mt-sm">Female</h4>
										<div class="info"><strong class="amount">'.$sqlemply['total_female_emply'].'</strong></div>
									</div>';
									echo'
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</a>
	<a class="col-md-6">
		<section class="panel panel-featured-left panel-featured-secondary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-dollar"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<div class="col-md-8 mt-xl">
								<h4 class="title">Monthly Royalty '.date('F').'</h4>
								<div class="info"><strong class="amount">Rs. '.number_format($paidroyalty).'.00</strong></div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4 class="title">Principal</h4>
										<div class="info"><strong class="amount">Rs. '.number_format($RoyaltyReceived).'.00</strong></div>
									</div>
									<div class="col-md-12">
										<h4 class="title mt-sm">Teacher</h4>
										<div class="info"><strong class="amount">Rs. '.number_format($RoyaltyReceivable).'.00</strong></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</a>
	<a class="col-md-6">
		<section class="panel panel-featured-left panel-featured-secondary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-dollar"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<div class="col-md-8 mt-xl">
								<h4 class="title">Exam Fund</h4>
								<div class="info"><strong class="amount">Rs. '.number_format($d).'.00</strong></div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-12">
										<h4 class="title">Principal</h4>
										<div class="info"><strong class="amount">Rs. '.number_format($ExamReceived).'.00</strong></div>
									</div>
									<div class="col-md-12">
										<h4 class="title mt-sm">Teacher</h4>
										<div class="info"><strong class="amount">Rs. '.number_format($ExamReceivable).'.00</strong></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</a>
	<a href="students.php" class="col-md-12">
		<section class="panel panel-featured-left panel-featured-secondary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-users"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<div class="col-md-3 mt-xl">
								<h4 class="title">Students</h4>
								<div class="info"><strong class="amount">'.$value_std['total'].'</strong></div>
							</div>
							<div class="col-md-9">
								<div class="row">';
									foreach (get_classlevel() as $key => $levels) {
										$sqllmsstudents	= $dblms->querylms("SELECT COUNT(DISTINCT s.std_id) as total_std_level
																				FROM ".CLASSES." c
																				LEFT JOIN ".STUDENTS." s ON (s.id_class = c.class_id AND s.is_deleted = '0')
																				WHERE c.class_status = '1' 
																				AND c.id_classlevel = ".$key."
																				".(!empty($id_campus) ? "AND s.id_campus  IN (".$id_campus.")" : "")."
																				AND c.is_deleted = '0'
																			");								  
										$value_std = mysqli_fetch_array($sqllmsstudents);
										echo'
										<div class="col-md-4">
											<h4 class="title '.(($key%2)==0?'mt-sm':'').'">'.$levels.'</h4>
											<div class="info"><strong class="amount">'.$value_std['total_std_level'].'</strong></div>
										</div>';
										if (($key%2)==0) {
											$sqllmsstudents	= $dblms->querylms("SELECT COUNT(DISTINCT s.std_id) as total_std_level
																					FROM ".CLASSES." c
																					LEFT JOIN ".STUDENTS." s ON (s.id_class = c.class_id AND s.is_deleted = '0' AND s.std_gender = '".($key==2?'Male':'Female')."')
																					WHERE c.class_status = '1' 
																					".(!empty($id_campus) ? "AND s.id_campus  IN (".$id_campus.")" : "")."
																					AND c.is_deleted = '0'
																				");								  
											$value_std = mysqli_fetch_array($sqllmsstudents);
											echo'
											<div class="col-md-4">
												<h4 class="title '.(($key%2)==0?'mt-sm':'').'">'.($key==2?'Boys':'Girls').'</h4>
												<div class="info"><strong class="amount">'.$value_std['total_std_level'].'</strong></div>
											</div>';
										}
									}
									echo'
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</a>
	
	<!--
	<div class="col-md-12 col-lg-6 col-xl-6">
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
							<div class="info"><strong class="amount">4</strong></div>
						</div>
						<div class="summary-footer">
							<span class="text-muted text-uppercase">total employees</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>		
	<div class="col-md-12 col-lg-6 col-xl-6">
		<section class="panel panel-featured-left panel-featured-secondary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-users"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title">Student</h4>
							<div class="info"><strong class="amount">6</strong></div>
						</div>
						<div class="summary-footer">
							<span class="text-muted text-uppercase">total students</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>		
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
	-->
			
</div>';
?>