<?php 
// ADD ROYALTY SETTING
if(isset($_POST['submit_royalty'])) { 

	$startDate	  =	date('Y-m-d', strtotime(($_POST['start_date'])));
	$endDate	  =	date('Y-m-d', strtotime(($_POST['end_date'])));
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".ROYALTY_SETTING." 
										WHERE id_campus		=	'".cleanvars($_POST['id_campus'])."' 
										AND royalty_type	=	'".cleanvars($_POST['royalty_type'])."'
										AND (start_date		=	'".$startDate."' OR end_date	= '".$endDate."')
										AND is_deleted		=	'0'
										LIMIT 1
                                    ");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuses.php?id=".cleanvars($_POST['id_campus'])."", true, 301);
		exit();
	}else{ 
		$sqllms = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING."(
															  status 
															, royalty_type
															, royalty_amount  
															, start_date  
															, end_date  
															, id_campus  
															, id_added
															, date_added
														) VALUES (
															  '".cleanvars($_POST['status'])."' 
															, '".cleanvars($_POST['royalty_type'])."'
															, '".($_POST['royalty_type'] == '1' ? $_POST['per_month'] : $_POST['per_student'])."'
															, '".$startDate."'
															, '".$endDate."'
															, '".cleanvars($_POST['id_campus'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()
														)");
		if($sqllms) { 
			
			$id		 = $dblms->lastestid();
			$remarks = 'Add Royalty Setting: "'.cleanvars($id).'" detail';
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

// UPDATE ROYALTY SETTING
if(isset($_POST['update_royalty'])) {

	$startDate	  =	date('Y-m-d', strtotime(($_POST['start_date'])));
	$endDate	  =	date('Y-m-d', strtotime(($_POST['end_date'])));
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".ROYALTY_SETTING." 
										WHERE id_campus		= 	'".cleanvars($_POST['id_campus'])."'
										AND royalty_type	=	'".cleanvars($_POST['royalty_type'])."'
										AND (start_date		= 	'".$startDate."' OR end_date	= '".$endDate."')
										AND is_deleted		= 	'0'
										AND id 				!= 	'".cleanvars($_POST['id'])."'
										LIMIT 1
                                    ");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuses.php?id=".cleanvars($_POST['id_campus'])."", true, 301);
		exit();
	}else{ 
		$sqllms  = $dblms->querylms("UPDATE ".ROYALTY_SETTING." SET  	
														  status			= '".cleanvars($_POST['status'])."'
														, royalty_type		= '".cleanvars($_POST['royalty_type'])."' 
														, royalty_amount	= '".($_POST['royalty_type'] == '1' ? $_POST['per_month'] : $_POST['per_student'])."' 
														, start_date		= '".cleanvars($startDate)."' 
														, end_date			= '".cleanvars($endDate)."' 
														, id_campus			= '".cleanvars($_POST['id_campus'])."' 
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
														, date_modify		= Now()
														  WHERE id			= '".cleanvars($_POST['id'])."'");

		if($sqllms){ 
			// Log
			$remarks = 'Update Royalty Setting: "'.cleanvars($_POST['id']).'" details';
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

// DELETE ROYALTY SETTING
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".ROYALTY_SETTING." SET  
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