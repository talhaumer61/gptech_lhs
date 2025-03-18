<?php
// Bulk Fee Challans Genrate
if(isset($_POST['bulk_challans_generate'])) { 

	if(COUNT($_POST['is_selected']) <= 0) {
		// Failed
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'No fee head selected';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: fee_challans.php", true, 301);
		exit();
	} else {
		
		$genratedChallans = 0;
		// Reformat Date
		$challandate	= date('Ym');
		$issue_date 	= date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'])));
		$due_date 		= date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
		$yearmonth 		= date('Y-m', strtotime(cleanvars($_POST['yearmonth'])));

		$sqllmsstudent	= $dblms->querylms("SELECT std_id, transport_fee
											FROM ".STUDENTS."
											WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
											AND id_class = '".cleanvars($_POST['id_class'])."'
											AND std_status = '1' AND is_deleted != '1'
											ORDER BY std_id ASC");
		$no = 0;
		while($value_std = mysqli_fetch_array($sqllmsstudent)) {
			// for($s=1; $s<= count($_POST['id_std']); $s++){

			// If Challan Not Exsist Then Genrate
			$sqllmscheck  = $dblms->querylms("SELECT id_std
												FROM ".FEES." 
												WHERE id_std	= '".cleanvars($value_std['std_id'])."'
												AND id_month	= '".cleanvars($_POST['id_month'])."'
												AND yearmonth	= '".cleanvars($yearmonth)."'
												AND is_deleted	= '0'
												AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
											");	
			if(mysqli_num_rows($sqllmscheck) == 0)
			{
				// Challan Number
				$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".FEES." 
															WHERE challan_no LIKE '".$challandate."%'  
															ORDER by challan_no DESC LIMIT 1 ");
				$rowchallan 	= mysqli_fetch_array($sqllmschallan);
				if(mysqli_num_rows($sqllmschallan) < 1) {
					$challano	= $challandate.'00001';
				} else  {
					$challano = ($rowchallan['challan_no'] +1);
				}

				// Fine
				$month = $_POST['id_month'] - 1;
				$sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
													FROM ".SCHOLARSHIP." 
													WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
													AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
													AND id_type = '3' AND status = '1' AND is_deleted != '1'
													AND id_std = '".cleanvars($value_std['std_id'])."' AND challan_no = ''
													AND  MONTH(date) IN ('".$month."', '".$_POST['id_month']."') ");
				$values_fine = 	mysqli_fetch_array($sql_fine);

				// Prev Challan Remaining Amount
				$sqllms_rem = $dblms->querylms("SELECT remaining_amount 
														FROM ".FEES." 
														WHERE id_std = '".$value_std['std_id']."'
														AND is_deleted != '1'
														ORDER BY id DESC LIMIT 1");
				$val_rem = mysqli_fetch_array($sqllms_rem);
				
				// Challan Genrate
				$sqllms  = $dblms->querylms("INSERT INTO ".FEES."(
																	status						, 
																	id_type						,
																	challan_no					, 
																	id_session					, 
																	id_month					,
																	yearmonth					,
																	id_class					, 
																	id_section					,
																	id_std						,
																	issue_date					,
																	due_date					,
																	scholarship					,
																	concession					,
																	fine						,
																	prev_remaining_amount		,
																	note						, 
																	id_campus 					,
																	id_added					,
																	date_added		
																) VALUES (
																	'2'																,
																	'2'																,
																	'".cleanvars($challano)."'										,
																	'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
																	'".cleanvars($_POST['id_month'])."'								,
																	'".cleanvars($yearmonth)."'										,
																	'".cleanvars($_POST['id_class'])."'								,
																	'".cleanvars($_POST['id_section'])."'							,
																	'".cleanvars($value_std['std_id'])."'							,
																	'".cleanvars($issue_date)."'									, 
																	'".cleanvars($due_date)."'										,
																	'".cleanvars($_POST['scholarship'][$s])."'						,
																	'".cleanvars($_POST['concession'][$s])."'						,
																	'".cleanvars($values_fine['fine'])."'							,
																	'".cleanvars($val_rem['remaining_amount'])."'					,
																	'".cleanvars($_POST['note'])."'									,
																	'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
																	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
																	Now()	
																)");
				$genratedChallans ++;
				// Fee Particulars Detail
				if($sqllms) { 

					// Get latest ID
					$idsetup = $dblms->lastestid();	

					$totalAmount = 0;
					$concessions = 0;
					$conc_amount = 0;
					$cat_conc_amount = 0;

					// Fee Details
					for($i=1; $i<= count($_POST['id_cat']); $i++){
						if(isset($_POST['is_selected'][$i]) || $_POST['id_cat'][$i] == 1){

							// GET EACH CAT AMOUNT
							if($_POST['id_cat'][$i] == 5){ 
								$cat_amount = $value_stu['transport_fee'];
							} else {
								$cat_amount = cleanvars($_POST['amount'][$i]);
							}

							if($cat_amount > 0) {
								// Concession
								$sqllmsCon	= $dblms->querylms("SELECT d.amount, d.percent, d.from_month, d.to_month
																		FROM ".SCHOLARSHIP." c
																		INNER JOIN ".CONCESSION_DETAIL." d ON d.id_setup = c.id
																		WHERE c.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
																		AND c.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
																		AND c.id_class = '".$_POST['id_class']."' AND c.id_std = '".$value_std['std_id']."'
																		AND c.status = '1' AND c.id_type = '2' AND c.is_deleted != '1'
																		AND d.id_fee_cat = '".$_POST['id_cat'][$i]."'
																		LIMIT 1");
								if(mysqli_num_rows($sqllmsCon) > 0) {
			
									$valCon = mysqli_fetch_array($sqllmsCon);

									// Conditions On which Concession Apply
									if(
										( $_POST['id_month'] >= $valCon['from_month'] && $_POST['id_month'] <= $valCon['to_month'] )
										||
										(empty($valCon['from_month']) && empty($valCon['to_month']))
									) {
										$conc_amount = ($valCon['percent'] * $cat_amount) / 100;
										$cat_conc_amount = ($cat_amount - $conc_amount);
									} else {
										$conc_amount = 0;
										$cat_conc_amount = $cat_amount;
									}
									
								} else {

									$conc_amount = 0;
									$cat_conc_amount = $cat_amount;
				
								}

								// Insert
								$sqllms  = $dblms->querylms("INSERT INTO ".FEE_PARTICULARS."(
																					id_fee			,
																					id_cat			,
																					amount			,
																					concession			
																				)
																			VALUES(
																					'".cleanvars($idsetup)."'						,
																					'".cleanvars($_POST['id_cat'][$i])."'			,
																					'".cleanvars($cat_conc_amount)."'				,
																					'".cleanvars($conc_amount)."'			

																				)");
								// Totals
								$concessions = $concessions + $conc_amount;
								$totalAmount = $totalAmount + $cat_conc_amount;
							}
						}
					}

					// Grand Total
					$grandTotal = ( $totalAmount + $val_rem['remaining_amount'] + $values_fine['fine'] );

					// Update Total Amount
					$sqllmsUpdate  = $dblms->querylms("UPDATE ".FEES." SET  
																	concession		= '".cleanvars($concessions)."'
																,	total_amount	= '".cleanvars($grandTotal)."'
															WHERE 	id = '".$idsetup."'
														");
					
					// Scholarship Added in Challan
					if($values_fine['fine'] > 0) {
						$sqllmsUpdate  = $dblms->querylms("UPDATE ".SCHOLARSHIP." SET  
																challan_no	= '".cleanvars($challano)."'
																WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
																AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
																AND id_type = '3' AND status = '1' AND is_deleted != '1'
																AND id_std = '".cleanvars($value_std['std_id'])."' AND challan_no = ''
																AND  MONTH(date) IN ('".$month."', '".$_POST['id_month']."') ");
					}
				}

				// Make Log
				$remarks = "Bulk Challan";
				$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																	id_user 				, 
																	filename				, 
																	action					,
																	challan_no 				,
																	dated					,
																	ip						,
																	remarks					, 
																	id_campus				
																)
				
															VALUES(
																	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
																	'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
																	'1'																	, 
																	'".cleanvars($challano)."'											,
																	NOW()																,
																	'".cleanvars($ip)."'												,
																	'".cleanvars($remarks)."'						,
																	'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																)
											");
			}
		}
		
		if(!$sqllms && $genratedChallans <= 0){
			
			$_SESSION['msg']['title'] 	= 'Error';
			$_SESSION['msg']['text'] 	= 'Record Already Exists';
			$_SESSION['msg']['type'] 	= 'error';
			header("Location: fee_challans.php", true, 301);
			exit();
		}
		if($genratedChallans > 0) { 
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: fee_challans.php", true, 301);
			exit();
		} else {
			
			$_SESSION['msg']['title'] 	= 'warning';
			$_SESSION['msg']['text'] 	= 'No Challan Genrated.';
			$_SESSION['msg']['type'] 	= 'warning';
			header("Location: fee_challans.php", true, 301);
			exit();
		}
	}
} 

//---------------- Single Fee Challans Genrate ----------------------
if(isset($_POST['single_challan_generate'])) { 

	if(COUNT($_POST['is_selected']) <= 0) {
		// Failed
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'No fee head selected';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: fee_challans.php", true, 301);
		exit();
	} else {
					   
		// Reformat Date
		$challandate	= date('Ym');
		$issue_date = date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'])));
		$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));

		// If Challan Not Exsist Then Genrate
		$sqllmscheck  = $dblms->querylms("SELECT id_std
											FROM ".FEES." 
											WHERE id_std	= '".cleanvars($_POST['id_std'])."'
											AND id_month	= '".cleanvars($_POST['id_month'])."'
											AND is_deleted	= '0'
											AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										");	
		if(mysqli_num_rows($sqllmscheck) > 0) {
			// Failed
			$_SESSION['msg']['title'] 	= 'Error';
			$_SESSION['msg']['text'] 	= 'Record Already Exists';
			$_SESSION['msg']['type'] 	= 'error';
			header("Location: fee_challans.php", true, 301);
			exit();
		} else {

			// Challan Number
			$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".FEES." 
																WHERE challan_no LIKE '".$challandate."%'  
																ORDER by challan_no DESC LIMIT 1 ");
			$rowchallan = mysqli_fetch_array($sqllmschallan);
			if(mysqli_num_rows($sqllmschallan) < 1) {
				$challano	= $challandate.'00001';
			} else  {
				$challano = ($rowchallan['challan_no'] +1);
			}

			// Fine
			// $month = $_POST['id_month'] - 1;
			// $sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
			// 									FROM ".SCHOLARSHIP." 
			// 									WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
			// 									AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
			// 									AND id_type = '3' AND status = '1' AND is_deleted != '1'
			// 									AND id_std = '".cleanvars($_POST['id_std'])."' AND challan_no = ''
			// 									AND  MONTH(date) IN ('".$month."', '".$_POST['id_month']."') ");
			// $values_fine = 	mysqli_fetch_array($sql_fine);
			
			// Make Challan
			$sqllms  = $dblms->querylms("INSERT INTO ".FEES."(
																status						, 
																id_type						,
																challan_no					, 
																id_session					, 
																id_month					,
																id_class					, 
																id_section					,
																id_std						,
																issue_date					,
																due_date					,
																fine						,
																prev_remaining_amount		,
																note						, 
																id_campus 					,
																id_added					,
																date_added
															) VALUES (
																'2'																,
																'2'																,
																'".cleanvars($challano)."'										,
																'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
																'".cleanvars($_POST['id_month'])."'								,
																'".cleanvars($_POST['id_class'])."'								,
																'".cleanvars($_POST['id_section'])."'							,
																'".cleanvars($_POST['id_std'])."'								,
																'".cleanvars($issue_date)."'									, 
																'".cleanvars($due_date)."'										,
																'".cleanvars($_POST['fine'])."'									,
																'".cleanvars($_POST['prev_remaining_amount'])."'				,
																'".cleanvars($_POST['note'])."'									,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
																Now()	
															)"
													);

			// Fee Particulars Detail
			if($sqllms) { 
				// Get latest ID 
				$idsetup = $dblms->lastestid();	
				$totalAmount = 0;
				$concessions = 0;
				$grandTotal = 0;
				// DETAILS INSERT
				for($i=1; $i<= count($_POST['id_cat']); $i++){
					if($_POST['amount'][$i] > 0 && isset($_POST['is_selected'][$i])) {
						// INSERT DETAILS 
						$sqllms  = $dblms->querylms("INSERT INTO ".FEE_PARTICULARS."(
																		id_fee			,
																		id_cat			,
																		amount			,
																		concession			
																	)
																VALUES(
																		'".cleanvars($idsetup)."'				,
																		'".cleanvars($_POST['id_cat'][$i])."'	,
																		'".cleanvars($_POST['amount'][$i])."'	,		
																		'".cleanvars($_POST['concession'][$i])."'			
																	)");
						$concessions = $concessions + $_POST['concession'][$i];						
						$totalAmount = $totalAmount + $_POST['amount'][$i];
					}
				}
				// Grand Total
				$grandTotal = ( $totalAmount + $_POST['prev_remaining_amount'] + $values_fine['fine'] );

				// Update Total & Concession
				$sqllmsUpdate  = $dblms->querylms("UPDATE ".FEES." SET  
														concession		= '".$concessions."'
													,	total_amount	= '".cleanvars($grandTotal)."'
														WHERE id = '".$idsetup."'
													");
				
				// Fine Added in Challan
				if($_POST['fine'] > 0) {
					$sqllmsUpdate  = $dblms->querylms("UPDATE ".SCHOLARSHIP." SET  
															challan_no	= '".cleanvars($challano)."'
															WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
															AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
															AND id_type = '3' AND status = '1' AND is_deleted != '1'
															AND id_std = '".cleanvars($_POST['id_std'])."' AND challan_no = ''
															AND  MONTH(date) IN ('".$month."', '".$_POST['id_month']."') ");
				}

				// Make Log
				$remarks = "Single Challan";
				$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
															id_user 				, 
															filename				, 
															action					,
															challan_no 				,
															dated					,
															ip						,
															remarks					, 
															id_campus				
														)

													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
															'1'																	, 
															'".cleanvars($challano)."'											,
															NOW()																,
															'".cleanvars($ip)."'												,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														)
												");

												
				// Added
				$_SESSION['msg']['title'] 	= 'Successfully';
				$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
				$_SESSION['msg']['type'] 	= 'success';
				header("Location: fee_challans.php", true, 301);
				exit();
			}
		}
	}
}

//----------------Update Single Fee Chalaln----------------------
if(isset($_POST['changes_challan'])) { 
	//------------------------------------
	if($_POST['status'] == 1){
		$paidAmount = $_POST['payable'];
		$paidDate = date('Y-m-d');
	} else {
		$paidAmount = 0;
		$paidDate = "0000-00-00";
	}
	//------------------------------------
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	if($_POST['status'] == 1) {

		//----------------- Update Chllan as Paid ---------------------
		$sqllms  = $dblms->querylms("UPDATE ".FEES." SET 
												status				= '".cleanvars($_POST['status'])."'
											,	paid_date			= '".cleanvars($paidDate)."'
											,	total_amount		= '".cleanvars($_POST['payable'])."'
											,	paid_amount			= '".cleanvars($paidAmount)."'
											,	prev_remaining_amount	= '".cleanvars($_POST['prev_remaining_amount'])."'
											,	note				= '".cleanvars($_POST['note'])."'
											,	id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
											, 	date_modify			= NOW()
										  WHERE id					= '".cleanvars($_POST['id_fee'])."'
											");
		if($sqllms) 
		{	
			//---------- If Admission Challan -----------
			if($_POST['type'] == 1){
				//------- Update Particulars ---------
				for($i = 1; $i<=COUNT($_POST['id']); $i++){
					$sqllms  = $dblms->querylms("UPDATE ".FEE_PARTICULARS." SET 
													amount  = '".cleanvars($_POST['amount'][$i])."'
											WHERE id_fee  = '".cleanvars($_POST['id_fee'])."'
												AND id 		= '".cleanvars($_POST['id'][$i])."'
												");
				}
			}

			$paidAmount = number_format($paidAmount);

			$phone = $_POST['std_phone'];
			// Send Message
			$message = 'Dear Parents! We have Received Your Child '.$_POST['std_name'].' Dues of Rs. '.$paidAmount.' for the month of '.$_POST['month'].'.'.PHP_EOL.''.PHP_EOL.'Thanks for cooperation. '.PHP_EOL.'Regards: LAUREL HOME SCHOOLS';
			sendMessage($phone, $message);
			
			//--------------------IF PAID THEN ADD IN EARNING-------------------------------
		
			//-------------------GET FEE HEAD FROM ACCOUNT HEADS------------------------
			$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' AND head_name LIKE '%fee%'");
			$values_trans_head = mysqli_fetch_array($sqllms_head);

			//------------------- Add INCOME ----------------------
			$sqllms  = $dblms->querylms("INSERT INTO ".ACCOUNT_TRANS."(
																trans_status							, 
																trans_title							    ,
																trans_type							    ,
																trans_amount							,
																voucher_no							    ,
																trans_method							,
																trans_note							    ,
																dated							        ,
																id_head							        ,
																id_campus							    ,  
																id_added							    ,  
																date_added 	
															)
														VALUES(
																'1'		                                    				,	 
																'".cleanvars($_POST['challan_no'])."'						,
																'".cleanvars($_POST['pay_mode'])."'            				,
																'".cleanvars($paidAmount)."'								,
																'".cleanvars($_POST['challan_no'])."'						,
																'1'															,
																'".cleanvars($_POST['note'])."'								,				
																'".cleanvars($paidDate)."' 									,
																'".cleanvars($values_trans_head['head_id'])."'   			,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	,
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
																NOW()	
															)"
									);
			//--------------------------------------
			
			//-------------------- Make Log ------------------------
			//------------ If Remaining Amount ---------------
			if(cleanvars($_POST['prev_remaining_amount']) > 0){
				//----------- Log Remarks ---------------
				$remarks = 'Fee Challan Paid with Pre Remaining Amount: '.cleanvars($_POST['prev_remaining_amount']).'';
			} else {
				//----------- Log Remarks ---------------
				$remarks = 'Fee Challan Paid.';
			}
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																id_user 				, 
																filename				, 
																action					,
																challan_no 				,
																dated					,
																ip						,
																remarks					, 
																id_campus				
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
																'3'																	, 
																'".cleanvars($_POST['challan_no'])."'								,
																NOW()																,
																'".cleanvars($ip)."'												,
																'".cleanvars($remarks)."'											,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: fee_challans.php", true, 301);
			exit();
			//--------------------------------------
		}
	} else {
		//----------------- Update Chllan ---------------------
		$sqllmsUpdate  = $dblms->querylms("UPDATE ".FEES." SET
												status					= '".cleanvars($_POST['status'])."'
											,	total_amount			= '".cleanvars($_POST['payable'])."'
											,	prev_remaining_amount	= '".cleanvars($_POST['prev_remaining_amount'])."'
											,	due_date				= '".cleanvars($due_date)."'
											,	note					= '".cleanvars($_POST['note'])."'
											,	id_modify				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
											, 	date_modify				= NOW()
										  WHERE id						= '".cleanvars($_POST['id_fee'])."' ");

		//------------ If Remaining Amount ---------------
		if(cleanvars($_POST['prev_remaining_amount']) > 0){
			//----------- Log Remarks ---------------
			$remarks = 'Fee Challan update with Pre Remaining Amount: '.cleanvars($_POST['prev_remaining_amount']).'';
		}
		else{
			//----------- Log Remarks ---------------
			$remarks = 'Fee Challan update.';
		}

		if($sqllmsUpdate) 
		{
			
			//---------- If Admission Challan -----------
			if($_POST['type'] == 1){
				//------- Update Particulars ---------
				for($i = 1; $i<=COUNT($_POST['id']); $i++){
					$sqllms  = $dblms->querylms("UPDATE ".FEE_PARTICULARS." SET 
													amount  = '".cleanvars($_POST['amount'][$i])."'
											WHERE id_fee  = '".cleanvars($_POST['id_fee'])."'
												AND id 		= '".cleanvars($_POST['id'][$i])."'
												");
				}
			}

			//-------------------- Make Log ------------------------
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																id_user 				, 
																filename				, 
																action					,
																challan_no 				,
																dated					,
																ip						,
																remarks					, 
																id_campus				
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
																'3'																	, 
																'".cleanvars($_POST['challan_no'])."'								,
																NOW()																,
																'".cleanvars($ip)."'												,
																'".cleanvars($remarks)."'											,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			
			//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: fee_challans.php", true, 301);
			exit();
		}
	}
}

//---------------- Update Partial Payment ----------------------
if(isset($_POST['changes_partialPayment'])) { 

	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));

	// For Remaining & Prev Remainings
	if($_POST['remaining_amount'] > $_POST['prev_remaining_amount']) {
		$remainingFromPrev = $_POST['remaining_amount'] - $_POST['prev_remaining_amount'];
		$prevremThisChallan = 0;
	} else {
		$remainingFromPrev = 0;
		$prevremThisChallan = $_POST['prev_remaining_amount'] - $_POST['remaining_amount'];
	}

	//----------------- Update Challan ---------------------
	$sqllms  = $dblms->querylms("UPDATE ".FEES." SET 
											total_amount			= '".cleanvars($_POST['partial_amount'])."'
										,	prev_remaining_amount	= '".cleanvars($prevremThisChallan)."'
										,	remaining_amount		= '".cleanvars($_POST['remaining_amount'])."'
										,	due_date				= '".cleanvars($due_date)."'
										,	note					= '".cleanvars($_POST['note'])."'
										,	id_modify				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
										, 	date_modify				= NOW()
									  WHERE id						= '".cleanvars($_POST['id_fee'])."'
										");

	//---------- Get All Values -------------
    $sqllmsFeePart  = $dblms->querylms("SELECT p.id, p.id_cat, p.amount, c.cat_name
											FROM ".FEE_PARTICULARS." p
											INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = p.id_cat
											WHERE p.id_fee = '".cleanvars($_POST['id_fee'])."'
											ORDER BY c.cat_partialpay_ordering ASC");
    while($valFeePart  = mysqli_fetch_array($sqllmsFeePart)) {  

		if($remainingFromPrev > 0) {
			if($valFeePart['amount'] > $remainingFromPrev){
				$addAmount = $valFeePart['amount'] - $remainingFromPrev;
				// echo "checl rem".$addAmount;
				$remainingFromPrev = 0;
				// echo "<br> Update" .$valFeePart['cat_name'].": ".$addAmount;
				// echo"<br>";
				
				$sqllmsUpdateTut = $dblms->querylms("UPDATE ".FEE_PARTICULARS." SET  
														amount      = '".cleanvars($addAmount)."'                        
													WHERE id_fee	= '".cleanvars($_POST['id_fee'])."'
													AND id_cat      = '".cleanvars($valFeePart['id_cat'])."'
													AND id_cat 	   != '18' 
													AND id          = '".cleanvars($valFeePart['id'])."' ");

			}else{

				$remainingFromPrev = $remainingFromPrev - $valFeePart['amount'];
				// echo "<br> Del, ".$valFeePart['cat_name'].": ".$remainingFromPrev;
				

				$sqllmsDelTut = $dblms->querylms("DELETE FROM ".FEE_PARTICULARS."                        
													WHERE id_fee	= '".cleanvars($_POST['id_fee'])."'
													AND id_cat      = '".cleanvars($valFeePart['id_cat'])."'
													AND id          = '".cleanvars($valFeePart['id'])."' ");
			}
		}

    } // end while loop

	if($sqllms) {
		//-------------------- Make Log ------------------------
		$remarks = 'Partial Payment Added of Amount '.cleanvars($addAmount).', with remaining amount '.cleanvars($_POST['remaining_amount']).'';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
															id_user 				, 
															filename				, 
															action					,
															challan_no 				,
															dated					,
															ip						,
															remarks					, 
															id_campus				
														)
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
															'3'																	, 
															'".cleanvars($_POST['challan_no'])."'								,
															NOW()																,
															'".cleanvars($ip)."'												,
															'".cleanvars($remarks)."'											,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														)
									");
		//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: fee_challans.php", true, 301);
		exit();
	}

}

//---------------- Delete reocrd----------------------
if(isset($_GET['deleteid'])) { 
	//------------------------------------------------
	$sqllms  = $dblms->querylms("UPDATE ".FEES." SET  
												  is_deleted			= '1'
												, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted			= '".$ip."'
												, date_deleted			= NOW()
											WHERE challan_no 			= '".cleanvars($_GET['deleteid'])."'");
	//--------------------------------------
		if($sqllms)
		{ 
			//-------------------- Make Log ------------------------
			$remarks = 'Fee Challan Deleted #: "'.cleanvars($_GET['deleteid']).'" details';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																id_user 				, 
																filename				, 
																action					,
																challan_no 				,
																dated					,
																ip						,
																remarks					, 
																id_campus				
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
																'3'																	, 
																'".cleanvars($_GET['deleteid'])."'									,
																NOW()																,
																'".cleanvars($ip)."'												,
																'".cleanvars($remarks)."'											,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Warning';
			$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
			$_SESSION['msg']['type'] 	= 'warning';
			header("Location: fee_challans.php", true, 301);
			exit();
			//--------------------------------------
		}
	//--------------------------------------
}
?>