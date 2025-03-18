<?php 
//----------------Particulars Insert record----------------------
if(isset($_POST['submit_particular'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT cat_name
										FROM ".ACADEMIC_PARTICULARS." 
										WHERE cat_name = '".cleanvars($_POST['cat_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: academiccalender_particulars.php", true, 301);
		exit();
//--------------------------------------
	} else { 
	$sqllms  = $dblms->querylms("INSERT INTO ".ACADEMIC_PARTICULARS."(
														cat_status												,
														cat_name												,														
														cat_ordering											,														
														id_added												,														
														date_added														
													  )
	   											VALUES(
														'".cleanvars($_POST['cat_status'])."'					,
														'".cleanvars($_POST['cat_name'])."'						,																
														'".cleanvars($_POST['cat_ordering'])."'					,																
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,																	
														NOW()																	
													  )"
							);
//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Add Academic Calender Particular: "'.cleanvars($_POST['cat_name']).'" detail';
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
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: academiccalender_particulars.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------Particulars Update reocrd----------------------
if(isset($_POST['changes_particular'])) { 
$sqllms  = $dblms->querylms("UPDATE ".ACADEMIC_PARTICULARS." SET  
													cat_status			= '".cleanvars($_POST['cat_status'])."'
												  , cat_name			= '".cleanvars($_POST['cat_name'])."'  
												  , cat_ordering		= '".cleanvars($_POST['cat_ordering'])."'  
												  , id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'  
												  , date_modify			= NOW()  
   											  WHERE cat_id				= '".cleanvars($_POST['cat_id'])."'");
//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Update Academic Calendar Particular: "'.cleanvars($_POST['cat_name']).'" details';
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
//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: academiccalender_particulars.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}

//---------------- Delete reocrd----------------------
if(isset($_GET['deleteid'])) {
	$sqllms  = $dblms->querylms("UPDATE ".ACADEMIC_PARTICULARS." SET  
												  is_deleted	= '1'
												, id_deleted	= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted	= '".$ip."'
												, date_deleted	= NOW()
												  WHERE cat_id	= '".cleanvars($_GET['deleteid'])."'");
	// REMARKS
	if($sqllms){
		$remarks = 'Academic Calendar Particular Deleted #: "'.cleanvars($_GET['deleteid']).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
															  id_user 
															, filename 
															, action
															, challan_no
															, dated
															, ip
															, remarks 
															, id_campus				
														)
													VALUES(
															  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
															, '3'
															, '".cleanvars($_GET['deleteid'])."'
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: academiccalender_particulars.php", true, 301);
		exit();
	}
}

//----------------Academic Calender Insert record----------------------
if(isset($_POST['submit_calendar'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id_session
										FROM ".A_CALENAR." 
										WHERE id_session = '".cleanvars($_POST['id_session'])."' 
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: academic-calender.php", true, 301);
		exit();
//--------------------------------------
	} else { 
	$sqllms  = $dblms->querylms("INSERT INTO ".A_CALENAR."(
														status		,
														published	,
														id_session	,
														term		,
														term_start	,
														term_end	,
														id_added	,
														date_added													
													  )
	   											VALUES(
														'".cleanvars($_POST['status'])."'							,
														'".cleanvars($_POST['published'])."'						,
														'".cleanvars($_POST['id_session'])."'						,
														'".cleanvars($_POST['term'])."'								,
														'".cleanvars(date('Y-m-d' , strtotime($_POST['term_start'])))."',
														'".cleanvars(date('Y-m-d' , strtotime($_POST['term_end'])))."',
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
														 Now()																						
													  )
								");
//--------------------------------------
	if($sqllms) { 
//--------------------------------------
//-------------------Get latest Id---------------- 
$idsetup = $dblms->lastestid();	
//------------------------------------------------
	for($i=1; $i<= count($_POST['id_cat']); $i++){
		if((!empty($_POST['id_cat'][$i])) && (!empty($_POST['date_start'][$i]))){
		$sqllms  = $dblms->querylms("INSERT INTO ".ACADEMIC_DETAIL."(
															id_setup		,
															id_cat			,
															date_start		,
															date_end		,
															remarks															
														)
													VALUES(
															'".cleanvars($idsetup)."'					,
															'".cleanvars($_POST['id_cat'][$i])."'			,
															'".cleanvars(date('Y-m-d' , strtotime($_POST['date_start'][$i])))."',
															'".cleanvars(date('Y-m-d' , strtotime($_POST['date_end'][$i])))."'	,
															'".cleanvars($_POST['remarks'][$i])."'														
														)
									");
		}
	}
//------------------------------------------------
	$remarks = 'Add Academic Calender : "'.cleanvars($_POST['date_start']).'" detail';
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
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: academic-calender.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 

//----------------Academic Calendar Update reocrd----------------------
if(isset($_POST['changes_calendar'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".A_CALENAR." SET  
													  status				= '".cleanvars($_POST['status'])."'
													, published				= '".cleanvars($_POST['published'])."'
													, id_session			= '".cleanvars($_POST['id_session'])."' 
													, term					= '".cleanvars($_POST['term'])."' 
													, term_start			= '".cleanvars(date('Y-m-d' , strtotime($_POST['term_start'])))."' 
													, term_end				= '".cleanvars(date('Y-m-d' , strtotime($_POST['term_end'])))."' 
													, id_modify				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify			= NOW()   
												WHERE id					= '".cleanvars($_POST['id'])."'");
//------------------------------------------------
for($i=1; $i<= count($_POST['id_cat']); $i++){
	if((!empty($_POST['id_cat'][$i])) && (!empty($_POST['id_edit'][$i])) && (!empty($_POST['date_start'][$i]))){

		$sqllms  = $dblms->querylms("UPDATE ".ACADEMIC_DETAIL." SET  
															id_cat		= '".cleanvars($_POST['id_cat'][$i])."'  
														, date_start	= '".cleanvars(date('Y-m-d' , strtotime($_POST['date_start'][$i])))."' 
														, date_end		= '".cleanvars(date('Y-m-d' , strtotime($_POST['date_end'][$i])))."' 
														, remarks		= '".cleanvars($_POST['remarks'][$i])."' 
														WHERE id		= '".cleanvars($_POST['id_edit'][$i])."'");

	}
	elseif((!empty($_POST['id_cat'][$i])) && (!empty($_POST['date_start'][$i]))){

		$sqllms  = $dblms->querylms("INSERT INTO ".ACADEMIC_DETAIL."(
																		id_setup		,
																		id_cat			,
																		date_start		,
																		date_end		,
																		remarks															
																	)
																VALUES(
																		'".cleanvars($_POST['id'])."'					,
																		'".cleanvars($_POST['id_cat'][$i])."'			,
																		'".cleanvars(date('Y-m-d' , strtotime($_POST['date_start'][$i])))."',
																		'".cleanvars(date('Y-m-d' , strtotime($_POST['date_end'][$i])))."'	,
																		'".cleanvars($_POST['remarks'][$i])."'														
																	)
															");

	}
}
//--------------------------------------

	if($sqllms) { 
//--------------------------------------
	$remarks = 'Update Academic Calendar : "'.cleanvars($_POST['date_start']).'" details';
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
//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: academic-calender.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}
?>