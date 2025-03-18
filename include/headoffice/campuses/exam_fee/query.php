<?php 
// ADD EXAM FEE
if(isset($_POST['submit_exam_fee'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".EXAM_FEE." 
										WHERE id_campus  = '".cleanvars($_POST['id_campus'])."' 
										AND id_exam_type = '".cleanvars($_POST['id_exam_type'])."' 
										AND is_deleted	 = '0'
										LIMIT 1
                                    ");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuses.php?id=".cleanvars($_POST['id_campus'])."", true, 301);
		exit();
	}else{ 
		$sqllms = $dblms->querylms("INSERT INTO ".EXAM_FEE."(
															  id_campus 
															, fee_per_std
															, status  
															, id_exam_type  
															, id_added
															, date_added
														) VALUES (
															  '".cleanvars($_POST['id_campus'])."' 
															, '".cleanvars($_POST['fee_per_std'])."'
															, '".cleanvars($_POST['status'])."'
															, '".cleanvars($_POST['id_exam_type'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()
														)");
		if($sqllms) { 
			
			$class_id = $dblms->lastestid();
			$remarks = 'Add Exam Fee: "'.cleanvars($_POST['fee_per_std']).'" detail';
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
																, '1' 
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: campuses.php?id=".cleanvars($_POST['id_campus'])."", true, 301);
			exit();
		}
	}
}

// UPDATE EXAM FEE
if(isset($_POST['update_exam_fee'])) {
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".EXAM_FEE." 
										WHERE id_campus  = '".cleanvars($_POST['id_campus'])."' 
										AND id_exam_type = '".cleanvars($_POST['id_exam_type'])."' 
										AND id != '".cleanvars($_POST['id'])."' 
										AND is_deleted	 = '0'
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuses.php?id=".cleanvars($_POST['id_campus'])."", true, 301);
		exit();
	}else{ 
		$sqllms  = $dblms->querylms("UPDATE ".EXAM_FEE." SET  	
														  status		= '".cleanvars($_POST['status'])."'
														, id_exam_type	= '".cleanvars($_POST['id_exam_type'])."' 
														, fee_per_std	= '".cleanvars($_POST['fee_per_std'])."' 
														, id_campus		= '".cleanvars($_POST['id_campus'])."' 
														, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
														, date_modify	= Now()
														  WHERE id		= '".cleanvars($_POST['id'])."'");

		if($sqllms){ 
			// Log
			$remarks = 'Update Exam Fee: "'.cleanvars($_POST['fee_per_std']).'" details';
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
																, '2' 
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: campuses.php?id=".cleanvars($_POST['id_campus'])."", true, 301);
			exit();
		}
	}
}

// DELETE EXAM FEE
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".EXAM_FEE." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE id		= '".cleanvars($_GET['deleteid'])."'");

	//--------------------------------------
	if($sqllms) { 
		$remarks = 'Exam Fee Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: campuses.php?id=".cleanvars($_GET['id'])."", true, 301);
		exit();
	}
}