<?php 
// ADD DEPARTMENT
if(isset($_POST['submit_department'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT dept_name  
									  FROM ".DEPARTMENTS." 
									  WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
								      AND dept_name = '".cleanvars($_POST['dept_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: department.php", true, 301);
		exit();
		
	} else { 
		
		$sqllms  = $dblms->querylms("INSERT INTO ".DEPARTMENTS."(
																dept_status						, 
																dept_code							,
																dept_name							,  
																id_campus 	
															)
														VALUES(
																'".cleanvars($_POST['dept_status'])."'		, 
																'".cleanvars($_POST['dept_code'])."'			,
																'".cleanvars($_POST['dept_name'])."'				,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
															)"
								   );
								
		if($sqllms) { 
			
			$remarks = 'Add Department: "'.cleanvars($_POST['dept_name']).'" detail';
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
			header("Location: department.php", true, 301);
			exit();
			
		}
	} 	
} 

// UPDATE DEPARTMENT
if(isset($_POST['changes_department'])) { 
	
	$sqllms  = $dblms->querylms("UPDATE ".DEPARTMENTS." SET  
														dept_status		= '".cleanvars($_POST['dept_status'])."'
													, dept_code			= '".cleanvars($_POST['dept_code'])."' 
													, dept_name				= '".cleanvars($_POST['dept_name'])."' 
													, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												WHERE dept_id			= '".cleanvars($_POST['dept_id'])."'");
	if($sqllms) { 
		$remarks = 'Update Class: "'.cleanvars($_POST['dept_name']).'" details';
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
			header("Location: department.php", true, 301);
			exit();
	}
}

// DELETE DESIGNATION
if(isset($_GET['deleteid']))
{
	$sqllms  = $dblms->querylms("UPDATE ".DEPARTMENTS." SET  
													is_deleted				= '1'
												  , id_deleted				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted				= '".$ip."'
												  , date_deleted			= NOW()
											  WHERE dept_id					= '".cleanvars($_GET['deleteid'])."'");

	if($sqllms) { 
		$remarks = 'Department Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: department.php", true, 301);
		exit();
	}
}

