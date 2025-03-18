<?php
// Genrate Challan
if(isset($_POST['genrate_challan'])) {

	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".FEES." 
										WHERE ( (id_month BETWEEN '".cleanvars($_POST['from_month'])."' AND '".cleanvars($_POST['to_month'])."') OR (to_month BETWEEN '".cleanvars($_POST['from_month'])."' AND '".cleanvars($_POST['to_month'])."') )
										AND id_type = '3' 
										AND id_campus = '".cleanvars($_POST['id_campus'])."' 
										LIMIT 1");

	if(mysqli_num_rows($sqllmscheck) > 0){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: royaltyChallans.php", true, 301);
		exit();
	}else{
		//Reformat Date
		$challandate	= date('Ym');
		$issue_date = date('Y-m-d');
		$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
		
		//Genrate Challan Number
		$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".FEES." 
															WHERE challan_no LIKE '".$challandate."%'  
															ORDER by challan_no DESC LIMIT 1 ");
		$rowchallan 	= mysqli_fetch_array($sqllmschallan);
		if(mysqli_num_rows($sqllmschallan) < 1){
			$challano	= $challandate.'00001';
		}else{
			$challano = ($rowchallan['challan_no'] +1);
		}
		
		// MAKE CHALLAN
		$sqllms  = $dblms->querylms("INSERT INTO ".FEES."(
															  status 
															, id_type
															, challan_no 
															, id_session 
															, id_month
															, to_month
															, issue_date
															, due_date
															, total_amount
															, id_campus
															, id_added
															, date_added
														)
													VALUES(
															  '2'
															, '3'
															, '".cleanvars($challano)."'
															, '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
															, '".cleanvars($_POST['from_month'])."'
															, '".cleanvars($_POST['to_month'])."'
															, '".cleanvars($issue_date)."' 
															, '".cleanvars($due_date)."'
															, '".cleanvars($_POST['total_amount'])."'
															, '".cleanvars($_POST['id_campus'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()	
														)" );

		//Royal Challan Detail
		if($sqllms) { 
			//Get latest Id
			$idsetup = $dblms->lastestid();
			$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_CHALLAN_DET."(
																						  id_setup
																						, royalty_type
																						, no_of_month
																						, no_of_std
																						, royalty_amount						
																					)
																				VALUES(
																						  '".$idsetup."'
																						, '".cleanvars($_POST['royalty_type'])."'
																						, '".cleanvars($_POST['no_of_month'])."'
																						, '".cleanvars($_POST['no_of_std'])."'
																						, '".cleanvars($_POST['royalty_amount'])."'				
																					)");
			$remarks = "Single Royalty Challan";
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
														  id_user 
														, filename 
														, action
														, challan_no
														, dated
														, ip
														, remarks 
														, id_campus				
													)

												VALUES(
														  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
														, '1'
														, '".cleanvars($challano)."'
														, NOW()
														, '".cleanvars($ip)."'
														, '".cleanvars($remarks)."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
													)");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: royaltyChallans.php", true, 301);
			exit();
		}
	}
}

// Update Challan
if(isset($_POST['update_challan'])) {

	if($_POST['status'] == 1){
		$paidAmount = $_POST['payable'];
		$paidDate = date('Y-m-d');
	} else {
		$paidAmount = 0;
		$paidDate = "0000-00-00";
	}

	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));

	if($_POST['status'] == 1) {
		// Update Chllan as Paid 
		$sqllmsRoyalty  = $dblms->querylms("UPDATE ".FEES." SET 
																status				= '".cleanvars($_POST['status'])."'
															,	paid_date			= '".cleanvars($paidDate)."'
															,	total_amount		= '".cleanvars($_POST['payable'])."'
															,	paid_amount			= '".cleanvars($paidAmount)."'
															,	note				= '".cleanvars($_POST['note'])."'
															,	id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
															, 	date_modify			= NOW()
														WHERE id					= '".cleanvars($_POST['id_fee'])."'
											");
	}else {
		// Update Chllan 
		$sqllmsRoyalty  = $dblms->querylms("UPDATE ".FEES." SET
												status					= '".cleanvars($_POST['status'])."'
											,	total_amount			= '".cleanvars($_POST['payable'])."'
											,	due_date				= '".cleanvars($due_date)."'
											,	note					= '".cleanvars($_POST['note'])."'
											,	id_modify				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
											, 	date_modify				= NOW()
										  WHERE id						= '".cleanvars($_POST['id_fee'])."' ");
	
		
	}
	if($sqllmsRoyalty) {
		
		// Make Log
		$remarks = "Royalty Challan Update";
		$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																		id_user 
																		, filename 
																		, action
																		, challan_no
																		, dated
																		, ip
																		, remarks 
																		, id_campus				
																	)

																VALUES(
																		'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																		, '1'
																		, '".cleanvars($_POST['challan_no'])."'
																		, NOW()
																		, '".cleanvars($ip)."'
																		, '".cleanvars($remarks)."'
																		, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																	)");
																		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: royaltyChallans.php", true, 301);
		exit();
	}
}

// Delete Challan
if(isset($_GET['deleteid'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".FEES." SET  
												  is_deleted			= '1'
												, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted			= '".$ip."'
												, date_deleted			= NOW()
											WHERE challan_no 			= '".cleanvars($_GET['deleteid'])."'");
		if($sqllms)
		{ 
			// Make Log
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
			$_SESSION['msg']['title'] 	= 'Warning';
			$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
			$_SESSION['msg']['type'] 	= 'warning';
			header("Location: royaltyChallans.php", true, 301);
			exit();
		}
}
?>