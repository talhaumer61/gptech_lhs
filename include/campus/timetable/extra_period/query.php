<?php 
//Extra Period Insert Record
if(isset($_POST['submit_extra_period'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".TIMETABLE_EXTRA_PERIOD." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND id_class = '".cleanvars($_POST['id_class'])."'
										AND id_section = '".cleanvars($_POST['id_section'])."'
										AND id_subject = '".cleanvars($_POST['id_subject'])."'
										AND from_date = '".cleanvars($_POST['from_date'])."'
										AND from_time = '".cleanvars($_POST['from_time'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		//if already exist
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: timetable_period.php", true, 301);
		exit();
	} else { 

		// Date Conversion
		$fromDate = date('Y-m-d', strtotime($_POST['from_date']));
		$toDate = date('Y-m-d', strtotime($_POST['to_date']));

		//Time Cinversion
		$fromTime = date("H:i", strtotime($_POST['from_time']));
		$toTime = date("H:i", strtotime($_POST['to_time']));
		

		$sqllms  = $dblms->querylms("INSERT INTO ".TIMETABLE_EXTRA_PERIOD."(
															status			,
															id_session		, 
															id_class		, 
															id_section		, 
															id_subject		,
															id_teacher		,
															id_room			,
															from_date		,
															to_date			,
															from_time		,
															to_time			,
															id_campus 		,
															id_added		,
															date_added
														)
													VALUES(
															'".cleanvars($_POST['status'])."'								, 
															'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	,
															'".cleanvars($_POST['id_class'])."'								,
															'".cleanvars($_POST['id_section'])."' 							,
															'".cleanvars($_POST['id_subject'])."'							,
															'".cleanvars($_POST['id_teacher'])."'							,
															'".cleanvars($_POST['id_room'])."'								,
															'".cleanvars($fromDate)."'										,
															'".cleanvars($toDate)."'										,
															'".cleanvars($fromTime)."'										,
															'".cleanvars($toTime)."'										,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															Now()	
														)");
		if($sqllms) { 

			$latestId = $dblms->lastestid();

			$remarks = 'Add Extra Period ID: '.$latestId.' detail';
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
																'1'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'					,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: timetable_extra_period.php", true, 301);
			exit();
		}
	} // end checker
}

// Extra Period Update Reocrd
if(isset($_POST['changes_extra_period'])) { 

	// Date Conversion
	$fromDate = date('Y-m-d', strtotime($_POST['from_date']));
	$toDate = date('Y-m-d', strtotime($_POST['to_date']));

	//Time Cinversion
	$fromTime = date("H:i", strtotime($_POST['from_time']));
	$toTime = date("H:i", strtotime($_POST['to_time']));

	$sqllms  = $dblms->querylms("UPDATE ".TIMETABLE_EXTRA_PERIOD." SET  
													  status		= '".cleanvars($_POST['status'])."'
													, id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
													, id_class		= '".cleanvars($_POST['id_class'])."' 
													, id_section	= '".cleanvars($_POST['id_section'])."' 
													, id_teacher	= '".cleanvars($_POST['id_teacher'])."' 
													, id_room		= '".cleanvars($_POST['id_room'])."' 
													, from_date		= '".cleanvars($fromDate)."' 
													, to_date		= '".cleanvars($toDate)."' 
													, from_time		= '".cleanvars($fromTime)."' 
													, to_time		= '".cleanvars($toTime)."' 
													, id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												WHERE id			= '".cleanvars($_POST['id'])."'");
	if($sqllms) { 
		$remarks = 'Update Extra Period ID:'.cleanvars($_POST['id']).' details';
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
			header("Location: timetable_extra_period.php", true, 301);
			exit();
	}
}

//Delete Extra Period
// if(isset($_GET['deleteid'])) { 
// 	$sqllms  = $dblms->querylms("UPDATE ".PERIODS." SET  
// 										is_deleted				= '1'
// 									, id_deleted				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
// 									, ip_deleted				= '".$ip."'
// 									, date_deleted			= NOW()
// 									WHERE period_id 			= '".cleanvars($_GET['deleteid'])."'");
// 	if($sqllms) { 
// 		$remarks = 'Period Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
// 		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
// 															id_user										, 
// 															filename									, 
// 															action										,
// 															dated										,
// 															ip											,
// 															remarks										, 
// 															id_campus				
// 															)
		
// 													VALUES(
// 															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
// 															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
// 															'3'											, 
// 															NOW()										,
// 															'".cleanvars($ip)."'						,
// 															'".cleanvars($remarks)."'						,
// 															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
// 															)
// 									");
// 			$_SESSION['msg']['title'] 	= 'Warning';
// 			$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
// 			$_SESSION['msg']['type'] 	= 'warning';
// 			header("Location: timetable_period.php", true, 301);
// 			exit();
// 	}
// }

?>