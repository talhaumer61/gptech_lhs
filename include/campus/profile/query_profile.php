<?php 
// UPDATE PROFILE
if(isset($_POST['changes_profile'])) {
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
												  adm_fullname		= '".cleanvars($_POST['adm_fullname'])."' 
												, adm_email			= '".cleanvars($_POST['adm_email'])."'  
												, adm_phone			= '".cleanvars($_POST['adm_phone'])."' 
   												  WHERE adm_id		= '".cleanvars($_POST['adm_id'])."'");
	// FILE UPLOAD
	$adm_id = cleanvars($_POST['adm_id']);

	// PHOTO
	if(!empty($_FILES['adm_photo']['name'])){

		$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 		= 'uploads/images/admins/';
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'])).'_'.$adm_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'])).'_'.$adm_id.".".($extension);

		if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
			$sqllmsupload  = $dblms->querylms("UPDATE ".ADMINS."
														SET adm_photo	= '".$img_fileName."'
													 	WHERE  adm_id	= '".cleanvars($adm_id)."'");
			unset($sqllmsupload);
			$sqllmsuploadLogo = $dblms->querylms("UPDATE ".CAMPUS."
														SET campus_logo		= '".$img_fileName."'
														WHERE  campus_id	= '".cleanvars($_POST['campus_id'])."'");
			unset($sqllmsuploadLogo);
			$mode = '0644'; 	
			move_uploaded_file($_FILES['adm_photo']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}

	// PRINCIPAL SIGN
	if(!empty($_FILES['principal_sign']['name'])) { 

		$path_parts 	= pathinfo($_FILES["principal_sign"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 		= 'uploads/images/signature/principal/';
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'])).'_'.$adm_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'])).'_'.$adm_id.".".($extension);

		if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
			$sqllmsupload  = $dblms->querylms("UPDATE ".CAMPUS."
														SET principal_sign	= '".$img_fileName."'
													 	WHERE  campus_id	= '".cleanvars($_POST['campus_id'])."'");
			unset($sqllmsupload);
			$mode = '0644'; 	
			move_uploaded_file($_FILES['principal_sign']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}

	// REMARKS
	if($sqllms) { 
		$remarks = 'Update Profile: "'.cleanvars($_POST['adm_username']).'" details';
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
															, '2' 
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: profile.php", true, 301);
		exit();
	}
}

// UPDATE PASSWORD
if(isset($_POST['chnage_pass'])) {
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$pass = $_POST['cnfrm_pass'];
	$password = hash('sha256', $pass . $salt);
	for($round = 0; $round < 65536; $round++){
		$password = hash('sha256', $password . $salt);
	}
	
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET 
												  adm_salt			=  '".cleanvars($salt)."' 
												, adm_userpass		= '".cleanvars($password)."' 
   											  	  WHERE adm_id		= '".$_SESSION['userlogininfo']['LOGINIDA']."'
											  ");
	// REMARKS
	if($sqllms){
		$remarks = 'Update Profile: "'.cleanvars($_POST['adm_username']).'" details';
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
															, '2'
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: profile.php", true, 301);
		exit();
	}
}

// UPDATE BANK DETAIL
if(isset($_POST['chnagesBankDetails'])){
	$sqllms  = $dblms->querylms("UPDATE ".CAMPUS." SET 
											  bank_name			=  '".cleanvars($_POST['bank_name'])."' 
											, bank_abbreviation	=  '".cleanvars($_POST['bank_abbreviation'])."' 
											, bank_account_no	=  '".cleanvars($_POST['bank_account_no'])."' 
											, id_modify			=  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
											, date_modify		=  NOW()
											  WHERE  campus_id	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										");
	// REMARKS
	if($sqllms) { 
		$remarks = 'Update Profile: "'.cleanvars($_POST['adm_username']).'" details';
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
															, '2'
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: profile.php", true, 301);
		exit();
	}
}

// INSERT SOCIAL LINK
if(isset($_POST['submit_link'])){
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".SOCIAL_LINKS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id_social	= '".cleanvars($_POST['id_social'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: profile.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".SOCIAL_LINKS."(
														  id_social 
														, status 
														, link
														, id_campus
														, id_added
														, date_added
													  )
	   											VALUES(
														  '".cleanvars($_POST['id_social'])."'
														, '".cleanvars($_POST['status'])."'
														, '".cleanvars($_POST['link'])."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, NOW()
													)
									");
		// LATEST ID
		$latestId = $dblms->lastestid();
		
		// REMARKS
		if($sqllms){ 
			$remarks = 'Add Social Link: "'.cleanvars($latestId).'" detail';
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
																, '1' 
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: profile.php", true, 301);
			exit();
		}
	}
}

// UPDATE SOCIAL LINK
if(isset($_POST['update_link'])){	
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".SOCIAL_LINKS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id_social	= '".cleanvars($_POST['id_social'])."'
										AND is_deleted	= '0'
										AND id		   != '".cleanvars($_POST['id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: profile.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("UPDATE ".SOCIAL_LINKS." SET 
												  id_social			=  '".cleanvars($_POST['id_social'])."' 
												, status			=  '".cleanvars($_POST['status'])."' 
												, link				=  '".cleanvars($_POST['link'])."' 
												, id_campus			=  '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
												, id_modify			=  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, date_modify		=  NOW()
												  WHERE id			= '".cleanvars($_POST['id'])."'
											");											
		// LATEST ID
		$latestId = $_POST['id'];

		// REMARKS
		if($sqllms) { 
			$remarks = 'Update Social Link: "'.cleanvars($latestId).'" details';
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
																, '2'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: profile.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$sqllms  = $dblms->querylms("UPDATE ".SOCIAL_LINKS." SET  
														  is_deleted	= '1'
														, id_deleted	= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted	= '".$ip."'
														, date_deleted	= NOW()
													 	  WHERE id		= '".cleanvars($_GET['deleteid'])."'");
	// REMARKS
	if($sqllms){
		$remarks = 'Social Link Deleted ID: "'.cleanvars($_GET['id']).'" details';
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
		header("Location: profile.php", true, 301);
		exit();
	}
}
?>