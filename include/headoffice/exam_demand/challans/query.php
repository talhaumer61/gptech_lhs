<?php
//ADD SINGLE CHALLAN
if(isset($_POST['genrate_challan'])) {
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_FEE_CHALLANS." 
										WHERE id_examtype = '".cleanvars($_POST['id_examtype'])."' 
										AND	  id_session  = '".cleanvars($_POST['id_session'])."'
										AND   id_campus   = '".cleanvars($_POST['id_campus'])."'
										AND   due_date    = '".cleanvars($due_date)."'  
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck) > 0){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_demand_challans.php?view=add_single", true, 301);
		exit();
	}else{
		$issue_date = date('Y-m-d');

		//CHALLAN NUMBER
		$challandate	= date('Ym');
		$sqllmschallan 	= $dblms->querylms("SELECT challan_no 
											FROM ".EXAM_FEE_CHALLANS." 
											WHERE challan_no LIKE '".$challandate."%'  
											ORDER by challan_no DESC LIMIT 1 ");
		$rowchallan 	= mysqli_fetch_array($sqllmschallan);
		if(mysqli_num_rows($sqllmschallan) < 1){
			$challano	= $challandate.'00001';
		}else{
			$challano = ($rowchallan['challan_no'] +1);
		}
		$sqllms  = $dblms->querylms("INSERT INTO ".EXAM_FEE_CHALLANS."(
															  status 
															, challan_no 
															, id_session 
															, id_campus
															, id_examtype
															, id_demand
															, issue_date
															, due_date
															, total_amount
															, id_added
															, date_added
														)
													VALUES(
															  '2'
															, '".cleanvars($challano)."'
															, '".cleanvars($_POST['id_session'])."' 
															, '".cleanvars($_POST['id_campus'])."'
															, '".cleanvars($_POST['id_examtype'])."'
															, '".cleanvars($_POST['id_demand'])."'
															, '".cleanvars($issue_date)."' 
															, '".cleanvars($due_date)."'
															, '".cleanvars($_POST['total_amount'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()	
														)" );
		if($sqllms) { 
			//Get latest Id
			$id		 = $dblms->lastestid();
			$remarks = "Single Exam Demand Challan: ".$id." Details";
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
			header("Location: exam_demand_challans.php", true, 301);
			exit();
			
		}
	}
}

//ADD BULK CHALLAN
if(isset($_POST['genrate_bulk_challan'])) {

	$due_date 		=	date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	$issue_date 	=	date('Y-m-d');
	$id_session		=	$_POST['id_session'];
	$id_examtype	=	$_POST['id_examtype'];

	//CHALLAN NUMBER
	$challandate	= date('Ym');
	$sqllmschallan 	= $dblms->querylms("SELECT challan_no 
										FROM ".EXAM_FEE_CHALLANS." 
										WHERE challan_no LIKE '".$challandate."%'  
										ORDER by challan_no DESC LIMIT 1 ");
	$rowchallan 	= mysqli_fetch_array($sqllmschallan);
	if(mysqli_num_rows($sqllmschallan) < 1){
		$challano	= $challandate.'00001';
	}else{
		$challano = ($rowchallan['challan_no'] +1);
	}

	for ($i=0; $i < sizeof($_POST['id_demand']); $i++) { 

		$sqllms  = $dblms->querylms("INSERT INTO ".EXAM_FEE_CHALLANS."(
																		  status 
																		, challan_no 
																		, id_session 
																		, id_campus
																		, id_examtype
																		, id_demand
																		, issue_date
																		, due_date
																		, total_amount
																		, id_added
																		, date_added
																	)
																VALUES(
																			'2'
																		, '".cleanvars($challano)."'
																		, '".cleanvars($id_session)."' 
																		, '".cleanvars($_POST['id_campus'][$i])."'
																		, '".cleanvars($id_examtype)."'
																		, '".cleanvars($_POST['id_demand'][$i])."'
																		, '".cleanvars($issue_date)."' 
																		, '".cleanvars($due_date)."'
																		, '".cleanvars($_POST['total_amount'][$i])."'
																		, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, Now()	
																	)" );
		$challano++;
	}
	// MAKE LOG
	if($sqllms) { 
		//Get latest Id
		$id		 = $dblms->lastestid();
		$remarks = "Single Exam Demand Challan: ".$id." Details";
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
		//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: exam_demand_challans.php", true, 301);
		exit();
		//--------------------------------------
	}
}

// UPDATE CHALLAN
if(isset($_POST['update_challan'])) {

	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	if($_POST['status'] == '1'){
		$paid_date   = date('Y-m-d' , strtotime(cleanvars($_POST['paid_amount'])));
		$paid_amount = $_POST['paid_amount'];
	}else{
		$paid_date = '0000-00-00';
		$paid_amount = '0';
	}
	$sqllms  = $dblms->querylms("UPDATE ".EXAM_FEE_CHALLANS." SET  
													  status		= '".cleanvars($_POST['status'])."'
													, due_date		= '".cleanvars($due_date)."'
													, paid_date		= '".cleanvars($paid_date)."'
													, paid_amount	= '".cleanvars($paid_amount)."'
													, note			= '".cleanvars($_POST['note'])."'
													, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
													, date_modify	= NOW()  
													  WHERE id		= '".cleanvars($_POST['id'])."'
								");

	// Make Log
	if($sqllms){
		$remarks = "Exam Demand Challan Updated:". $_POST['id']." details";
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
		header("Location: exam_demand_challans.php", true, 301);
		exit();
	}
}

// DELETE CHALLAN
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".EXAM_FEE_CHALLANS." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE id		= '".cleanvars($_GET['deleteid'])."'");

	//--------------------------------------
	if($sqllms) { 
		$remarks = 'Exam Demand Challan Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															  id_user 
															, filename 
															, action
															, dated
															, ip
															, remarks 
															, id_campus				
														)
													VALUES(
															  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
															, '3' 
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
										");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: exam_demand_challans.php?id=".cleanvars($_GET['id'])."", true, 301);
		exit();
	}
}