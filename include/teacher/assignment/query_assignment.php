<?php 
// INSERT RECORD
if(isset($_POST['submit_assignment'])){
	$aray 		= explode("|", $_POST['id_subject']);
	$id_subject	= $aray[0];
	$id_teacher	= $aray[1];
	
	$sqllmscheck  = $dblms->querylms("SELECT assig_title
										FROM ".ASSIGNMENT." 
										WHERE assig_title	= '".cleanvars($_POST['assig_title'])."' 
										AND id_session		= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND id_section 		= '".cleanvars($_POST['id_section'])."'
										AND id_subject 		= '".cleanvars($id_subject)."'
										AND id_teacher 		= '".cleanvars($id_teacher)."'
										AND id_campus  		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: assignment.php", true, 301);
		exit();
	}else{ 
		$open_date = date('Y-m-d' , strtotime(cleanvars($_POST['open_date'])));
		$close_date = date('Y-m-d' , strtotime(cleanvars($_POST['close_date'])));

		$sqllms  = $dblms->querylms("INSERT INTO ".ASSIGNMENT."(
															  assig_status 
															, assig_title 
															, assig_note  
															, open_date  
															, close_date  
															, id_session
															, id_class
															, id_section 
															, id_subject
															, id_teacher
															, id_campus
															, id_added 
															, date_added 	
														)
													VALUES(
															  '".cleanvars($_POST['assig_status'])."'  
															, '".cleanvars($_POST['assig_title'])."'	 
															, '".cleanvars($_POST['assig_note'])."'
															, '".cleanvars($open_date)."'
															, '".cleanvars($close_date)."'
															, '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	
															, '".cleanvars($_POST['id_class'])."'	
															, '".cleanvars($_POST['id_section'])."'
															, '".cleanvars($id_subject)."'
															, '".cleanvars($id_teacher)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, NOW()
														)"
								);
		$assig_id = $dblms->lastestid();
		// FILE UPLOAD
		if(!empty($_FILES['assig_file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["assig_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/assignments/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['assig_title'])).'_'.$assig_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['assig_title'])).'_'.$assig_id.".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".ASSIGNMENT."
																SET assig_file 	= '".$img_fileName."'
																WHERE  assig_id	= '".cleanvars($assig_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['assig_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// REMARKS
		if($sqllms) { 
			$remarks = 'Add Assignment ID: "'.cleanvars($_POST['assig_title']).'"';
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
			header("Location: assignment.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_assignment'])){
	$aray 		= explode("|", $_POST['id_subject']);
	$id_subject	= $aray[0];
	$id_teacher	= $aray[1];
	
	$sqllmscheck  = $dblms->querylms("SELECT assig_title
										FROM ".ASSIGNMENT." 
										WHERE assig_title	= '".cleanvars($_POST['assig_title'])."' 
										AND id_session		= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND id_section 		= '".cleanvars($_POST['id_section'])."'
										AND id_subject 		= '".cleanvars($id_subject)."'
										AND id_teacher 		= '".cleanvars($id_teacher)."'
										AND id_campus  		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND assig_id	   != '".cleanvars($_POST['assig_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: assignment.php", true, 301);
		exit();
	}else{
		$open_date = date('Y-m-d' , strtotime(cleanvars($_POST['open_date'])));
		$close_date = date('Y-m-d' , strtotime(cleanvars($_POST['close_date'])));

		$sqllms  = $dblms->querylms("UPDATE ".ASSIGNMENT." SET  
													  assig_status		= '".cleanvars($_POST['assig_status'])."'
													, assig_title		= '".cleanvars($_POST['assig_title'])."'
													, assig_note		= '".cleanvars($_POST['assig_note'])."' 
													, id_class			= '".cleanvars($_POST['id_class'])."' 
													, id_section		= '".cleanvars($_POST['id_section'])."' 
													, id_subject		= '".cleanvars($id_subject)."' 
													, open_date			= '".cleanvars($open_date)."' 
													, close_date		= '".cleanvars($close_date)."' 
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify		= NOW()
													  WHERE assig_id	= '".cleanvars($_POST['assig_id'])."'
									");
		// FILE UPLOAD
		if(!empty($_FILES['assig_file']['name'])){
			$path_parts 	= pathinfo($_FILES["assig_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/assignments/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['assig_title'])).'_'.$_POST['assig_id'].".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['assig_title'])).'_'.$_POST['assig_id'].".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".ASSIGNMENT."
																SET assig_file	= '".$img_fileName."'
																WHERE  assig_id	= '".cleanvars($_POST['assig_id'])."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['assig_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// REMARKS
		if($sqllms){
			$remarks = 'Updated Assignment ID: "'.cleanvars($_POST['assig_id']).'"';
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
			header("Location: assignment.php", true, 301);
			exit();
		}
	}
}
?>