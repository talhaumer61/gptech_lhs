<?php 
//----------------Asdmin insert record----------------------
if(isset($_POST['submit_campuslogin'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE adm_username	= '".cleanvars($_POST['adm_username'])."' 
										AND adm_logintype	= '2'
										AND is_deleted		= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuslogin.php", true, 301);
		exit();
	}else{ 
		//------------hashing---------------
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		//------------hashing---------------

		$values = array(
							  'adm_status'		=> cleanvars($_POST['adm_status'])
							, 'adm_type'		=> '1'
							, 'adm_logintype'	=> '2'
							, 'adm_username'	=> cleanvars($_POST['adm_username'])
							, 'adm_salt'		=> cleanvars($salt)
							, 'adm_userpass'	=> cleanvars($password)
							, 'adm_fullname'	=> cleanvars($_POST['adm_fullname'])
							, 'adm_email'		=> cleanvars($_POST['adm_email'])
							, 'adm_phone'		=> cleanvars($_POST['adm_phone'])
							, 'id_campus'		=> cleanvars($_POST['id_campus'])
							, 'id_added'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							, 'date_added'		=> date('Y-m-d H:i:s')
						);
		$sqlLogs = $dblms->Insert(ADMINS , $values);

		// REMARKS
		if($sqlLogs) { 
			$adm_id = $dblms->lastestid();
			$remarks = 'Add Campus Login: "'.cleanvars($_POST['adm_username']).'"';

			$values = array(
							  'id_user'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							, 'filename'	=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							, 'action'		=> '1'
							, 'dated'		=> date('Y-m-d H:i:s')
							, 'ip'			=> cleanvars($ip)
							, 'remarks'		=> cleanvars($remarks)
							, 'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
			$sqlLogs = $dblms->Insert(LOGS , $values);

			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: campuslogin.php", true, 301);
			exit();
		}
	}
} 

//----------------Admin update reocrd----------------------
if(isset($_POST['changes_campuslogin'])) {
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE adm_username	= '".cleanvars($_POST['adm_username'])."' 
										AND adm_logintype	= '2'
										AND is_deleted		= '0'
										AND adm_id		   != '".cleanvars($_POST['adm_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuslogin.php", true, 301);
		exit();
	}else{ 

		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}

		$values = array(
							  'adm_status'		=> cleanvars($_POST['adm_status'])
							, 'adm_fullname'	=> cleanvars($_POST['adm_fullname'])
							, 'adm_phone'		=> cleanvars($_POST['adm_phone'])
							, 'adm_email'		=> cleanvars($_POST['adm_email'])
							, 'adm_salt'		=> cleanvars($salt)
							, 'adm_userpass'	=> cleanvars($password)
							, 'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							, 'date_modify'		=> date('Y-m-d H:i:s')
						);
		$sqlUpdate = $dblms->Update(ADMINS , $values, "WHERE adm_id = '".cleanvars($_POST['adm_id'])."'");
		
		// REMARKS
		if($sqlUpdate) { 
			$remarks = 'Update Campus Login: "'.cleanvars($_POST['adm_username']).'"';

			$values = array(
								'id_user'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								, 'filename'	=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
								, 'action'		=> '2'
								, 'dated'		=> date('Y-m-d H:i:s')
								, 'ip'			=> cleanvars($ip)
								, 'remarks'		=> cleanvars($remarks)
								, 'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							);
			$sqlLogs = $dblms->Insert(LOGS , $values);
			
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: campuslogin.php", true, 301);
			exit();
		}
	}
}

//DELETE RECORD
if(isset($_GET['deleteid'])){

	// ARRAY
	$values = array(
					  'is_deleted'		=> '1'
					, 'ip_deleted'		=> cleanvars($ip)
					, 'id_deleted'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					, 'date_deleted'	=> date('Y-m-d H:i:s')
				);
	$sqlUpdate = $dblms->Update(ADMINS , $values, "WHERE adm_id = '".cleanvars($_GET['deleteid'])."'");
	
	// REMARKS
	if($sqlUpdate) { 
		$remarks = 'Campus Admin Login Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';

		$values = array(
							  'id_user'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							, 'filename'	=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							, 'action'		=> '3'
							, 'dated'		=> date('Y-m-d H:i:s')
							, 'ip'			=> cleanvars($ip)
							, 'remarks'		=> cleanvars($remarks)
							, 'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqlLogs = $dblms->Insert(LOGS , $values);
		
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: campuslogin.php", true, 301);
		exit();
	}
}
?>