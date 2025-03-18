<?php 
// ADD DESIGNATION
if(isset($_POST['submit_designation'])) { 

	$sqllmscheck  = $dblms->querylms("SELECT designation_name  
										FROM ".DESIGNATIONS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND designation_name = '".cleanvars($_POST['designation_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: designation.php", true, 301);
		exit();
	} else { 

		$sqllms  = $dblms->querylms("INSERT INTO ".DESIGNATIONS."(
																designation_status						, 
																designation_code							,
																designation_name							,  
																id_campus 	
															)
														VALUES(
																'".cleanvars($_POST['designation_status'])."'		, 
																'".cleanvars($_POST['designation_code'])."'			,
																'".cleanvars($_POST['designation_name'])."'				,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
															)"
									);
							
		if($sqllms) { 
			
			$remarks = 'Add Designation: "'.cleanvars($_POST['designation_name']).'" detail';
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
			header("Location: designation.php", true, 301);
			exit();
			
		}
	}
} 

// UPDATE DESIGNATION
if(isset($_POST['changes_designation'])) { 
	
	$sqllms  = $dblms->querylms("UPDATE ".DESIGNATIONS." SET  
														designation_status		= '".cleanvars($_POST['designation_status'])."'
													, designation_code			= '".cleanvars($_POST['designation_code'])."' 
													, designation_name				= '".cleanvars($_POST['designation_name'])."' 
													, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												WHERE designation_id			= '".cleanvars($_POST['designation_id'])."'");
	if($sqllms) { 
		
		$remarks = 'Update Class: "'.cleanvars($_POST['designation_name']).'" details';
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
			header("Location: designation.php", true, 301);
			exit();
			
	}
	
}

// DELETE DESIGNATION
if(isset($_GET['deleteid']))
{
	$sqllms  = $dblms->querylms("UPDATE ".DESIGNATIONS." SET  
													is_deleted				= '1'
												  , id_deleted				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted				= '".$ip."'
												  , date_deleted			= NOW()
											  WHERE designation_id			= '".cleanvars($_GET['deleteid'])."'");

	if($sqllms) { 
		$remarks = 'Designation Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: designation.php", true, 301);
		exit();
	}
}

