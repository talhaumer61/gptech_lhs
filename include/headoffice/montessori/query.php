<?php 
// INSERT DOWNLOAD
if(isset($_POST['submit_download'])) { 

	$sqllmscheck  = $dblms->querylms("SELECT mont_id  
										FROM ".MONTESSORI." 
										WHERE 
											id_type 	= '".cleanvars($_POST['id_type'])."' 
										AND
											mont_title 	= '".cleanvars($_POST['mont_title'])."' 
										AND 
											is_deleted = '0'
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: montessori.php", true, 301);
		exit();
	} else { 

		$sqllms  = $dblms->querylms("INSERT INTO ".MONTESSORI."(
																	  `mont_status`
																	, `id_type`
																	, `mont_date`
																	, `mont_title`
																	, `mont_resource_person`
																	, `mont_youtube_code`
																	, `mont_description`
																	, id_added												
																	, date_added
																	, is_deleted 	
																)
															VALUES(
																		'".cleanvars($_POST['mont_status'])."'				
																	, '".cleanvars($_POST['id_type'])."'				
																	, '".cleanvars($_POST['mont_date'])."'				
																	, '".cleanvars($_POST['mont_title'])."'				
																	, '".cleanvars($_POST['mont_resource_person'])."'				
																	, '".cleanvars($_POST['mont_youtube_code'])."'				
																	, '".cleanvars($_POST['mont_description'])."'							
																	, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	
																	, date('Y-m-d h:i:s')
																	, 0
																)"
								);
							
		$id = $dblms->lastestid();

		if($sqllms) { 
			$remarks = 'Add Montessori Download #: '.$id.' detail';
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
			header("Location: montessori.php", true, 301);
			exit();
		}
	}
} 

// UPDATE DOWNLOADS
if(isset($_POST['change_video'])) { 

	$sqllmscheck  = $dblms->querylms("SELECT mont_id  
										FROM ".MONTESSORI." 
										WHERE mont_title = '".cleanvars($_POST['mont_title'])."' 
										AND is_deleted = '0'
										AND mont_id != '".cleanvars($_POST['id'])."'
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: montessori.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("UPDATE ".MONTESSORI." SET  
																		  mont_status			= '".cleanvars($_POST['mont_status'])."'
																		, id_type				= '".cleanvars($_POST['id_type'])."'
																		, mont_title			= '".cleanvars($_POST['mont_title'])."' 
																		, mont_resource_person	= '".cleanvars($_POST['mont_resource_person'])."' 
																		, mont_youtube_code		= '".cleanvars($_POST['mont_youtube_code'])."' 
																		, mont_description		= '".cleanvars($_POST['mont_description'])."' 
																		, id_modify				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, date_modify			= NOW()
																		WHERE mont_id			= '".cleanvars($_POST['id'])."'
								   ");
		$id = $_POST['id'];


		if($sqllms) { 
			$remarks = 'Update Montessori Download #: '.$id.' details';
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
			header("Location: montessori.php", true, 301);
			exit();
		}

	}
}

// DELETE DOWNLOADS
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".MONTESSORI." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE mont_id	= '".cleanvars($_GET['deleteid'])."'
								");

	if($sqllms) { 
		$remarks = 'Montessori Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: montessori.php", true, 301);
		exit();
	}
}