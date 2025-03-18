<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
	
	$sqllmscampus  = $dblms->querylms("SELECT * 
										FROM ".CAMPUS." 
										WHERE campus_status = '1' AND campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	$value_campus = mysqli_fetch_array($sqllmscampus);
	
	echo '
	<!doctype html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Fee Challan Form</title>
		<style type="text/css">
			body {
				overflow: -moz-scrollbars-vertical; 
				margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  
			}
			body .btn-primary {
				color: #ffffff;
				text-shadow: 0 -1px 0 rgb(0 0 0 / 25%);
				background-color: #cb3f44;
				border-color: #cb3f44;
			}
			body .btn {
				white-space: normal;
			}
			.ml-sm {
				margin-left: 10px !important;
			}
			.mb-xs {
				margin-bottom: 5px !important;
			}
			.pull-right {
				float: right !important;
			}
			.btn {
				margin-right:20px;
				display: inline-block;
				padding: 6px 12px;
				font-size: 14px;
				font-weight: normal;
				line-height: 1.42857143;
				text-align: center;
				vertical-align: middle;
				touch-action: manipulation;
				cursor: pointer;
				user-select: none;
				background-image: none;
				border: 1px solid transparent;
				border-radius: 4px;
			}
			@media all {
				.page-break	{ display: none; }
			}
			@media print {
				.page-break	{ 
					display: block; 
					page-break-before: always; 
				}
				@page { 
					 
					margin: 4mm 4mm 4mm 4mm; 
				}
				#printPageButton {
					display: none;
				}
			}
			h1 { 
				text-align:left; 
				margin:0; 
				margin-top:0; 
				margin-bottom:0px; 
				font-size:26px; 
				font-weight:700; 
				text-transform:uppercase; 
			}
			.spanh1 { 
				font-size:14px; 
				font-weight:normal; 
				text-transform:none; 
				text-align:right; 
				float:right; 
				margin-top:10px; 
			}
			h2 { 
				text-align:left; 
				margin:0; 
				margin-top:0; 
				margin-bottom:1px; 
				font-size:24px; 
				font-weight:700; 
				text-transform:uppercase; 
			}
			.spanh2 { 
				font-size:20px; 
				font-weight:700; 
				text-transform:none; 
			}
			h3 { 
				text-align:center; 
				margin:0; margin-top:0; 
				margin-bottom:1px; 
				font-size:18px; 
				font-weight:700; 
				text-transform:uppercase; 
			}
			h4 { 
				text-align:center; 
				margin:0; margin-bottom:1px; 
				font-weight:normal; 
				font-size:15px; 
				font-weight:700; 
				word-spacing:0.1em;  
			}
			td { 
				padding-bottom:4px; 
				font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; 
			}
			.line1 { 
				border:1px solid #333; 
				width:100%; 
				margin-top:2px; 
				margin-bottom:5px; 
			}
			.payable { 
				border:2px solid #000; 
				padding:2px; 
				text-align:center; 
				font-size:14px; 
			}
			.paid:after
			{
				content:"PAID";
				position:absolute;
				top:30%;
				left:20%;
				z-index:1;
				font-family:Arial,sans-serif;
				-webkit-transform: rotate(-5deg); /* Safari */
				-moz-transform: rotate(-5deg); /* Firefox */
				-ms-transform: rotate(-5deg); /* IE */
				-o-transform: rotate(-5deg); /* Opera */
				transform: rotate(-5deg);
				font-size:250px;
				color:green;
				background:#fff;
				border:solid 4px yellow;
				padding:5px;
				border-radius:5px;
				zoom:1;
				filter:alpha(opacity=50);
				opacity:0.1;
				-webkit-text-shadow: 0 0 2px #c00;
				text-shadow: 0 0 2px #c00;
				box-shadow: 0 0 2px #c00;
			}
		</style>
		<link rel="shortcut icon" href="images/favicon/favicon.ico">
	</head>

	<body>
		<br>
		<button type="button" id="printPageButton" onClick="window.print();" class="modal-with-move-anim ml-sm mb-xs btn btn-primary btn-xs pull-right">Print</button>';
		//Single Challan Print
		if(isset($_GET['id'])) {
			echo '
			<table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
				<tr>';
					$sqllms  = $dblms->querylms("SELECT *   
												FROM ".FEES." f									
												INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
												INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std	
												LEFT JOIN ".CLASS_SECTIONS." cs ON cs.section_id = st.id_section
												INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
												
												WHERE f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												AND f.challan_no = '".cleanvars($_GET['id'])."' LIMIT 1"
												);
					$feercord = mysqli_fetch_array($sqllms);
					if($feercord['status'] == 1) { 
						$clspaid = " paid";
					} else { 
						$clspaid = "";
					}
					$cpi = 0;
					
					for($ifee = 1; $ifee<=3; $ifee++) { 
						if($ifee<3) { 
							$rightborder = 'style="border-right:1px dashed #333;"';
						} else { 
							$rightborder = '';
						}
						$cpi++;
						
						if($cpi==1) { 
							$copyfor = 'Bank';
						} else if($cpi==2) { 
							$copyfor = 'Account';
						}else if($cpi==3) { 
							$copyfor = "Student's";
						}

						$stdname = preg_replace('/\s+/', ' ', $feercord['std_name']);
						$shortarray = explode(' ',trim($stdname));
						$firstname 	= $shortarray[0];
						$displayname =  $feercord['std_name'];
						echo '
						<td width="341" valign="top" '.$rightborder.' class="'.$clspaid.'">
							<div class="row">
								<h3>
									<img src="uploads/logo.png" style="width:50px; height: auto; text-align: left; vertical-align: middle;">
									<span>
										'.SCHOOL_NAME.' 
										<br>
										<span style="font-size: 14px;">('.$value_campus['campus_name'].') </span>
										<span style="margin-top: -1px;" class="spanh1">'.$copyfor.'</span>
									</span>
								</h3>
								<h4 style="margin-top: 5px;">'.$value_campus['bank_abbreviation'].' Collection Account # '.$value_campus['bank_account_no'].'</h4>
							</div>
							<div class="line1"></div>
							<div style="font-size:13px; margin-top:5px;">
								<table style="border-collapse:collapse;" width="100%" border="0">
									<tr>
										<td style="text-align:left; width:75px;">Challan #:</td>
										<td style= text-align:left; width:150px;"><span style="width:90px;display:inline-block; overflow:hidden; border-bottom:1px solid;">'.$feercord['challan_no'].'</span></td>
										<td style="text-align:left;width:70px;">Issue Date:</td>
										<td style="text-align:left; text-decoration:underline;">'.$feercord['issue_date'].'</td>
									</tr>
									<tr>
										<td style="text-align:left;">Reg #:</td>
										<td style="text-align:left;"><span style="font-size:10px;"><u>'.$feercord['std_regno'].'</u></span></td>
										<td style="text-align:left;">Due Date:</td>
										<td style=" text-align:left; text-decoration:underline;">'.$feercord['due_date'].'</td>	
									</tr>
									<tr>
										<td style="text-align:left;">Name:</td>
										<td style="text-align:left; text-decoration:underline;">'.$feercord['std_name'].'</td>
										<td style="text-align:left;">Father:</td>
										<td style="text-align:left; text-decoration:underline;">'.$feercord['std_fathername'].'</td>
									</tr>
									<tr>
										<td style="text-align:left;">Class:</td>
										<td style="text-align:left; text-decoration:underline;">'.$feercord['class_name'].'</td>
										<td style="text-align:left;">Section:</td>
										<td style="text-align:left; text-decoration:underline;">'.$feercord['section_name'].'</td>
									</tr>
									<tr>
										<td style="text-align:left;">Roll No:</td>
										<td style=" text-align:left; text-decoration:underline;">'.$feercord['std_rollno'].'</td>
										<td style="text-align:left;">Session</td>
										<td style=" text-align:left;  text-decoration:underline;">'.$feercord['session_name'].'</td>
									</tr>
								</table>
							</div>
							<div style="font-size:12px; margin-top:5px;">
								<table style="border-collapse:collapse; border:1px solid #666;" cellpadding="2" cellspacing="2" border="1" width="100%">
									<tr>
										<td style="text-align:center; font-size:12px; font-weight:bold;"></td>
										<td style="text-align:right; font-size:12px; font-weight:bold;">Rs.</td>
									</tr>';
									$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
																		FROM ".FEE_CATEGORY."
																		WHERE cat_status = '1' 
																		ORDER BY cat_id ASC");
									$countcats 	= mysqli_num_rows($sqllmscats);
									
									if($countcats >0) {
										$src = 0;
										while($rowdoc 	=	mysqli_fetch_array($sqllmscats)) {
											$src++;
											$sqllmsfeeprt  = $dblms->querylms("SELECT id_cat, amount FROM ".FEE_PARTICULARS." 
																				WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$feercord['id']."' 
																				LIMIT 1");
											if(mysqli_num_rows($sqllmsfeeprt)>0) { 
												$valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
												$remarks = '';
												echo '
												<tr>
													<td>'.$rowdoc['cat_name'].$remarks.'</td>
													<td style="text-align:right; width:45%;">'.number_format($valuefeeprt['amount']).'</td>
												</tr>';
												
											} else { 
												echo '
												<tr>
													<td>'.$rowdoc['cat_name'].'</td>
													<td style="text-align:right; width:45%;"></td>
												</tr>';
											
											}
											
										}
										
									}
					
									echo'
									<tr>
										<td>Fine</td>
										<td style="text-align:right; width:45%;">'.number_format($feercord['fine']).'</td>
									</tr>
									<tr>
										<td>Prevoius Balance</td>
										<td style="text-align:right; width:45%;">'.number_format($feercord['prev_remaining_amount']).'</td>
									</tr>
									<tr>
										<td style="text-align:left; font-size:12px; font-weight:bold; border:2px solid #333;">Grand Total</td>
										<td style="text-align:right; font-size:12px; font-weight:bold;  border:2px solid #333;">'.number_format($feercord['total_amount']).'</td>
									</tr>
								</table>';
								if($_SESSION['userlogininfo']['LOGINAFOR'] != 3) { 
									echo '<span style="font-size:9px;">issue by: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>';
								}
								echo '
								<span style="font-size:9px; float:right; margin-top:3px;">issue Date: '.date("m/d/Y").'</span>
							</div>
							<div style="clear:both;"></div>
							<div style="font-size:13px; color:#000; margin-top:20px;">
								<table width="100%" border="0" style="border-collapse:collapse;" cellpadding="0" cellspacing="5">
									<tr>
										<td style="font-weight:normal; font-style:italic; text-align:left; font-size:11px; width:80%;">Rupees in word: <span style="text-decoration:underline; font-size:9px; color:#000;">'.convert_number_to_words($feercord['total_amount']).' only</span>
										</td>
										<td style="font-weight:normal; font-style:italic; text-align:right;">Cashier</td>
									</tr>
									<tr>
										<td style="font-weight:normal; font-style:italic; color: #777777; text-align:left; font-size:9px; width:80%;"><b>Cashier Note: </b>
											<ol type="1">
												<li>Only Cash & Cheque/Payorder will be accepted.</li>
												<li>After Due Date student will pay fine PKR 10/, of each day.</li>
											</ol>
										</td>
									</tr>

								</table>
							</div>
						</td>';
					}
					echo '
				</tr>
			</table>';
		}
		//End Single Challan Print
		//Monthly Challan Print
		if(isset($_POST['id_month']) && isset($_POST['id_session'])) {
			if($_POST['id_month'] <= 9){
				$challanIn = date('Y').'0'.$_POST['id_month'];
			} else{
				$challanIn = date('Y').$_POST['id_month'];
			}
			$sqllms  = $dblms->querylms("SELECT *   
											FROM ".FEES." f									
											INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
											
											INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
											INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std	
											INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = st.id_section
											WHERE f.status !='1' AND f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
											AND f.id_session	= '".cleanvars($_POST['id_session'])."'
											AND ( f.id_month = '".cleanvars($_POST['id_month'])."' OR f.challan_no LIKE '%".$challanIn."%' ) 
										");
			while($feercord = mysqli_fetch_array($sqllms)){
				echo '
				<table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
					<tr>';
						if($feercord['status'] == 1) { 
							$clspaid = " paid";
						} else { 
							$clspaid = "";
						}
						$cpi = 0;
						
						for($ifee = 1; $ifee<=3; $ifee++) { 
							if($ifee<3) { 
								$rightborder = 'style="border-right:1px dashed #333;"';
							} else { 
								$rightborder = '';
							}
							$cpi++;
							
							if($cpi==1) { 
								$copyfor = 'Bank';
							} else if($cpi==2) { 
								$copyfor = 'Account';
							}else if($cpi==3) { 
								$copyfor = "Student's";
							}

							$stdname = preg_replace('/\s+/', ' ', $feercord['std_name']);
							$shortarray = explode(' ',trim($stdname));
							$firstname 	= $shortarray[0];
							$displayname =  $feercord['std_name'];
							echo '
							<td width="341" valign="top" '.$rightborder.' class="'.$clspaid.'">
								<div class="row">
									<h3>
										<img src="uploads/logo.png" style="width:50px; height: auto; text-align: left; vertical-align: middle;">
										<span>
											'.SCHOOL_NAME.' 
											<br>
											<span style="font-size: 14px;">('.$value_campus['campus_name'].') </span>
											<span style="margin-top: -1px;" class="spanh1">'.$copyfor.'</span>
										</span>
									</h3>
									<h4 style="margin-top: 5px;">'.$value_campus['bank_abbreviation'].' Collection Account # '.$value_campus['bank_account_no'].'</h4>
								</div>
								<div class="line1"></div>
								<div style="font-size:13px; margin-top:5px;">
									<table style="border-collapse:collapse;" width="100%" border="0">
										<tr>
											<td style="text-align:left; width:75px;">Challan #:</td>
											<td style= text-align:left; width:150px;"><span style="width:90px;display:inline-block; overflow:hidden; border-bottom:1px solid;">'.$feercord['challan_no'].'</span></td>
											<td style="text-align:left;width:70px;">Issue Date:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['issue_date'].'</td>
										</tr>
										<tr>
											<td style="text-align:left;">Reg #:</td>
											<td style="text-align:left;"><span style="font-size:10px;"><u>'.$feercord['std_regno'].'</u></span></td>
											<td style="text-align:left;">Due Date:</td>
											<td style=" text-align:left; text-decoration:underline;">'.$feercord['due_date'].'</td>	
										</tr>
										<tr>
											<td style="text-align:left;">Name:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['std_name'].'</td>
											<td style="text-align:left;">Father:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['std_fathername'].'</td>
										</tr>
										<tr>
											<td style="text-align:left;">Class:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['class_name'].'</td>
											<td style="text-align:left;">Section:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['section_name'].'</td>
										</tr>
										<tr>
											<td style="text-align:left;">Roll No:</td>
											<td style=" text-align:left; text-decoration:underline;">'.$feercord['std_rollno'].'</td>
											<td style="text-align:left;">Session</td>
											<td style=" text-align:left;  text-decoration:underline;">'.$feercord['session_name'].'</td>
										</tr>
									</table>
								</div>
								<div style="font-size:12px; margin-top:5px;">
									<table style="border-collapse:collapse; border:1px solid #666;" cellpadding="2" cellspacing="2" border="1" width="100%">
										<tr>
											<td style="text-align:center; font-size:12px; font-weight:bold;"></td>
											<td style="text-align:right; font-size:12px; font-weight:bold;">Rs.</td>
										</tr>';
										$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
																			FROM ".FEE_CATEGORY."
																			WHERE cat_status = '1' 
																			ORDER BY cat_id ASC");
										$countcats 	= mysqli_num_rows($sqllmscats);
										if($countcats >0) {
											$src = 0;
											while($rowdoc 	= mysqli_fetch_array($sqllmscats)) {
												$src++;
												$sqllmsfeeprt  = $dblms->querylms("SELECT id_cat, amount FROM ".FEE_PARTICULARS." 
																					WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$feercord['id']."' 
																					LIMIT 1");
												if(mysqli_num_rows($sqllmsfeeprt)>0) { 
													$valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
													$remarks = '';
													
													echo '
													<tr>
														<td>'.$rowdoc['cat_name'].$remarks.'</td>
														<td style="text-align:right; width:45%;">'.number_format($valuefeeprt['amount']).'</td>
													</tr>';
												} else { 
													
													echo '
													<tr>
														<td>'.$rowdoc['cat_name'].'</td>
														<td style="text-align:right; width:45%;"></td>
													</tr>';
												}
											}
										}
										
										echo '
										<tr>
											<td>Concession</td>
											<td style="text-align:right; width:45%;">'.number_format($feercord['scholarship']).'</td>
										</tr>
										<tr>
											<td>Scholarship</td>
											<td style="text-align:right; width:45%;">'.number_format($feercord['concession']).'</td>
										</tr>
										<tr>
											<td>Fine</td>
											<td style="text-align:right; width:45%;">'.number_format($feercord['fine']).'</td>
										</tr>
										<tr>
											<td>Prevoius Balance</td>
											<td style="text-align:right; width:45%;">'.number_format($feercord['prev_remaining_amount']).'</td>
										</tr>
										<tr>
											<td style="text-align:left; font-size:12px; font-weight:bold; border:2px solid #333;">Grand Total</td>
											<td style="text-align:right; font-size:12px; font-weight:bold;  border:2px solid #333;">'.number_format($feercord['total_amount']).'</td>
										</tr>
									</table>';
									if($_SESSION['userlogininfo']['LOGINAFOR'] != 3) { 
										echo '<span style="font-size:9px;">issue by: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>';
									}
									echo '
									<span style="font-size:9px; float:right; margin-top:3px;">issue Date: '.date("m/d/Y").'</span>
								</div>
								<div style="clear:both;"></div>
								<div style="font-size:13px; color:#000; margin-top:20px;">
									<table width="100%" border="0" style="border-collapse:collapse;" cellpadding="0" cellspacing="5">
										<tr>
											<td style="font-weight:normal; font-style:italic; text-align:left; font-size:11px; width:80%;">Rupees in word: <span style="text-decoration:underline; font-size:9px; color:#000;">'.convert_number_to_words($feercord['total_amount']).' only</span>
											</td>
											<td style="font-weight:normal; font-style:italic; text-align:right;">Cashier</td>
										</tr>
									</table>
								</div>
							</td>';
						}
						echo '
					</tr>
				</table>
				<div class="page-break"></div>';
						
			}
		}
		//End Monthly Challan Print
		echo '
	</body>
	</html>';
?>