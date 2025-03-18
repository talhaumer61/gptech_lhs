<?php 
// INSERT RECORD
if(isset($_POST['submit_scheme'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type		= '3' 
										AND id_session		= '".cleanvars($_POST['id_session'])."' 
										AND id_exam			= '".cleanvars($_POST['id_exam'])."' 
										AND id_class		= '".cleanvars($_POST['id_class'])."' 
										AND id_sbuject_cat	= '".cleanvars($_POST['id_sbuject_cat'])."' 
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_scheme.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".EXAM_DOWNLOADS."(
																		  status 
																		, id_session
																		, id_exam
																		, id_class
																		, id_sbuject_cat
																		, note
																		, id_added 
																		, date_added
																	)
																	VALUES(
																		  '".cleanvars($_POST['status'])."'
																		, '".cleanvars($_POST['id_session'])."'
																		, '".cleanvars($_POST['id_exam'])."'
																		, '".cleanvars($_POST['id_class'])."'
																		, '".cleanvars($_POST['id_sbuject_cat'])."'
																		, '".cleanvars($_POST['note'])."'
																		, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, NOW()
																	)
									");
		// LATEST ID							
		$latestID = $dblms->lastestid();
		if(!empty($_FILES['file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/assessment_downloads/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_sbuject_cat'])).'_'.$latestID.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_sbuject_cat'])).'_'.$latestID.".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
															SET file	= '".$img_fileName."'
															WHERE id	= '".cleanvars($latestID)."'");
				unset($sqllmsupload);
				$mode = '0644'; 
				move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		if($sqllms) { 
			$remarks = 'Assessment Scheme Added ID: '.$latestID.'" detail';
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
			header("Location: exam_scheme.php", true, 301);
			exit();
		}
	}
} 

// UPDATE RECORD
if(isset($_POST['changes_scheme'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type		= '3' 
										AND id_session		= '".cleanvars($_POST['id_session'])."' 
										AND id_exam			= '".cleanvars($_POST['id_exam'])."' 
										AND id_class		= '".cleanvars($_POST['id_class'])."' 
										AND id_sbuject_cat	= '".cleanvars($_POST['id_sbuject_cat'])."' 
										AND id			   != '".cleanvars($_POST['id'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_scheme.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS." SET  
														  status			= '".cleanvars($_POST['status'])."'
														, id_session		= '".cleanvars($_POST['id_session'])."'   
														, id_exam			= '".cleanvars($_POST['id_exam'])."'   
														, id_class			= '".cleanvars($_POST['id_class'])."'   
														, id_sbuject_cat	= '".cleanvars($_POST['id_sbuject_cat'])."'  
														, note				= '".cleanvars($_POST['note'])."' 
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify		= NOW()
														  WHERE id			= '".cleanvars($_POST['id'])."'");

		$latestID = $_POST['id'];
		if(!empty($_FILES['file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/assessment_downloads/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_sbuject_cat'])).'_'.$latestID.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_sbuject_cat'])).'_'.$latestID.".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
																SET file	= '".$img_fileName."'
																WHERE id	= '".cleanvars($latestID)."'");
				unset($sqllmsupload);
				$mode = '0644'; 
				move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		if($sqllms) { 
			$remarks = 'Assessment Scheme Updated ID"'.cleanvars($latestID).'" details';
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
			header("Location: exam_scheme.php", true, 301);
			exit();
		}
	}
}
