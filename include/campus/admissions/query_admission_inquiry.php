<?php 
// INSERT RECORD
if(isset($_POST['submit_inquiry'])) {	
	$sqllmscheck  = $dblms->querylms("SELECT cell_no, id_class
										FROM ".ADMISSIONS_INQUIRY." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND cell_no = '".cleanvars($_POST['cell_no'])."' 
										AND id_class = '".cleanvars($_POST['id_class'])."' 
										AND name = '".cleanvars($_POST['name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: admission_inquiry.php", true, 301);
		exit();
	}else{

		$dated = date('Y-m-d');
		$dob = date('Y-m-d' , strtotime(cleanvars($_POST['dob'])));

		$sqllms  = $dblms->querylms("INSERT INTO ".ADMISSIONS_INQUIRY."(
															  status 
															, form_no
															, name
															, fathername
															, gender
															, cell_no 
															, address    
															, dated 
															, source  
															, note 
															, id_previousclass 
															, school   
															, id_class  
															, id_campus
															, id_added
															, date_added				
														)
													VALUES(
															  '".cleanvars($_POST['status'])."'	 
															, '".cleanvars($_POST['form_no'])."'	 
															, '".cleanvars($_POST['name'])."'
															, '".cleanvars($_POST['fathername'])."'
															, '".cleanvars($_POST['gender'])."'
															, '".cleanvars($_POST['cell_no'])."'
															, '".cleanvars($_POST['address'])."'
															, '".cleanvars($dated)."'
															, '".cleanvars($_POST['source'])."'
															, '".cleanvars($_POST['note'])."'
															, '".cleanvars($_POST['id_previousclass'])."'
															, '".cleanvars($_POST['school'])."'
															, '".cleanvars($_POST['id_class'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, NOW()
														)" );
		if($sqllms) { 
			
			// for sending message
			require_once("include/functions/send_message.php");
			
			$phone = cleanvars($_POST['cell_no']);
			
			// Send Message
			$message = 'We Appreciate Your Kind Visit. '.PHP_EOL.''.PHP_EOL.'Thanks '.PHP_EOL.'Regards: LAUREL HOME SCHOOLS';
			sendMessage($phone, $message);


			$remarks = 'Add Admission Inquiry: "'.cleanvars($_POST['cell_no']).'"';
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
			header("Location: admission_inquiry.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_inquiry'])){
	$sqllmscheck  = $dblms->querylms("SELECT cell_no, id_class
										FROM ".ADMISSIONS_INQUIRY." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND cell_no		= '".cleanvars($_POST['cell_no'])."' 
										AND id_class	= '".cleanvars($_POST['id_class'])."' 
										AND name		= '".cleanvars($_POST['name'])."'
										AND id 		   != '".cleanvars($_POST['id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: admission_inquiry.php", true, 301);
		exit();
	}else{

		$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
		$dob = date('Y-m-d' , strtotime(cleanvars($_POST['dob'])));

		$sqllms  = $dblms->querylms("UPDATE ".ADMISSIONS_INQUIRY." SET  
														  status			= '".cleanvars($_POST['status'])."'
														, name				= '".cleanvars($_POST['name'])."' 
														, fathername		= '".cleanvars($_POST['fathername'])."' 
														, gender			= '".cleanvars($_POST['gender'])."' 
														, dob				= '".cleanvars($dob)."' 
														, nic				= '".cleanvars($_POST['nic'])."' 
														, guardian			= '".cleanvars($_POST['guardian'])."' 
														, cell_no			= '".cleanvars($_POST['cell_no'])."' 
														, email				= '".cleanvars($_POST['email'])."' 
														, address			= '".cleanvars($_POST['address'])."' 
														, dated				= '".cleanvars($dated)."' 
														, source			= '".cleanvars($_POST['source'])."' 
														, note				= '".cleanvars($_POST['note'])."'
														, id_previousclass	= '".cleanvars($_POST['id_previousclass'])."'
														, school			= '".cleanvars($_POST['school'])."'
														, id_class			= '".cleanvars($_POST['id_class'])."'  
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
														, date_modify		= NOW() 
														  WHERE id			= '".cleanvars($_POST['id'])."'");
		if($sqllms) { 
			$remarks = 'Update Admission Inquiry: "'.cleanvars($_POST['name']).'"';
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
			header("Location: admission_inquiry.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) { 

	$sqllms  = $dblms->querylms("UPDATE ".ADMISSIONS_INQUIRY." SET  
														  is_deleted	= '1'
														, id_deleted	= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted	= '".$ip."'
														, date_deleted	= NOW()
													 	  WHERE id		= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		$remarks = 'Admission Inquiry Deleted ID: "'.cleanvars($_GET['id']).'" details';
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
		header("Location: admission_inquiry.php", true, 301);
		exit();
	}
}
?>