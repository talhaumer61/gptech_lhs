<?php 
// Fine insert record
if(isset($_POST['submit_fine'])) { 
	$date = date('Y-m-d' , strtotime(cleanvars($_POST['date'])));
	$sqllmscheck  = $dblms->querylms("SELECT id_std, id_cat, date, id_session, id_campus
										FROM ".SCHOLARSHIP." 
										WHERE id_std = '".cleanvars($_POST['id_std'])."' AND id_cat = '".cleanvars($_POST['id_cat'])."'AND date = '".cleanvars($date)."'
										AND id_session = '".cleanvars($_POST['id_session'])."' AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: scholarship.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".SCHOLARSHIP."(
															status					, 
															id_type					, 
															amount					,
															date					,
															id_cat					, 
															id_class				,
															id_std					,
															id_session				, 
															note					,
															id_campus				,
															id_added				,
															date_added			 	
														)
													VALUES(
															'".cleanvars($_POST['status'])."'								, 
															'3'																,
															'".cleanvars($_POST['amount'])."'								, 
															'".cleanvars($date)."'											, 
															'".cleanvars($_POST['id_cat'])."'								, 
															'".cleanvars($_POST['id_class'])."'								, 
															'".cleanvars($_POST['id_std'])."'								, 
															'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	,
															'".cleanvars($_POST['note'])."'									,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															NOW()
														)"
								);
		if($sqllms) { 
			$remarks = 'Add Fine: "'.cleanvars($_POST['id_std']).'" detail';
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
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: fine.php", true, 301);
			exit();
		}
	} // end checker
} 

// Fine Update reocrd
if(isset($_POST['changes_fine'])) { 
	$date = date('Y-m-d' , strtotime(cleanvars($_POST['date'])));
	$sqllms  = $dblms->querylms("UPDATE ".SCHOLARSHIP." SET  
														status		= '".cleanvars($_POST['status'])."'
													,	amount		= '".cleanvars($_POST['amount'])."' 
													,	date		= '".cleanvars($date)."' 
													, id_cat		= '".cleanvars($_POST['id_cat'])."'	
													, id_class		= '".cleanvars($_POST['id_class'])."'	
													, id_std		= '".cleanvars($_POST['id_std'])."' 
													, note			= '".cleanvars($_POST['note'])."' 
													, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify	= NOW()
												WHERE id			= '".cleanvars($_POST['id'])."'");

	if($sqllms) { 
		$remarks = 'Update Fine: "'.cleanvars($_POST['id_std']).'" details';
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
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: fine.php", true, 301);
			exit();
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".SCHOLARSHIP." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE id		= '".cleanvars($_GET['deleteid'])."'
								");

	if($sqllms) { 
		$remarks = 'Fine Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: fine.php", true, 301);
		exit();
	}
}
?>