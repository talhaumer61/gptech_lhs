<?php
// INSERT RECORD
if(isset($_POST['submit_diary'])){
	$aray 		= explode("|", $_POST['id_subject']);
	$id_subject	= $aray[0];
	$id_teacher	= $aray[1];

	$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
	
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".DIARY." 
										WHERE id_class	= '".cleanvars($_POST['id_class'])."'
										AND id_section	= '".cleanvars($_POST['id_section'])."'
										AND id_subject	= '".cleanvars($id_subject)."'
										AND id_teacher	= '".cleanvars($id_teacher)."'
										AND dated		= '".cleanvars($dated)."'
										AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: diary.php", true, 301);
		exit();
	}else{ 
		$sqllms  = $dblms->querylms("INSERT INTO ".DIARY."(
															  status 
															, note 
															, id_session
															, id_class
															, id_section
															, id_subject
															, id_teacher 
															, dated 
															, id_campus
															, id_added 
															, date_added 	
														)
													VALUES(
															  '".cleanvars($_POST['status'])."'
															, '".cleanvars($_POST['note'])."' 
															, '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
															, '".cleanvars($_POST['id_class'])."'
															, '".cleanvars($_POST['id_section'])."'
															, '".cleanvars($id_subject)."'
															, '".cleanvars($id_teacher)."' 
															, '".cleanvars($dated)."' 
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, NOW()
														)" );
		$id_latest = $dblms->lastestid();
		if($sqllms) { 
			$remarks = 'Add Diary ID #"'.$id_latest.'"';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																  id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks				
															)
															VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
																, '1' 
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: diary.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_diary'])){
	$aray 		= explode("|", $_POST['id_subject']);
	$id_subject	= $aray[0];
	$id_teacher	= $aray[1];

	$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
	
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".DIARY." 
										WHERE id_class	= '".cleanvars($_POST['id_class'])."'
										AND id_section	= '".cleanvars($_POST['id_section'])."'
										AND id_subject	= '".cleanvars($id_subject)."'
										AND id_teacher	= '".cleanvars($id_teacher)."'
										AND dated		= '".cleanvars($dated)."'
										AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id		   != '".cleanvars($_POST['id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: diary.php", true, 301);
		exit();
	}else{ 
		$sqllms  = $dblms->querylms("UPDATE ".DIARY." SET  
													  status		= '".cleanvars($_POST['status'])."'
													, id_class		= '".cleanvars($_POST['id_class'])."'
													, id_section	= '".cleanvars($_POST['id_section'])."'
													, id_subject	= '".cleanvars($id_subject)."'
													, id_teacher	= '".cleanvars($id_teacher)."'
													, dated			= '".cleanvars($dated)."'
													, note			= '".cleanvars($_POST['note'])."'
													, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify	= NOW()
													  WHERE id		= '".cleanvars($_POST['id'])."'
									");
		if($sqllms){
			$remarks = 'Update Diary ID # "'.cleanvars($_POST['id']).'"';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																  id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks			
															)
														VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
																, '2' 
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
															)
											");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location:  diary.php", true, 301);
			exit();
		}
	}
}
?>