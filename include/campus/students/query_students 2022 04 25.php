<?php 
//----------------Student insert record--------------------
if(isset($_POST['submit_student'])) { 
	
	$sqllmscheck  = $dblms->querylms("SELECT std_id
										FROM ".STUDENTS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND std_name = '".cleanvars($_POST['std_name'])."' AND std_phone = '".cleanvars($_POST['std_phone'])."'
										AND std_rollno = '".cleanvars($_POST['std_rollno'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: students.php", true, 301);
		exit();
	} else {
		
		//------------Date variable--------------------------
		$dob = date('Y-m-d' , strtotime(cleanvars($_POST['std_dob'])));
		$admissiondate = date('Y-m-d' , strtotime(cleanvars($_POST['std_admissiondate'])));
		$admission_year = date('Y' , strtotime(cleanvars($_POST['std_admissiondate'])));

		//For Campus Short Code
		$sqllmscampus = $dblms->querylms("SELECT campus_code FROM ".CAMPUS." WHERE campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
		$value_campus = mysqli_fetch_array($sqllmscampus);

		$campus_code = str_replace('LHS-',"",$value_campus['campus_code']);
		$student_name = substr($_POST['std_name'], 0, 3);

		//To Get Either Student Reg # exist or not
		$sqllmsstudentregno = $dblms->querylms("SELECT std_regno FROM ".STUDENTS." 
													WHERE std_regno LIKE '%".$campus_code."%'
													AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
													ORDER by std_regno DESC LIMIT 1 ");
		$value_regno = mysqli_fetch_array($sqllmsstudentregno);

		if(mysqli_num_rows($sqllmsstudentregno) < 1) {
			$regno	= $student_name.'-'.$admission_year.'-'.$campus_code.'-00001';
		} else  {
			$last_num= substr($value_regno['std_regno'], -5);
			$regno = $student_name.'-'.$admission_year.'-'.$campus_code.'-'.($last_num +1);
		}

		$sqllms  = $dblms->querylms("INSERT INTO ".STUDENTS."(
															std_status								, 
															std_name								,
															std_fathername							,  
															std_gender								,  
															id_guardian								,  
															std_dob									,  
															std_bloodgroup							,
															form_no									,
															id_country								,
															std_city								,  
															std_nic									,  
															std_religion							,  
															std_phone								, 
															std_emergency_phone						,
															std_whatsapp							, 
															std_address								,  
															id_class								,  
															id_section								,  
															id_group								,  
															id_session								,  
															std_rollno								,  
															std_regno								,  
															std_admissiondate						,
															id_campus							 	,
															id_added								,  
															date_added															
														)
													VALUES(
															'".cleanvars($_POST['std_status'])."'							, 
															'".cleanvars($_POST['std_name'])."'								,
															'".cleanvars($_POST['std_fathername'])."'						,
															'".cleanvars($_POST['std_gender'])."'							, 
															'".cleanvars($_POST['id_guardian'])."'							, 
															'".$dob."'														,
															'".cleanvars($_POST['std_bloodgroup'])."'						, 
															'".cleanvars($_POST['form_no'])."'								, 
															'1'																, 
															'".cleanvars($_POST['std_city'])."'								, 
															'".cleanvars($_POST['std_nic'])."'								, 
															'".cleanvars($_POST['std_religion'])."'							, 
															'".cleanvars($_POST['std_phone'])."'							, 
															'".cleanvars($_POST['std_emergency_phone'])."'					, 
															'".cleanvars($_POST['std_whatsapp'])."'							, 
															'".cleanvars($_POST['std_address'])."'							, 
															'".cleanvars($_POST['id_class'])."'								, 
															'".cleanvars($_POST['id_section'])."'							, 
															'".cleanvars($_POST['id_group'])."'								, 
															'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
															'".cleanvars($_POST['std_rollno'])."'							, 
															'".cleanvars($regno)."'											, 
															'".$admissiondate."'											,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															NOW()
														)"
								);
		$std_id = $dblms->lastestid();
		//--------------------------------------
		if(!empty($_FILES['std_photo']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_photo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/images/students/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'_'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'_'.$std_id.".".($extension);
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".STUDENTS."
																SET std_photo = '".$img_fileName."'
														WHERE  std_id		  = '".cleanvars($std_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 
				move_uploaded_file($_FILES['std_photo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}


		if(isset($_POST['is_new'])){
			//--------- GET Fee Structure -------------------
			$sqllmsfeesetup	= $dblms->querylms("SELECT f.id, f.dated, f.id_class, f.id_section, f.id_session, d.id_cat, d.amount
														FROM ".FEESETUP." f 
														INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = f.id 	
														WHERE f.status = '1'
														AND f.id_class = '".cleanvars($_POST['id_class'])."'
														AND f.id_section = '".cleanvars($_POST['id_section'])."'
														AND f.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
														AND f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
														AND f.is_deleted != '1'
														ORDER BY f.id DESC");
			if(mysqli_num_rows($sqllmsfeesetup) > 0){
				$toalAmount = 0;
				while($value_feesetup = mysqli_fetch_array($sqllmsfeesetup)){

					$toalAmount = $toalAmount + $value_feesetup['amount'];
					$feeDetail[] = array('id_cat'=>$value_feesetup['id_cat'], 'amount'=>$value_feesetup['amount']);

				}

				//----------------- Make Challans ---------------------
				$challandate = date('Ym' , strtotime(cleanvars($admissiondate)));
				$challanMonth = date('n' , strtotime(cleanvars($admissiondate)));
				$issue_date = $admissiondate;
				$due_date = date('Y-m-d' , strtotime($issue_date. ' + 15 days'));
				//----------------- Challan Number ------------------------
				$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".FEES." 
												WHERE challan_no LIKE '".$challandate."%'  
												ORDER by challan_no DESC LIMIT 1 ");
				$rowchallan = mysqli_fetch_array($sqllmschallan);
				if(mysqli_num_rows($sqllmschallan) < 1) {
					$challano	= $challandate.'00001';
				} else  {
					$challano = ($rowchallan['challan_no'] +1);
				}

				//---------------- Grand Total ------------------------------
				$grandTotal = $toalAmount + $_POST['remaining_amount'];

				//---------------------- Make -------------------------
				$sqllmsFee  = $dblms->querylms("INSERT INTO ".FEES."(
																	status						, 
																	challan_no					, 
																	id_session					, 
																	id_month					,
																	id_class					, 
																	id_section					,
																	id_std						,
																	issue_date					,
																	due_date					,
																	scholarship					,
																	concession					,
																	fine						,
																	total_amount				,
																	prev_remaining_amount		,
																	note						, 
																	id_campus 					,
																	id_added					,
																	date_added
																)
															VALUES(
																	'2'																,
																	'".cleanvars($challano)."'										,
																	'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
																	'".cleanvars($challanMonth)."'									,
																	'".cleanvars($_POST['id_class'])."'								,
																	'".cleanvars($_POST['id_section'])."'							,
																	'".cleanvars($std_id)."'										,
																	'".cleanvars($issue_date)."'									, 
																	'".cleanvars($due_date)."'										,
																	'".cleanvars($_POST['scholarship'])."'							,
																	'".cleanvars($_POST['concession'])."'							,
																	'".cleanvars($values_fine['fine'])."'							,
																	'".cleanvars($grandTotal)."'									,
																	'".cleanvars($_POST['prev_remaining_amount'])."'				,
																	'".cleanvars($_POST['note'])."'									,
																	'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
																	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
																	Now()	
																)"
														);
				//------------------- Chllans Details ---------------------
				if($sqllmsFee) { 
					//-------------------------Get latest Id----------------------- 
					$challan_id = $dblms->lastestid();	
					//--------------------------------------
					foreach($feeDetail as $det){
						$sqllms  = $dblms->querylms("INSERT INTO ".FEE_PARTICULARS."(
																		id_fee			,
																		id_cat			,
																		amount						
																	)
																VALUES(
																		'".cleanvars($challan_id)."'			,
																		'".cleanvars($det['id_cat'])."'			,
																		'".cleanvars($det['amount'])."'			
																	)
																		");

					}
					//-------------------- Make Log ------------------------
					$remarks = "New Admission challan Genrate";
					$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																		id_user 				, 
																		filename				, 
																		action					,
																		challan_no 				,
																		dated					,
																		ip						,
																		remarks					, 
																		id_campus				
																	)
					
																VALUES(
																		'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
																		'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
																		'1'																	, 
																		'".cleanvars($challano)."'											,
																		NOW()																,
																		'".cleanvars($ip)."'												,
																		'".cleanvars($remarks)."'											,
																		'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																	) ");
				}
			}

			$sqllmsCls	= $dblms->querylms("SELECT class_name 
												FROM ".CLASSES."
												WHERE class_id = '".cleanvars($_POST['id_class'])."' LIMIT 1");
			$valueCls = mysqli_fetch_array($sqllmsCls);

			// for sending message
			require_once("include/functions/send_message.php");
				
			$phone = cleanvars($_POST['std_phone']);
			
			// Send Message
			$message = 'Dear Parents! Your Child '.cleanvars($_POST['std_name']).' Reg No. '.$regno.' has been enrolled in Class: '.$valueCls['class_name'].'. '.PHP_EOL.''.PHP_EOL.'Thanks '.PHP_EOL.'Regards: LAUREL HOME SCHOOLS';
			sendMessage($phone, $message);
		}
	

		// Admin Information
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $regno;
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		//------------hashing---------------
		$sqllms  = $dblms->querylms("INSERT INTO ".ADMINS."(
															adm_status						,  
															adm_logintype					, 
															adm_username					, 
															adm_salt						,
															adm_userpass					,
															adm_fullname					,
															adm_phone						,
															id_campus 	
															)
														VALUES(
															'".cleanvars($_POST['std_status'])."'		, 
															'5'											,
															'".cleanvars($regno)."'						,
															'".cleanvars($salt)."'						,
															'".cleanvars($password)."'					,
															'".cleanvars($_POST['std_name'])."'			,
															'".cleanvars($_POST['std_phone'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
															)"
								);

		//--------------------------------------
		$adm_id = $dblms->lastestid();	
		//--------------------------------------

		if($sqllms) { 
			$sqllmsestd  = $dblms->querylms("UPDATE ".STUDENTS." SET id_loginid = '".(cleanvars($adm_id))."' 
												WHERE std_id = '".cleanvars($std_id)."'");
			unset($sqllmsestd);

			$sqllmsInquiry	= $dblms->querylms("SELECT inq.id
													FROM ".ADMISSIONS_INQUIRY." inq 
													WHERE inq.form_no = '".cleanvars($_POST['form_no'])."'
													AND inq.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
													LIMIT 1");
			if(mysqli_num_rows($sqllmsInquiry) == 1){

				$rowInquiry = mysqli_fetch_array($sqllmsInquiry);

				$sqllmsUpdateInquiry  = $dblms->querylms("UPDATE ".ADMISSIONS_INQUIRY." SET  
														status				= '3'
												WHERE id					= '".cleanvars($rowInquiry['id'])."'");
			}
			// Make Log
			$remarks = 'Added Student ID: "'.cleanvars($std_id).'" detail';
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
			header("Location: students.php", true, 301);
			exit();
		}
	} // end checker
}
	
 
//----------------class update reocrd----------------------
if(isset($_POST['changes_student'])) { 
	//------------Date variable--------------------------
	$dob = date('Y-m-d' , strtotime(cleanvars($_POST['std_dob'])));
	$admissiondate = date('Y-m-d' , strtotime(cleanvars($_POST['std_admissiondate'])));
	$admission_year = date('Y' , strtotime(cleanvars($_POST['std_admissiondate'])));
	//------------------------------------------------

	//For Campus Short Code
	$sqllmscampus = $dblms->querylms("SELECT campus_code FROM ".CAMPUS." WHERE campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	$value_campus = mysqli_fetch_array($sqllmscampus);

	$campus_code = str_replace('LHS-',"",$value_campus['campus_code']);
	$student_name = substr($_POST['std_name'], 0, 3);

	//To Get Either Student Reg # exist or not
	$sqllmsstudentregno = $dblms->querylms("SELECT std_regno FROM ".STUDENTS." 
												WHERE std_regno LIKE '%".$campus_code."%'
												AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
												ORDER by std_regno DESC LIMIT 1 ");
	$value_regno = mysqli_fetch_array($sqllmsstudentregno);

	if(mysqli_num_rows($sqllmsstudentregno) < 1) {
		$regno	= $student_name.'-'.$admission_year.'-'.$campus_code.'-00001';
	} else  {
		$last_num= substr($value_regno['std_regno'], -5);
		$regno = $student_name.'-'.$admission_year.'-'.$campus_code.'-'.($last_num +1);
	}

	$sqllms  = $dblms->querylms("UPDATE ".STUDENTS." SET  
														std_status				= '".cleanvars($_POST['std_status'])."'
													, std_name				= '".cleanvars($_POST['std_name'])."' 
													, std_fathername			= '".cleanvars($_POST['std_fathername'])."' 
													, std_gender				= '".cleanvars($_POST['std_gender'])."' 
													, id_guardian				= '".cleanvars($_POST['id_guardian'])."' 
													, std_dob					= '".$dob."' 
													, std_bloodgroup			= '".cleanvars($_POST['std_bloodgroup'])."' 
													, std_city				= '".cleanvars($_POST['std_city'])."' 
													, std_nic					= '".cleanvars($_POST['std_nic'])."' 
													, std_religion			= '".cleanvars($_POST['std_religion'])."' 
													, std_phone				= '".cleanvars($_POST['std_phone'])."' 
													, std_emergency_phone		= '".cleanvars($_POST['std_emergency_phone'])."' 
													, std_whatsapp			= '".cleanvars($_POST['std_whatsapp'])."' 
													, std_address				= '".cleanvars($_POST['std_address'])."' 
													, id_class				= '".cleanvars($_POST['id_class'])."' 
													, id_section				= '".cleanvars($_POST['id_section'])."' 
													, id_group				= '".cleanvars($_POST['id_group'])."'
													, std_rollno				= '".cleanvars($_POST['std_rollno'])."' 
													, std_regno				= '".cleanvars($regno)."' 
													, std_admissiondate		= '".$admissiondate."'   
													, transport_fee			= '".cleanvars($_POST['transport_fee'])."'
													, id_campus				= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
													, id_modify				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify				= NOW()
														WHERE std_id			= '".cleanvars($_POST['std_id'])."'");
									
	// Photos
	if(!empty($_FILES['std_photo']['name'])) { 
		$path_parts 	= pathinfo($_FILES["std_photo"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 	= 'uploads/images/students/';
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'_'.$_POST['std_id'].".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'_'.$_POST['std_id'].".".($extension);
		if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
			$sqllmsupload  = $dblms->querylms("UPDATE ".STUDENTS."
															SET std_photo = '".$img_fileName."'
														WHERE  std_id		  = '".cleanvars($_POST['std_id'])."'");
			unset($sqllmsupload);
			$mode = '0644'; 
			move_uploaded_file($_FILES['std_photo']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}
	
	if($sqllms) { 
		$remarks = 'Updated Student ID: "'.cleanvars($_POST['std_id']).'" details';
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
		header("Location: students.php", true, 301);
		exit();
	}
}


//---------------- Delete record --------------------
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".STUDENTS." SET  
													is_deleted				= '1'
												  , id_deleted				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted				= '".$ip."'
												  , date_deleted			= NOW()
													WHERE std_id			= '".cleanvars($_GET['deleteid'])."'");

	//--------------------------------------
	if($sqllms) { 
		$remarks = 'Student Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
															'3'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															) ");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: students.php", true, 301);
		exit();
	}
}

?>