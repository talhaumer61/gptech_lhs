<?php 
// ADD QUALIFICATION LEVEL
if(isset($_POST['submit_qualification_level'])) { 


	$sqllmscheck  = $dblms->querylms("SELECT q_l_name  
										FROM ".QUALIFICATION_LEVELS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND q_l_name = '".cleanvars($_POST['q_l_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: qualification_level.php", true, 301);
		exit();
	} else { 

		$sqllms  = $dblms->querylms("INSERT INTO ".QUALIFICATION_LEVELS."(
																q_l_status						, 
																q_l_code						,
																q_l_name						,  
																id_campus 						,
																id_added 						,
																date_added 						
															)
														VALUES(
																'".cleanvars($_POST['q_l_status'])."'						, 
																'".cleanvars($_POST['q_l_code'])."'							,
																'".cleanvars($_POST['q_l_name'])."'							,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	,
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
																NOW()
															)"
									);
							
		if($sqllms) { 
			
			$remarks = 'Add Qualification Level: "'.cleanvars($_POST['q_l_name']).'" detail';
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
			header("Location: qualification_level.php", true, 301);
			exit();
			
		}
	}
} 

// UPDATE QUALIFICATION LEVEL
if(isset($_POST['changes_qualification_level'])) { 
	
	$sqllms  = $dblms->querylms("UPDATE ".QUALIFICATION_LEVELS." SET  
																q_l_status		= '".cleanvars($_POST['q_l_status'])."'
															, q_l_code			= '".cleanvars($_POST['q_l_code'])."' 
															, q_l_name			= '".cleanvars($_POST['q_l_name'])."' 
															, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
															, id_modified		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
															, date_modified		= NOW() 
															WHERE q_l_id		= '".cleanvars($_POST['q_l_id'])."'");
	if($sqllms) { 
		
		$remarks = 'Update Qualification Level: "'.cleanvars($_POST['q_l_name']).'" details';
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
															'2'															  , 
															NOW()														  ,
															'".cleanvars($ip)."'										  ,
															'".cleanvars($remarks)."'									  ,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )
									");
									
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: qualification_level.php", true, 301);
			exit();
			
	}
	
}

// DELETE QUALIFICATION LEVEL
if(isset($_GET['deleteid']))
{
	$sqllms  = $dblms->querylms("UPDATE ".QUALIFICATION_LEVELS." SET  
													is_deleted				= '1'
												  , id_deleted				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted				= '".$ip."'
												  , date_deleted			= NOW()
											  		WHERE q_l_id			= '".cleanvars($_GET['deleteid'])."'");

	if($sqllms) { 
		$remarks = 'Qualification Level Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
															'3'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: qualification_level.php", true, 301);
		exit();
	}
}

