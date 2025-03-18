<?php 
//----------------campus insert record----------------------
if(isset($_POST['submit_campus'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT campus_name, campus_regno_gov
										FROM ".CAMPUS."
										WHERE campus_name	= '".cleanvars($_POST['campus_name'])."'
										AND is_deleted		= '0' 
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuses.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".CAMPUS."(
															  campus_status
															, campus_regno_gov
															, campus_code
															, campus_name
															, campus_opendate
															, campus_address
															, campus_email
															, campus_phone
															, campus_head
															, campus_website
															, id_zone
															, id_added
															, date_added								
														)
													VALUES(
															  '".cleanvars($_POST['campus_status'])."'
															, '".cleanvars($_POST['campus_regno_gov'])."'
															, '".cleanvars($_POST['campus_code'])."'		
															, '".cleanvars($_POST['campus_name'])."'
															, '".cleanvars($_POST['campus_opendate'])."'
															, '".cleanvars($_POST['campus_address'])."'
															, '".cleanvars($_POST['campus_email'])."'
															, '".cleanvars($_POST['campus_phone'])."'
															, '".cleanvars($_POST['campus_head'])."'
															, '".cleanvars($_POST['campus_website'])."'
															, '".cleanvars($_POST['id_zone'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()	
														)"
								);
		//--------------------------------------
		$campus_id = $dblms->lastestid();
		//--------------------------------------
		if(!empty($_FILES['campus_logo']['name'])) { 
			$path_parts 	= pathinfo($_FILES["campus_logo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/images/campus/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['campus_regno'])).'_'.$campus_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['campus_regno'])).'_'.$campus_id.".".($extension);
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".CAMPUS."
															SET campus_logo = '".$img_fileName."'
														WHERE  campus_id	= '".cleanvars($campus_id)."'
													");
				unset($sqllmsupload);
				$mode = '0644'; 
				move_uploaded_file($_FILES['campus_logo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		if($sqllms) {
			
			$result	  = $api->add_customer($_POST['campus_name'], $_POST['campus_phone'], $_POST['campus_email'], $_POST['campus_address'], '', '', '', $_POST['campus_code']);
			
			$remarks = 'Add Campus: "'.cleanvars($_POST['campus_name']).'" detail';
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
			header("Location: campuses.php", true, 301);
			exit();
		}
	} // end checker
} 

//---------------- campus update reocrd ----------------------
if(isset($_POST['changes_campus'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT campus_name, campus_regno_gov
										FROM ".CAMPUS."
										WHERE campus_name	= '".cleanvars($_POST['campus_name'])."'
										AND is_deleted		= '0'
										AND campus_id		!= '".cleanvars($_POST['campus_id'])."' 
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campuses.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("UPDATE ".CAMPUS." SET  
														  campus_status		= '".cleanvars($_POST['campus_status'])."'
														, campus_regno_gov	= '".cleanvars($_POST['campus_regno_gov'])."' 
														, campus_code		= '".cleanvars($_POST['campus_code'])."'
														, campus_name		= '".cleanvars($_POST['campus_name'])."'
														, campus_opendate	= '".cleanvars($_POST['campus_opendate'])."'
														, campus_address	= '".cleanvars($_POST['campus_address'])."'
														, campus_email		= '".cleanvars($_POST['campus_email'])."'
														, campus_phone		= '".cleanvars($_POST['campus_phone'])."'
														, campus_head		= '".cleanvars($_POST['campus_head'])."'
														, campus_website	= '".cleanvars($_POST['campus_website'])."'
														, campus_logo		= '".cleanvars($_POST['campus_logo'])."'
														, id_zone			= '".cleanvars($_POST['id_zone'])."'
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify		= Now()
														  WHERE campus_id	= '".cleanvars($_POST['campus_id'])."'");
		$campus_id = cleanvars($_POST['campus_id']);

		if(!empty($_FILES['campus_logo']['name'])) { 
			$path_parts 	= pathinfo($_FILES["campus_logo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/images/campus/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['campus_regno'])).'_'.$campus_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['campus_regno'])).'_'.$campus_id.".".($extension);
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".CAMPUS."
															SET campus_logo = '".$img_fileName."'
														WHERE  campus_id	= '".cleanvars($campus_id)."'
													");
				unset($sqllmsupload);
				$mode = '0644'; 
				move_uploaded_file($_FILES['campus_logo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		if($sqllms) { 
			$remarks = 'Update Campus: "'.cleanvars($_POST['campus_name']).'" details';
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
			header("Location: campuses.php", true, 301);
			exit();
		}
	}
}

//---------------- Campus Royalty Setting ----------------------
if(isset($_POST['submit_royalty'])){ 

	// Seprate The Values
	$values = explode("|",$_POST['royalty_type']);
	$id_type = $values[0];
	$id_campus = $values[1];

	//Check If Royalty Exist
	$sqllmsRoyalty	= $dblms->querylms("SELECT id
												FROM ".ROYALTY_SETTING."
												WHERE id_campus		= '".cleanvars($id_campus)."'
												AND royalty_type	= '".cleanvars($id_type)."'
												AND is_deleted		= '0' ");
	if(mysqli_num_rows($sqllmsRoyalty) > 0){
		$valRoyalty = mysqli_fetch_array($sqllmsRoyalty);

		// Update Parent Royalty Table
		$sqllmsRoyalty  = $dblms->querylms("UPDATE ".ROYALTY_SETTING." SET  
												  royalty_type	= '".cleanvars($id_type)."'
												, grand_total	= '".cleanvars($_POST['grandTotal'])."'
												, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, date_modify	= Now()
												  WHERE id		= '".$valRoyalty['id']."'");

		if($sqllmsRoyalty) {
			for($i=0; $i<COUNT($_POST['id_particular']); $i++){

				// POST Vars
				$id_class = 0;
				$tuition_fee = 0;
				$no_of_std = 0;
				$amount_per_std = 0;
				$tuitionfee_percentage = 0;

				if(!empty($_POST['id_class'][$i])){
					$id_class = cleanvars($_POST['id_class'][$i]);
				}  
				if(!empty($_POST['tuition_fee'][$i])){
					$tuition_fee = cleanvars($_POST['tuition_fee'][$i]);
				} 
				if(!empty($_POST['stds'][$i])) { 
					$no_of_std = cleanvars($_POST['stds'][$i]);
				} 
				if(!empty($_POST['amount'][$i])) {

					$amount_per_std = cleanvars($_POST['amount'][$i]);
				}
				if(!empty($_POST['tuitionfee_percentage'][$i])) {

					$tuitionfee_percentage = cleanvars($_POST['tuitionfee_percentage'][$i]);
				}

				//Check If Record Exist
				$sqllmsRoyDetCheck	= $dblms->querylms("SELECT detail_id 
															FROM ".ROYALTY_SETTING_DET." 
															WHERE id_setup  = '".$valRoyalty['id']."'
															AND   id_particular = '".cleanvars($_POST['id_particular'][$i])."'
															AND   id_class = '".cleanvars($id_class)."'");
				
				if(mysqli_num_rows($sqllmsRoyDetCheck)>0){
					//If Exist Then Update 
					$sqllmsRoyDetUpdate  = $dblms->querylms("UPDATE ".ROYALTY_SETTING_DET." SET  
															  tuition_fee			= '".cleanvars($tuition_fee)."'
															, no_of_std				= '".cleanvars($no_of_std)."'
															, amount_per_std		= '".cleanvars($amount_per_std)."'
															, tuitionfee_percentage	= '".cleanvars($tuitionfee_percentage)."'
															, total_amount			= '".cleanvars($_POST['totalAmount'][$i])."'
															  WHERE id_setup				= '".$valRoyalty['id']."'
															  AND id_particular			= '".cleanvars($_POST['id_particular'][$i])."'
															  AND id_class 				= '".cleanvars($id_class)."'
															");
				}else{
					//If Not Exist & Amount Then Add
					if($_POST['totalAmount'][$i] > 0) {

						$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																  id_setup
																, id_particular
																, id_class
																, tuition_fee
																, no_of_std
																, amount_per_std
																, tuitionfee_percentage	
																, total_amount								
															)
														VALUES(
																  '".$valRoyalty['id']."'
																, '".cleanvars($_POST['id_particular'][$i])."'
																, '".cleanvars($id_class)."'
																, '".cleanvars($tuition_fee)."'
																, '".cleanvars($no_of_std)."'
																, '".cleanvars($amount_per_std)."'
																, '".cleanvars($tuitionfee_percentage)."'
																, '".cleanvars($_POST['totalAmount'][$i])."'				
															)");
					}
				}
			}

			// Make Log
			$remarks = 'Royalty Updated, CampusID# "'.cleanvars($id_campus).'" details';
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
			header("Location: campuses.php?id=$id_campus", true, 301);
			exit();
		}
	}else{
		// Insert Into Parent Royalty Table
		$sqllmsRoyality  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING."(
															  royalty_status
															, royalty_type
															, grand_total
															, id_campus
															, id_added
															, date_added								
														)
													VALUES(
															  '0'
															, '".cleanvars($id_type)."'
															, '".cleanvars($_POST['grandTotal'])."'
															, '".$id_campus."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()	
														)" );
			
		if($sqllmsRoyality) {
			$latest_id = $dblms->lastestid();

			for($i=0; $i<COUNT($_POST['id_particular']); $i++){
				// echo $i
				// .$_POST['id_particular'][$i].""
				// ."---".cleanvars($_POST['id_particular'][$i]).""			
				// ."----".cleanvars($_POST['id_class'][$i])."<br>"					
				// ."----".cleanvars($_POST['stds'][$i])."<br>"				
				// ."----".cleanvars($_POST['amount'][$i])."<br>"			
				// ."----".cleanvars($_POST['totalAmount'][$i])."<br>"				
				// ;

				if($_POST['totalAmount'][$i] > 0) {
					
					// POST Vars
					$id_class = 0;
					$no_of_std = 0;
					$amount_per_std = 0;
					$tuitionfee_percentage = 0;

					if(!empty($_POST['id_class'][$i])){
						$id_class = cleanvars($_POST['id_class'][$i]);
					}  
					if(!empty($_POST['stds'][$i])) { 
						$no_of_std = cleanvars($_POST['stds'][$i]);
					} 
					if(!empty($_POST['amount'][$i])) {

						$amount_per_std = cleanvars($_POST['amount'][$i]);
					}
					if(!empty($_POST['tuitionfee_percentage'][$i])) {

						$tuitionfee_percentage = cleanvars($_POST['tuitionfee_percentage'][$i]);
					}

					$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																  id_setup
																, id_particular
																, id_class
																, no_of_std
																, amount_per_std
																, tuitionfee_percentage	
																, total_amount								
															)
														VALUES(
																  '".$latest_id."'
																, '".cleanvars($_POST['id_particular'][$i])."'
																, '".cleanvars($id_class)."'
																, '".cleanvars($no_of_std)."'
																, '".cleanvars($amount_per_std)."'
																, '".cleanvars($tuitionfee_percentage)."'
																, '".cleanvars($_POST['totalAmount'][$i])."'				
															)");
				}	
			}

			// make Log
			$remarks = 'Royalty Add, CampusID# "'.cleanvars($id_campus).'" details';
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
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: campuses.php?id=$id_campus", true, 301);
			exit();
		}
	}
}

//---------------- Campus Royalty Status ----------------------
// if(isset($_POST['update_royalty_status'])){
// 	for ($i=0; $i <sizeof($_POST['royalty_id']) ; $i++){
// 		// Update Parent Royalty Table
// 		$sqllmsRoyalty  = $dblms->querylms("UPDATE ".ROYALTY_SETTING." SET  
// 												  royalty_status	= '".cleanvars($_POST['royalty_status'][$i])."'
// 												, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
// 												, date_modify		= Now()
// 												  WHERE id			= '".cleanvars($_POST['royalty_id'][$i])."' ");
// 		if($sqllmsRoyalty) {
// 			$latest_id = $_POST['royalty_id'][$i];
// 			// make Log
// 			$remarks = 'Royalty Status Updated, CampusID# "'.cleanvars($id_campus).'" details';
// 			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
// 																id_user										, 
// 																filename									, 
// 																action										,
// 																dated										,
// 																ip											,
// 																remarks										, 
// 																id_campus				
// 																)
			
// 														VALUES(
// 																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
// 																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
// 																'2'											, 
// 																NOW()										,
// 																'".cleanvars($ip)."'						,
// 																'".cleanvars($remarks)."'						,
// 																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
// 																)
// 										");
// 		}
// 	}
// 	$_SESSION['msg']['title'] 	= 'Successfully';
// 	$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
// 	$_SESSION['msg']['type'] 	= 'success';
// 	header("Location: campuses.php", true, 301);
// 	exit();
// }
?>
