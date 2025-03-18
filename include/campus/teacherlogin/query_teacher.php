<?php 
// Asdmin insert record
if(isset($_POST['submit_teacher'])){ 
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE adm_username = '".cleanvars($_POST['adm_username'])."' AND adm_logintype = '3' 
										AND is_deleted = '0' AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: teacherlogin.php", true, 301);
		exit();
	}else{ 

		// hashing
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for($round = 0; $round < 65536; $round++){
			$password = hash('sha256', $password . $salt);
		}

		$sqllms  = $dblms->querylms("INSERT INTO ".ADMINS."(
															  adm_status  
															, adm_logintype 
															, adm_username 
															, adm_salt
															, adm_userpass
															, adm_fullname
															, adm_email 
															, adm_phone
															, id_dept
															, id_campus
															, id_added
															, date_added
														)
													VALUES(
															  '".cleanvars($_POST['adm_status'])."' 
															, '3'
															, '".cleanvars($_POST['adm_email'])."'
															, '".cleanvars($salt)."'
															, '".cleanvars($password)."'
															, '".cleanvars($_POST['adm_fullname'])."'
															, '".cleanvars($_POST['adm_email'])."'
															, '".cleanvars($_POST['adm_phone'])."'
															, '".cleanvars($_POST['id_dept'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()	
														)" );
		$adm_id = $dblms->lastestid();	

		if($sqllms){ 
			$sqllmsemply  = $dblms->querylms("UPDATE ".EMPLOYEES." SET id_loginid = '".(cleanvars($adm_id))."' 
													WHERE emply_id 	  = '".cleanvars($_POST['id_employe'])."'");
			unset($sqllmsemply);

			$remarks = 'Add Teacher Login: "'.cleanvars($_POST['adm_username']).'"';
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
			header("Location: teacherlogin.php", true, 301);
			exit();
		}
	}
} 

// Admin update reocrd
if(isset($_POST['changes_teacher'])){ 

	if(isset($_POST['adm_userpass'])){
		// hashing
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		
		$passUpdate = "
						, adm_salt		= '".cleanvars($salt)."'
						, adm_userpass	= '".cleanvars($password)."'";
	}else{
		$passUpdate = "";
	}

	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
												  adm_status	= '".cleanvars($_POST['adm_status'])."' 
												, adm_phone		= '".cleanvars($_POST['adm_phone'])."'
												  $passUpdate 
												, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		
												, date_modify	= Now()
												  WHERE adm_id	= '".cleanvars($_POST['adm_id'])."'");
											  

	if($sqllms){ 
		$remarks = 'Update Admin: "'.cleanvars($_POST['adm_username']).'" details';
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
		header("Location: teacherlogin.php", true, 301);
		exit();
	}
}

// Export Logins
if(isset($_POST['export_logins'])){

	// Depts Array Conversion
	$depts = implode(", ", $_POST['id_depts']);

    // Excel file name for download
    $ExcelFileName = "Teacher Logins " . date('Y-m-d') . ".xls";
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=$ExcelFileName.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo '<table>';
    //make the column headers what you want in whatever order you want
    echo '<tr>
			<th> Sr# </th>
			<th>Teacher Name</th> 
			<th>Department</th> 
			<th>Contact</th> 
			<th>User Name</th> 
			<th>Password</th>
        </tr>';
	// Query
	$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, e.emply_photo, d.dept_name
									FROM ".ADMINS." a
									INNER JOIN ".EMPLOYEES." 	e ON e.id_loginid = a.adm_id
									INNER JOIN ".DEPARTMENTS."  d ON d.dept_id 	  = e.id_dept
									WHERE a.adm_status = '1' AND a.adm_logintype = '3' 
									AND a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
									AND d.dept_id IN (".$depts.")
									ORDER BY a.adm_username ASC");
    //loop the query data to the table in same order as the headers
	$srno = 0;
    while ($row = mysqli_fetch_assoc($sqllms)) {
		$srno++;
        echo'<tr>
				<td>'.$srno.'</td> 
				<td>'.$row["adm_fullname"].'</td> 
				<td>'.$row["dept_name"].'</td> 
				<td>'.$row["adm_phone"].'</td> 
				<td>'.$row["adm_username"].'</td> 
				<td></td> 
            </tr>';
    }
    echo '</table>';
    header("url=teacherlogin.php");
    exit;
}

//DELETE RECORD
if(isset($_GET['deleteid'])){

	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
													is_deleted				=	'1'
												  , id_deleted				=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted				=	'".$ip."'
												  , date_deleted			=	NOW()
											  		WHERE adm_id 			=	'".cleanvars($_GET['deleteid'])."'");

	if($sqllms) { 
		$remarks = 'Teacher Login Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
														)");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: teacherlogin.php", true, 301);
		exit();
	}
}

?>