<?php 
	// Cat insert record
	if(isset($_POST['submit_cat'])) { 
		$sqllmscheck  = $dblms->querylms("SELECT cat_name, cat_type, id_campus  
											FROM ".SCHOLARSHIP_CAT." 
											WHERE cat_name = '".cleanvars($_POST['cat_name'])."' AND cat_type = '2' AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
		if(mysqli_num_rows($sqllmscheck)) {
			$_SESSION['msg']['title'] 	= 'Error';
			$_SESSION['msg']['text'] 	= 'Record Already Exists';
			$_SESSION['msg']['type'] 	= 'error';
			header("Location: feeconcession_cat.php", true, 301);
			exit();
		} else { 
			$sqllms  = $dblms->querylms("INSERT INTO ".SCHOLARSHIP_CAT."(
																cat_status					, 
																cat_type					,
																cat_name					,
																cat_detail							 	
															)
														VALUES(
																'".cleanvars($_POST['cat_status'])."'						, 
																'2'															, 
																'".cleanvars($_POST['cat_name'])."'							, 
																'".cleanvars($_POST['cat_detail'])."'
															)"
									);
			if($sqllms) { 
				$remarks = 'Add Fee Concession Category: "'.cleanvars($_POST['cat_name']).'" detail';
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
				header("Location: feeconcession_cat.php", true, 301);
				exit();
			}
		} // end checker
	} 

	// Cat Update reocrd
	if(isset($_POST['changes_cat'])) { 
		$sqllms  = $dblms->querylms("UPDATE ".SCHOLARSHIP_CAT." SET  
															cat_status		= '".cleanvars($_POST['cat_status'])."'
														, cat_name		= '".cleanvars($_POST['cat_name'])."'	
														, cat_detail		= '".cleanvars($_POST['cat_detail'])."' 
													WHERE cat_id		= '".cleanvars($_POST['cat_id'])."'");

		if($sqllms) { 
			$remarks = 'Update  Fee Concession Category: "'.cleanvars($_POST['cat_name']).'" details';
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
				header("Location: feeconcession_cat.php", true, 301);
				exit();
		}
	}

	// Cat Delete Record
	if(isset($_GET['deleteid'])) { 
		
		$sqllms  = $dblms->querylms("UPDATE ".SCHOLARSHIP_CAT." SET  
															  is_deleted			= '1'
															, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, ip_deleted			= '".$ip."'
															, date_deleted			= NOW()
														 	  WHERE cat_id			= '".cleanvars($_GET['deleteid'])."'");
														 
		if($sqllms) { 

			$remarks = 'Fee Concession Category Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
																  )
											");
											
			$_SESSION['msg']['title'] 	= 'Warning';
			$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
			$_SESSION['msg']['type'] 	= 'warning';
			header("Location: feeconcession_cat.php", true, 301);
			exit();		
		}
	}
?>