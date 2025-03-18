<?php 
// Make Class
if(isset($_POST['submit_class'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT class_name  
										FROM ".CLASSES." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND class_name = '".cleanvars($_POST['class_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: class.php", true, 301);
		exit();
	}else{ 
		$sqllms = $dblms->querylms("INSERT INTO ".CLASSES."(
															  class_status 
															, class_code
															, class_name  
															, id_classlevel  
															, id_added
															, date_added
														) VALUES (
															  '".cleanvars($_POST['class_status'])."' 
															, '".cleanvars($_POST['class_code'])."'
															, '".cleanvars($_POST['class_name'])."'
															, '".cleanvars($_POST['id_classlevel'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()
														)");
		if($sqllms) { 
			
			$class_id = $dblms->lastestid();

			if(!empty($_FILES['class_attachment']['name'])) { 
				$path_parts 	= pathinfo($_FILES["class_attachment"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/class_subjects/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['ACA_SESSION_NAME'].'-'.$_POST['class_name'])).'_'.$class_id.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['ACA_SESSION_NAME'].'-'.$_POST['class_name'])).'_'.$class_id.".".($extension);
				if(in_array($extension , array('pdf'))) { 
					$sqllmsupload  = $dblms->querylms("UPDATE ".CLASSES."
																SET class_attachment = '".$img_fileName."'
																WHERE class_id	     = '".cleanvars($class_id)."'");
					unset($sqllmsupload);
					$mode = '0644'; 
					move_uploaded_file($_FILES['class_attachment']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			$remarks = 'Add Class: "'.cleanvars($_POST['class_name']).'" detail';
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
			header("Location: class.php", true, 301);
			exit();
		}
	} // end checker
}

// Update Class
if(isset($_POST['changes_class'])) {
	$sqllmscheck  = $dblms->querylms("SELECT class_name  
										FROM ".CLASSES." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND class_name	= '".cleanvars($_POST['class_name'])."'
										AND class_id   != '".cleanvars($_POST['class_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: class.php", true, 301);
		exit();
	}else{ 
		$sqllms  = $dblms->querylms("UPDATE ".CLASSES." SET  	
														  class_status		= '".cleanvars($_POST['class_status'])."'
														, class_code		= '".cleanvars($_POST['class_code'])."' 
														, class_name		= '".cleanvars($_POST['class_name'])."' 
														, id_classlevel		= '".cleanvars($_POST['id_classlevel'])."' 
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
														, date_modify		= Now()
														  WHERE class_id	= '".cleanvars($_POST['class_id'])."'");

		if($sqllms){ 
			
			// File UPload
			if(!empty($_FILES['class_attachment']['name'])) { 
				$path_parts 	= pathinfo($_FILES["class_attachment"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/class_subjects/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['ACA_SESSION_NAME'].'-'.$_POST['class_name'])).'_'.$class_id.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['ACA_SESSION_NAME'].'-'.$_POST['class_name'])).'_'.$class_id.".".($extension);
				if(in_array($extension , array('pdf'))) { 
					$sqllmsupload  = $dblms->querylms("UPDATE ".CLASSES."
																SET class_attachment = '".$img_fileName."'
																WHERE class_id	     = '".cleanvars($_POST['class_id'])."'");
					unset($sqllmsupload);
					$mode = '0644'; 
					move_uploaded_file($_FILES['class_attachment']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// Log
			$remarks = 'Update Class: "'.cleanvars($_POST['class_name']).'" details';
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
			header("Location: class.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".CLASSES." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE class_id	= '".cleanvars($_GET['deleteid'])."'");

	//--------------------------------------
	if($sqllms) { 
		$remarks = 'Class Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: class.php", true, 301);
		exit();
	}
}