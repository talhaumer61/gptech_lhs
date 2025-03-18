<?php 
// INSERT CIRCULARS
if(isset($_POST['submit_circular'])) { 
	$dated = date('Y-m-d', strtotime($_POST['cir_dated']));
	$sqllmscheck  = $dblms->querylms("SELECT cir_id  
										FROM ".CIRCULARS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND cir_subject = '".cleanvars($_POST['cir_subject'])."'
										AND cir_dated = '".$dated."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: circular.php", true, 301);
		exit();
	} else { 
	
		$sqllms  = $dblms->querylms("INSERT INTO ".CIRCULARS."(
															cir_status						, 
															cir_subject						,
															cir_addressto					, 
															cir_dated						,
															cir_refrence					,
															cir_details						,
															cir_regards						, 
															id_designation					,
															id_session						,
															id_campus 	
														)
													VALUES(
															'".cleanvars($_POST['cir_status'])."'						, 
															'".cleanvars($_POST['cir_subject'])."'						,
															'".cleanvars($_POST['cir_addressto'])."'					,
															'".cleanvars($dated)."'										,
															'".cleanvars($_POST['cir_refrence'])."'						,
															'".cleanvars($_POST['cir_details'])."'						,
															'".cleanvars($_POST['cir_regards'])."'						,
															'".cleanvars($_POST['id_designation'])."'					,
															'".$_SESSION['userlogininfo']['ACADEMICSESSION']."'			,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
														)"
								   );

		if($sqllms) { 

			$cir_id = $dblms->lastestid();

			if(!empty($_FILES['digital_signature']['name'])) { 
				$path_parts 	= pathinfo($_FILES["digital_signature"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/images/circulars/digital_signatures/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cir_regards'])).'_'.$cir_id.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['cir_regards'])).'_'.$cir_id.".".($extension);
				if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
					$sqllmsupload  = $dblms->querylms("UPDATE ".CIRCULARS."
																SET digital_signature	= '".$img_fileName."'
																WHERE cir_id 			= '".cleanvars($cir_id)."'");
					unset($sqllmsupload);
					$mode = '0644'; 
					move_uploaded_file($_FILES['digital_signature']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// ADD LOG
			$remarks 	= 'Add Circular: "'.cleanvars($cir_id).'" detail';
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
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 	, 
																'1'																, 
																NOW()															,
																'".cleanvars($ip)."'											,
																'".cleanvars($remarks)."'										,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: circular.php", true, 301);
			exit();
		}
	} 
} 

// UPDATE CIRCULARS
if(isset($_POST['changes_circular'])) { 
	$dated = date('Y-m-d', strtotime($_POST['cir_dated']));
	$sqllmscheck  = $dblms->querylms("SELECT cir_id  
										FROM ".CIRCULARS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND cir_subject = '".cleanvars($_POST['cir_subject'])."'
										AND cir_dated   = '".$dated."' 
										AND cir_id	   != '".cleanvars($_POST['cir_id'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: circular.php", true, 301);
		exit();
	} else {
		$sqllms  = $dblms->querylms("UPDATE ".CIRCULARS." SET  
														cir_status		= '".cleanvars($_POST['cir_status'])."'
													, cir_subject		= '".cleanvars($_POST['cir_subject'])."' 
													, cir_addressto		= '".cleanvars($_POST['cir_addressto'])."' 
													, cir_dated			= '".cleanvars($dated)."' 
													, cir_refrence		= '".cleanvars($_POST['cir_refrence'])."' 
													, cir_details		= '".cleanvars($_POST['cir_details'])."' 
													, cir_regards		= '".cleanvars($_POST['cir_regards'])."' 
													, id_designation	= '".cleanvars($_POST['id_designation'])."' 
													, id_session		= '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
													, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												WHERE cir_id			= '".cleanvars($_POST['cir_id'])."'
									");

		if(!empty($_FILES['digital_signature']['name'])) { 
			$path_parts 	= pathinfo($_FILES["digital_signature"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/images/circulars/digital_signatures/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cir_regards'])).'_'.$_POST['cir_id'].".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['cir_regards'])).'_'.$_POST['cir_id'].".".($extension);
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".CIRCULARS."
															SET digital_signature	= '".$img_fileName."'
															WHERE cir_id 			= '".cleanvars($_POST['cir_id'])."'");
				unset($sqllmsupload);
				$mode = '0644'; 
				move_uploaded_file($_FILES['digital_signature']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		if($sqllms) { 

			$remarks 	= 'Update Circular: "'.cleanvars($_POST['cir_id']).'" details';
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
				$_SESSION['msg']['type'] 	= 'info';
				header("Location: circular.php", true, 301);
				exit();
		}
	}
}

// DELETE CIRCULARS
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".CIRCULARS." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE cir_id	= '".cleanvars($_GET['deleteid'])."'
								");

	if($sqllms) { 
		$remarks = 'Circulars Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: circular.php", true, 301);
		exit();
	}
}

