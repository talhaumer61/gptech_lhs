<?php 
// Insert record
if(isset($_POST['submit_subject'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT subject_code  
										FROM ".CLASS_SUBJECTS." 
										WHERE subject_code = '".cleanvars($_POST['subject_code'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: classsubjects.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".CLASS_SUBJECTS."(
															subject_status							, 
															subject_code							,
															subject_name							, 
															weekly_period							,  
															monthly_totalmarks						,  
															monthly_passmarks						,
															term_totalmarks							,  
															term_passmarks							,  
															subject_type							,  
															instruction_medium						,
															id_cat									,
															id_class								,
															id_added								,
															date_added	
														) VALUES (
															'".cleanvars($_POST['subject_status'])."'				, 
															'".cleanvars($_POST['subject_code'])."'					,
															'".cleanvars($_POST['subject_name'])."'					,
															'".cleanvars($_POST['weekly_period'])."'				,
															'".cleanvars($_POST['monthly_totalmarks'])."'			,
															'".cleanvars($_POST['monthly_passmarks'])."'			,
															'".cleanvars($_POST['term_totalmarks'])."'				,
															'".cleanvars($_POST['term_passmarks'])."'				,
															'".cleanvars($_POST['subject_type'])."'					,
															'".cleanvars($_POST['instruction_medium'])."'			,
															'".cleanvars($_POST['id_cat'])."'						,
															'".cleanvars($_POST['id_class'])."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															Now()
														)");
		if($sqllms) { 
			$remarks = 'Add Subjects: "'.cleanvars($_POST['subject_name']).'" detail';
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
																'".cleanvars($remarks)."'						,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: classsubjects.php", true, 301);
			exit();
		}
	}
} 

// update reocrd
if(isset($_POST['changes_subject'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".CLASS_SUBJECTS." SET  
													  subject_status		= '".cleanvars($_POST['subject_status'])."'
													, subject_code			= '".cleanvars($_POST['subject_code'])."' 
													, subject_name			= '".cleanvars($_POST['subject_name'])."' 
													, weekly_period			= '".cleanvars($_POST['weekly_period'])."' 
													, monthly_totalmarks	= '".cleanvars($_POST['monthly_totalmarks'])."' 
													, monthly_passmarks		= '".cleanvars($_POST['monthly_passmarks'])."' 
													, term_totalmarks		= '".cleanvars($_POST['term_totalmarks'])."' 
													, term_passmarks		= '".cleanvars($_POST['term_passmarks'])."' 
													, subject_type			= '".cleanvars($_POST['subject_type'])."' 												  
													, instruction_medium	= '".cleanvars($_POST['instruction_medium'])."' 												  
													, id_cat				= '".cleanvars($_POST['id_cat'])."' 												  
													, id_class				= '".cleanvars($_POST['id_class'])."' 
													, id_modify				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify			= Now()
												WHERE subject_id			= '".cleanvars($_POST['subject_id'])."'");
	if($sqllms) { 
		$remarks = 'Update Subjects: "'.cleanvars($_POST['subject_name']).'" details';
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
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )
									");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: classsubjects.php", true, 301);
		exit();
	}
}

