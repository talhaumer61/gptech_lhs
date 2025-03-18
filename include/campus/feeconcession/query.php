<?php 

// Fee Concession insert record
if(isset($_POST['add_concession'])) {

	
    $value = explode("|", $_POST['id_std']);
    $std = $value[0];
    $class = $value[2];

	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".SCHOLARSHIP." 
										WHERE id_type = '2'	AND id_class = '".cleanvars($_POST['id_class'])."' 
										AND id_std = '".cleanvars($_POST['id_std'])."' 
										AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
										AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: feeconcession.php", true, 301);
		exit();
	} else {
		// Insert
		$sqllmsConcession = $dblms->querylms("INSERT INTO ".SCHOLARSHIP."(
																status					, 
																id_type					, 
																id_cat					,
																id_class				, 
																id_std					,
																id_session				, 
																note					,
																id_campus				,
																id_added				,
																date_added			 	
															) VALUES (
																'".cleanvars($_POST['status'])."'								, 
																'2'																,
																'".cleanvars($_POST['id_cat'])."'								, 
																'".cleanvars($class)."'											, 
																'".cleanvars($std)."'											, 
																'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	,
																'".cleanvars($_POST['note'])."'									,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
																NOW()
															)" );
		if($sqllmsConcession) {
			// Latest ID													
			$id_setup = $dblms->lastestid();
			// Details									
			for($i=1; $i<=COUNT($_POST['id_fee_cat']); $i++) {
				if($_POST['con_amount'][$i] > 0 ) {

					// Fee Category ID
					$value = explode("|", $_POST['duration'][$i]);
					$duration = $value[0];
					// 
					if($duration == 2) {
						$from = $_POST['from'][$i];
						$to = $_POST['to'][$i];
					} else {
						$from = 0;
						$to = 0;
					}

					$sqllmsDet = $dblms->querylms("INSERT INTO ".CONCESSION_DETAIL."(
																						id_setup		, 
																						id_fee_cat		, 
																						from_month		,
																						to_month		,
																						amount			,
																						percent					
																					) VALUES (
																						'".cleanvars($id_setup)."'					, 
																						'".cleanvars($_POST['id_fee_cat'][$i])."'	, 
																						'".cleanvars($from)."'						, 
																						'".cleanvars($to)."'						, 
																						'".cleanvars($_POST['con_amount'][$i])."'	, 
																						'".cleanvars($_POST['con_percent'][$i])."'
																					)");
				}
			}
		}

		// LOG
		if($sqllmsDet) { 
			$remarks = 'Add Fee Concession ID#"'.cleanvars($id_setup).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user										, 
																filename									, 
																action										,
																dated										,
																ip											,
																remarks				
															) VALUES (
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
																	'1'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'			
															)");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: feeconcession.php", true, 301);
			exit();
		}
	}
}

//Fee Concession Update reocrd
if(isset($_POST['update_feeconcession'])) {
	
	// Fee Category ID
	$value = explode("|", $_POST['id_cat']);
    $fee_cat = $value[0];

	$sqllms  = $dblms->querylms("UPDATE ".SCHOLARSHIP." SET  
													  status		= '".cleanvars($_POST['status'])."'
													, id_cat		= '".cleanvars($_POST['id_cat'])."'	
													, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify	= NOW()
												WHERE id			= '".cleanvars($_POST['id'])."'");
	if($sqllms) {

		// Latest ID													
		$id_setup = cleanvars($_POST['id']);
									
		for($i=1; $i<=COUNT($_POST['id_fee_cat']); $i++) {

			if($_POST['con_amount'][$i] >= 0 ) {
				
				// Fee Category ID
				$value = explode("|", $_POST['duration'][$i]);
				$duration = $value[0];

				if($duration == 2) {
					$from = $_POST['from'][$i];
					$to = $_POST['to'][$i];
				} else {
					$from = 0;
					$to = 0;
				}
		
				// Check If Record Exist
				$sqllmscheck  = $dblms->querylms("SELECT det_id
													FROM ".CONCESSION_DETAIL." 
													WHERE id_setup = '".cleanvars($id_setup)."'	
													AND id_fee_cat = '".cleanvars($_POST['id_fee_cat'][$i])."'  LIMIT 1");
				if(mysqli_num_rows($sqllmscheck) > 0) {
					// If NOT Exist Then Insert
					$valDet = mysqli_fetch_array($sqllmscheck);

					$sqllmsDet  = $dblms->querylms("UPDATE ".CONCESSION_DETAIL." SET  
																	  from_month	= '".cleanvars($from)."'
																	, to_month		= '".cleanvars($to)."'
																	, amount		= '".cleanvars($_POST['con_amount'][$i])."'
																	, percent		= '".cleanvars($_POST['con_percent'][$i])."'
																WHERE det_id		= '".cleanvars($valDet['det_id'])."'");

				} else {
					// If Exist Then Update					
					$sqllmsDet = $dblms->querylms("INSERT INTO ".CONCESSION_DETAIL."(
																						id_setup		, 
																						id_fee_cat		, 
																						from_month		,
																						to_month		,
																						amount			,
																						percent					
																					) VALUES (
																						'".cleanvars($id_setup)."'					, 
																						'".cleanvars($_POST['id_fee_cat'][$i])."'	, 
																						'".cleanvars($from)."'						, 
																						'".cleanvars($to)."'						, 
																						'".cleanvars($_POST['con_amount'][$i])."'	, 
																						'".cleanvars($_POST['con_percent'][$i])."'
																					)");
				}
			}
		}
	}

	if($sqllmsDet) { 
		$remarks = 'Update Fee Concession ID#"'.cleanvars($id_setup).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks			
														  )
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'2'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'		
														  )");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: feeconcession.php", true, 301);
			exit();
	}
}







// Fee Concession insert record
if(isset($_POST['submit_feeconcession'])) {

	// Fee Category ID
	$value = explode("|", $_POST['id_cat']);
    $fee_cat = $value[0];

	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".SCHOLARSHIP." 
										WHERE id_type = '2' AND id_fee_cat = '".$fee_cat."' AND id_cat = '".cleanvars($_POST['id_cat'])."' 
										AND id_class = '".cleanvars($_POST['id_class'])."' AND id_std = '".cleanvars($_POST['id_std'])."' 
										AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
										AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: feeconcession.php", true, 301);
		exit();
	} else {
		// Insert
		$sqllms  = $dblms->querylms("INSERT INTO ".SCHOLARSHIP."(
															status					, 
															id_type					, 
															id_fee_cat				,
															percent					,
															amount					,
															total_amount			,
															id_cat					,
															id_class				, 
															id_std					,
															id_session				, 
															note					,
															id_campus				,
															id_added				,
															date_added			 	
														) VALUES (
															'".cleanvars($_POST['status'])."'								, 
															'2'																,
															'".cleanvars($fee_cat)."'										, 
															'".cleanvars($_POST['percent'])."'								, 
															'".cleanvars($_POST['amount'])."'								, 
															'".cleanvars($_POST['totalamount'])."'							, 
															'".cleanvars($_POST['id_cat'])."'								, 
															'".cleanvars($_POST['id_class'])."'								, 
															'".cleanvars($_POST['id_std'])."'								, 
															'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	,
															'".cleanvars($_POST['note'])."'									,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															NOW()
														)" );
		if($sqllms) { 
			$remarks = 'Add Fee Concession ID#"'.cleanvars($_POST['id']).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user										, 
																filename									, 
																action										,
																dated										,
																ip											,
																remarks				
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
																'1'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'			
															)");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: feeconcession.php", true, 301);
			exit();
		}
	} // end checker
} 

//Fee Concession Update reocrd
if(isset($_POST['changes_feeconcession'])) {
	
	// Fee Category ID
	$value = explode("|", $_POST['id_cat']);
    $fee_cat = $value[0];

	$sqllms  = $dblms->querylms("UPDATE ".SCHOLARSHIP." SET  
													  status		= '".cleanvars($_POST['status'])."'
													, id_fee_cat	= '".cleanvars($fee_cat)."' 
													, percent		= '".cleanvars($_POST['percent'])."' 
													, amount		= '".cleanvars($_POST['amount'])."' 
													, total_amount	= '".cleanvars($_POST['totalamount'])."' 
													, id_cat		= '".cleanvars($_POST['id_cat'])."'	
													, id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
													, note			= '".cleanvars($_POST['note'])."' 
													, id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
													, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify	= NOW()
												WHERE id			= '".cleanvars($_POST['id'])."'");

	if($sqllms) { 
		$remarks = 'Update Fee Concession ID#"'.cleanvars($_POST['id']).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks			
														  )
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'2'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'		
														  )");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: feeconcession.php", true, 301);
			exit();
	}
}
?>