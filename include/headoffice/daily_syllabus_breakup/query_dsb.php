<?php 
//----------------Syllabus insert record----------------------
if(isset($_POST['submit_syllabus'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT dsb_term, id_session, id_class, id_subject  
										FROM ".DAILY_SYLLABUS_BREAKUP." 
										WHERE dsb_type = '3' AND dsb_term = '".cleanvars($_POST['dsb_term'])."' 
										AND id_session = '".cleanvars($_POST['id_session'])."' AND id_month = '".cleanvars($_POST['id_month'])."'
										AND id_class = '".cleanvars($_POST['id_class'])."'
										AND id_subject = '".cleanvars($_POST['id_subject'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: daily_syllabus_breakup.php", true, 301);
		exit();
//--------------------------------------
	} else { 
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".DAILY_SYLLABUS_BREAKUP."(
														dsb_status						, 
														dsb_type						, 
														dsb_term						, 
														id_session							, 
														id_month							, 
														id_class							, 
														id_subject							,
														note								,
														id_added							, 
														date_added 	
													  )
	   											VALUES(
														'".cleanvars($_POST['dsb_status'])."'					, 
														'3'															, 
														'".cleanvars($_POST['dsb_term'])."'					, 
														'".cleanvars($_POST['id_session'])."'						,
														'".cleanvars($_POST['id_month'])."'							,
														'".cleanvars($_POST['id_class'])."'							,
														'".cleanvars($_POST['id_subject'])."'						,
														'".cleanvars($_POST['note'])."'								,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
														NOW()
													  )"
							);
							
	$dsb_id = $dblms->lastestid();
	//--------------------------------------
	if(!empty($_FILES['dsb_file']['name'])) { 
	//--------------------------------------
		$path_parts 	= pathinfo($_FILES["dsb_file"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 	= 'uploads/worksheet/';
	//--------------------------------------
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['id_class'])).'_'.$dsb_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['id_class'])).'_'.$dsb_id.".".($extension);
	//--------------------------------------
		if(in_array($extension , array('pdf','ppt', 'docx'))) { 
	//--------------------------------------
			$sqllmsupload  = $dblms->querylms("UPDATE ".DAILY_SYLLABUS_BREAKUP."
															SET dsb_file = '".$img_fileName."'
														 WHERE  dsb_id	  = '".cleanvars($dsb_id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 
	//--------------------------------------	
			move_uploaded_file($_FILES['dsb_file']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
	//--------------------------------------
		}
	//--------------------------------------
	}
//-----------------------end---------------

//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Add Syllabus: "'.cleanvars($_POST['id_class']).'" "'.cleanvars($_POST['id_subject']).'" detail';
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
														  )
									");
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: daily_syllabus_breakup.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------Syllabs Update reocrd----------------------
if(isset($_POST['changes_syllabus'])) { 
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".DAILY_SYLLABUS_BREAKUP." SET  
													dsb_status		= '".cleanvars($_POST['dsb_status'])."'
												  ,	dsb_term		= '".cleanvars($_POST['dsb_term'])."'
												  , id_session			= '".cleanvars($_POST['id_session'])."'
												  , id_month			= '".cleanvars($_POST['id_month'])."' 
												  , id_class			= '".cleanvars($_POST['id_class'])."' 
												  , id_subject			= '".cleanvars($_POST['id_subject'])."' 
												  , note				= '".cleanvars($_POST['note'])."' 
												  , id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , date_modify			= NOW()
   											  WHERE dsb_id			= '".cleanvars($_POST['dsb_id'])."'");

//--------------------------------------										  
$dsb_id = $_POST['dsb_id'];
	//--------------------------------------
	if(!empty($_FILES['dsb_file']['name'])) { 
	//--------------------------------------
		$path_parts 	= pathinfo($_FILES["dsb_file"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 	= 'uploads/worksheet/';
	//--------------------------------------
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['id_class'])).'_'.$dsb_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['id_class'])).'_'.$dsb_id.".".($extension);
	//--------------------------------------
		if(in_array($extension , array('pdf','ppt', 'docx'))) { 
	//--------------------------------------
			$sqllmsupload  = $dblms->querylms("UPDATE ".DAILY_SYLLABUS_BREAKUP."
															SET dsb_file = '".$img_fileName."'
													 WHERE  dsb_id		  = '".cleanvars($dsb_id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 
	//--------------------------------------	
			move_uploaded_file($_FILES['dsb_file']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
	//--------------------------------------
		}
	//--------------------------------------
	}
//-----------------------end---------------

	if($sqllms) { 
//--------------------------------------
	$remarks = 'Update Syllabus: "'.cleanvars($_POST['id_class']).'" "'.cleanvars($_POST['id_subject']).'"details';
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
														  )
									");
//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: daily_syllabus_breakup.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}
