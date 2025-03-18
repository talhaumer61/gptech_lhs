<?php 
//----------------update prfile----------------------
if(isset($_POST['changes_profile'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
											  adm_fullname	= '".cleanvars($_POST['adm_fullname'])."' 
											, adm_email		= '".cleanvars($_POST['adm_email'])."'  
											, adm_phone		= '".cleanvars($_POST['adm_phone'])."' 
											  WHERE adm_id	= '".cleanvars($_POST['adm_id'])."'");

	//------------------Image Rename and Storing--------------------
	$adm_id = cleanvars($_POST['adm_id']);
	if(!empty($_FILES['adm_photo']['name'])) { 
		$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 	= 'uploads/images/admins/';
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'])).'_'.$adm_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'])).'_'.$adm_id.".".($extension);
		if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
			$sqllmsupload  = $dblms->querylms("UPDATE ".ADMINS."
															SET adm_photo = '".$img_fileName."'
													 WHERE  adm_id		  = '".cleanvars($adm_id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 	
			move_uploaded_file($_FILES['adm_photo']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}
	//-----------------------end----------------

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

//---------------- Change Pass ----------------------
if(isset($_POST['chnage_pass'])){ 
	//------------hashing---------------
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$pass = $_POST['cnfrm_pass'];
	$password = hash('sha256', $pass . $salt);
	for ($round = 0; $round < 65536; $round++) {
		$password = hash('sha256', $password . $salt);
	}
	//------------hashing---------------

	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET 
										  adm_salt		=  '".cleanvars($salt)."' 
										, adm_userpass	= '".cleanvars($password)."' 
										  WHERE adm_id	= '".$_SESSION['userlogininfo']['LOGINIDA']."'
									");

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

//-------------- Bank insert record -----------------
if(isset($_POST['submit_bank'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT bank_id
										FROM ".BANKS." 
										WHERE id_type		= '".cleanvars($_POST['id_type'])."'
										AND account_number	= '".cleanvars($_POST['account_number'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: profile.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".BANKS."(
														  bank_status
														, id_type
														, bank_name
														, account_title
														, account_no
														, iban_no
														, branch_code
													)
	   											VALUES(
														  '2' 
														, '".cleanvars($_POST['id_type'])."'
														, '".cleanvars($_POST['bank_name'])."'
														, '".cleanvars($_POST['account_title'])."'
														, '".cleanvars($_POST['account_no'])."'
														, '".cleanvars($_POST['iban_no'])."'
														, '".cleanvars($_POST['branch_code'])."'
													)"
							);
		if($sqllms){
			$remarks = 'Add Bank to Recieve Funds: "'.cleanvars($_POST['bank_name']).'" detail';
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
			header("Location: profile.php", true, 301);
			exit();
		}
	} // end checker
}

//----------------Bank update reocrd-----------------
if(isset($_POST['update_bank'])){
	$sqlstatuscheck  = $dblms->querylms("SELECT bank_id
										FROM ".BANKS." 
										WHERE id_type		= '".cleanvars($_POST['id_type'])."'
										AND bank_status		= '1'
										AND bank_id		   != '".cleanvars($_POST['bank_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqlstatuscheck)>0){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'An Active Bank Already Exist';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: profile.php", true, 301);
		exit();
	}else{
		$sqllmscheck  = $dblms->querylms("SELECT bank_id
											FROM ".BANKS." 
											WHERE id_type		= '".cleanvars($_POST['id_type'])."'
											AND account_number	= '".cleanvars($_POST['account_number'])."'
											AND bank_id		   != '".cleanvars($_POST['bank_id'])."' LIMIT 1");
		if(mysqli_num_rows($sqllmscheck)){
			$_SESSION['msg']['title'] 	= 'Error';
			$_SESSION['msg']['text'] 	= 'Record Already Exists';
			$_SESSION['msg']['type'] 	= 'error';
			header("Location: profile.php", true, 301);
			exit();
		}else{
			$sqllms  = $dblms->querylms("UPDATE ".BANKS." SET  
													  bank_status	= '".cleanvars($_POST['bank_status'])."'
													, id_type		= '".cleanvars($_POST['id_type'])."'
													, bank_name		= '".cleanvars($_POST['bank_name'])."'
													, account_title	= '".cleanvars($_POST['account_title'])."'
													, account_no	= '".cleanvars($_POST['account_no'])."'
													, iban_no		= '".cleanvars($_POST['iban_no'])."'
													, branch_code	= '".cleanvars($_POST['branch_code'])."'
													  WHERE bank_id	= '".cleanvars($_POST['bank_id'])."'");
			if($sqllms){ 
				$remarks = 'Update Bank: "'.cleanvars($_POST['bank_name']).'" details';
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
	}
}

//---------------- Delete Bank reocrd----------------
if(isset($_GET['deleteid'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".BANKS." SET  
												  is_deleted	= '1'
												, id_deleted	= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted	= '".$ip."'
												, date_deleted	= NOW()
												  WHERE bank_id	= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		//-------------------- Make Log ------------------------
		$remarks = 'Bank Deleted #: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: profile.php", true, 301);
		exit();
	}
}
?>
