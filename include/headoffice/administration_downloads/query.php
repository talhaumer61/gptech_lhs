<?php 
// INSERT DOWNLOAD
if(isset($_POST['submit_download'])) { 

	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".ADMINISTRATION_DOWNLOAD." 
										WHERE title = '".cleanvars($_POST['title'])."' 
										AND is_deleted = '0'
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: administration_downloads.php", true, 301);
		exit();
	} else { 
		if($_POST['id_type'] == '3'){
			$youtube_code = $_POST['youtube_code'];
		}else{
			$youtube_code = '';
		}

		$sqllms  = $dblms->querylms("INSERT INTO ".ADMINISTRATION_DOWNLOAD."(
																				  status												
																				, id_type												
																				, title										 
																				, youtube_code										 
																				, description											
																				, id_added												
																				, date_added 	
																			)
																		VALUES(
																				  '".cleanvars($_POST['status'])."'						
																				, '".cleanvars($_POST['id_type'])."'						
																				, '".cleanvars($_POST['title'])."'				
																				, '".$youtube_code."'										
																				, '".cleanvars($_POST['description'])."'				
																				, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	
																				, NOW()
																			)"
								);
							
		$id = $dblms->lastestid();

		if(!empty($_FILES['file']['name']) && $_POST['id_type'] != '3') { 
			$path_parts 	= pathinfo($_FILES["file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/administration_downloads/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['title'])).'-'.$id.".".($extension);
			$fileName	= to_seo_url(cleanvars($_POST['title'])).'-'.$id.".".($extension);

			$sqllmsupload  = $dblms->querylms("UPDATE ".ADMINISTRATION_DOWNLOAD."
														SET file	= '".$fileName."'
														WHERE id	= '".cleanvars($id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 
			move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}

		if($sqllms) { 
			$remarks = 'Add Administration Download #: '.$id.' detail';
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
			header("Location: administration_downloads.php", true, 301);
			exit();
		}
	}
} 

// UPDATE DOWNLOADS
if(isset($_POST['change_video'])) { 

	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".ADMINISTRATION_DOWNLOAD." 
										WHERE title = '".cleanvars($_POST['title'])."' 
										AND is_deleted = '0'
										AND id != '".cleanvars($_POST['id'])."'
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: administration_downloads", true, 301);
		exit();
	} else { 
		if($_POST['id_type'] == '3'){
			$youtube_code = $_POST['youtube_code'];
		}else{
			$youtube_code = '';
		}
		$sqllms  = $dblms->querylms("UPDATE ".ADMINISTRATION_DOWNLOAD." SET  
																		  status		= '".cleanvars($_POST['status'])."'
																		, id_type		= '".cleanvars($_POST['id_type'])."'
																		, title			= '".cleanvars($_POST['title'])."' 
																		, youtube_code	= '".$youtube_code."' 
																		, description	= '".cleanvars($_POST['description'])."' 
																		, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, date_modify	= NOW()
																	WHERE id			= '".cleanvars($_POST['id'])."'
								   ");
		$id = $_POST['id'];

		if(!empty($_FILES['file']['name']) && $_POST['id_type'] != '3') { 
			$path_parts 	= pathinfo($_FILES["file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/administration_downloads/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['title'])).'-'.$id.".".($extension);
			$fileName	= to_seo_url(cleanvars($_POST['title'])).'-'.$id.".".($extension);

			$sqllmsupload  = $dblms->querylms("UPDATE ".ADMINISTRATION_DOWNLOAD."
														SET file	= '".$fileName."'
														WHERE id	= '".cleanvars($id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 
			move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}

		if($sqllms) { 
			$remarks = 'Update Administration Download #: '.$id.' details';
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
			header("Location: administration_downloads.php", true, 301);
			exit();
		}

	}
}

// DELETE DOWNLOADS
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".ADMINISTRATION_DOWNLOAD." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE id	    = '".cleanvars($_GET['deleteid'])."'
								");

	if($sqllms) { 
		$remarks = 'Administration Download Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: administration_downloads.php", true, 301);
		exit();
	}
}