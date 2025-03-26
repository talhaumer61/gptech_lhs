<?php 
//PARENT LOGIN CREATE
if(isset($_POST['submit_parent'])) { 
	// echo'<pre>';print_r($_POST);exit;
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE adm_username = '".cleanvars($_POST['adm_username'])."' AND is_deleted = '0'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: parentlogin.php", true, 301);
		exit();
	} else { 
		//LOGIN INFORMATION

		//HASHING
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		$sqllms  = $dblms->querylms("INSERT INTO ".ADMINS."(
															 adm_status						  
															,adm_logintype					 
															,adm_username					 
															,adm_salt						
															,adm_userpass					
															,adm_fullname					
															,adm_email						 
															,adm_phone						
															,id_dept							
															,id_campus 	
														)
													VALUES(
															 '".cleanvars($_POST['adm_status'])."'		 
															,'4'											
															,'".cleanvars($_POST['adm_username'])."'		
															,'".cleanvars($salt)."'						
															,'".cleanvars($password)."'					
															,'".cleanvars($_POST['adm_fullname'])."'		
															,'".cleanvars($_POST['adm_email'])."'		
															,'".cleanvars($_POST['adm_phone'])."'		
															,'".cleanvars($_POST['id_dept'])."'			
															,'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
														)");

		$adm_id = $dblms->lastestid();	

		//-------------------------- Add Roles ----------------------
		if($sqllms) { 
			$sqllmsemply  = $dblms->querylms("UPDATE ".STUDENTS." SET id_loginid = '".(cleanvars($adm_id))."' 
													WHERE std_fathercnic = '".cleanvars($_POST['std_fathercnic'])."'");
			unset($sqllmsemply);
			$remarks = 'Add Parent Login: "'.cleanvars($_POST['adm_username']).'"';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																 id_user										 
																,filename									 
																,action										
																,dated										
																,ip											
																,remarks										 
																,id_campus				
															)
			
														VALUES(
																 '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	
																,'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'  
																,'1'											 
																,NOW()										
																,'".cleanvars($ip)."'						
																,'".cleanvars($remarks)."'						
																,'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: parentlogin.php", true, 301);
			exit();
		}
	} // end checker
}

//PARENT LOGIN UPDATE
if(isset($_POST['changes_parent'])) { 

	if(isset($_POST['adm_userpass'])) {
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
	} else {
		$passUpdate = "";
	}

	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
														adm_status			= '".cleanvars($_POST['adm_status'])."'
													, adm_phone			= '".cleanvars($_POST['adm_phone'])."'
													  $passUpdate 
													, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		
													, date_modify		= Now()
												WHERE adm_id			= '".cleanvars($_POST['adm_id'])."'");
	if($sqllms) { 
		$remarks = 'Update Admin: "'.cleanvars($_POST['adm_username']).'"';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															 id_user			 
															,filename		 
															,action			
															,dated			
															,ip				
															,remarks			 
															,id_campus				
														) VALUES (
															 '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			
															,'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 	 
															,'2'																 
															,NOW()															
															,'".cleanvars($ip)."'											
															,'".cleanvars($remarks)."'										
															,'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: parentlogin.php", true, 301);
		exit();
	}
}


// Export Logins
if(isset($_POST['export_logins'])) {

	// Depts Array Conversion
	$classes = implode(", ", $_POST['id_class']);

    // Excel file name for download
    $ExcelFileName = "Parent Logins " . date('Y-m-d') . ".xls";
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=$ExcelFileName.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo '<table>';
    //make the column headers what you want in whatever order you want
    echo '<tr>
			<th> Sr# </th>
			<th>Student Name</th> 
			<th>Class</th> 
			<th>Section</th> 
			<th>Parent Contact</th> 
			<th>User Name</th> 
			<th>Password</th>
        </tr>';
	// Query
	$sqllms	= $dblms->querylms("SELECT a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, s.std_photo, c.class_name, se.section_name
									FROM ".ADMINS." a
									INNER JOIN ".STUDENTS."  	  s  ON s.id_loginid 	= a.adm_id
									INNER JOIN ".CLASSES."   	  c  ON c.class_id 		= s.id_class
									LEFT JOIN ".CLASS_SECTIONS."  se  ON se.section_id 	= s.id_section
									WHERE a.adm_status = '1' AND a.adm_logintype = '5' 
									AND a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
									AND c.class_id IN (".$classes.")
									ORDER BY c.class_id ASC");
    //loop the query data to the table in same order as the headers
	$srno = 0;
    while ($row = mysqli_fetch_assoc($sqllms)) {
		$srno++;
        echo'<tr>
				<td>'.$srno.'</td> 
				<td>'.$row["adm_fullname"].'</td> 
				<td>'.$row["class_name"].'</td> 
				<td>'.$row["section_name"].'</td> 
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
if(isset($_GET['deleteid']))
{

	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
													is_deleted				=	'1'
												  , id_deleted				=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted				=	'".$ip."'
												  , date_deleted			=	NOW()
											  		WHERE adm_id 			=	'".cleanvars($_GET['deleteid'])."'");

	if($sqllms) { 
		$remarks = 'Parent Login Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															 id_user										 
															,filename									 
															,action										
															,dated										
															,ip											
															,remarks										 
															,id_campus				
															)
		
													VALUES(
															 '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	
															,'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'  
															,'3'											 
															,NOW()										
															,'".cleanvars($ip)."'	
															,'".cleanvars($remarks)."'						
															,'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: parentlogin.php", true, 301);
		exit();
	}
}
?>