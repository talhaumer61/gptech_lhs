<?php
// TIMETABLE INSERT RECORD
if(isset($_POST['submit_timetable'])){ 
	$sqllmscheck  = $dblms->querylms("SELECT period_name  
										FROM ".PERIODS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND period_name = '".cleanvars($_POST['period_name'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		//-------if already exist---------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: timetable_period.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".PERIODS."(
													      period_status 
														, period_ordering
														, period_name
														, id_campus 	
													  )
	   											VALUES(
														  '".cleanvars($_POST['period_status'])."' 
														  , '".cleanvars($_POST['period_ordering'])."'
														  , '".cleanvars($_POST['period_name'])."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
													  )"
							);
		if($sqllms){ 
			$remarks = 'Add timetable: "'.cleanvars($_POST['period_name']).'" detail';
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
			header("Location: timetable_period.php", true, 301);
			exit();
		}
	}
}

// TIMETABLE UPDATE RECORD
if(isset($_POST['changes_timetable'])){
	$sqllmscheck  = $dblms->querylms("SELECT period_name  
										FROM ".PERIODS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND period_name = '".cleanvars($_POST['period_name'])."'
										AND period_id  != '".cleanvars($_POST['period_id'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		//-------if already exist---------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: timetable_period.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("UPDATE ".PERIODS." SET  
													  period_status		= '".cleanvars($_POST['period_status'])."'
													, period_ordering	= '".cleanvars($_POST['period_ordering'])."'
													, period_name		= '".cleanvars($_POST['period_name'])."'
													, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
													  WHERE period_id	= '".cleanvars($_POST['period_id'])."'");
		if($sqllms){ 
			$remarks = 'Update Timetable: "'.cleanvars($_POST['period_name']).'" details';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user										, 
																filename									, 
																action										,
																dated										,
																ip											,
																remarks										, 
																id_campus				
															  )
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
																'2'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'					,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															  )
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: timetable_period.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".PERIODS." SET  
												  is_deleted		= '1'
												, id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted		= '".$ip."'
												, date_deleted		= NOW()
												  WHERE period_id	= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		$remarks = 'Period Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: timetable_period.php", true, 301);
		exit();
	}
}
?>