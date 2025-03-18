<?php
// ADD EXAM DEMAND
if(isset($_POST['add_demand'])){
	$sqllmsattendance	= $dblms->querylms("SELECT demand_id
											FROM ".EXAM_DEMAND."
											WHERE id_session	= '".$_POST['id_session']."'
											AND id_examtype		= '".$_POST['id_examtype']."'
											AND id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	//if already exist
	if (mysqli_num_rows($sqllmsattendance)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_demand.php?view=add", true, 301);
		exit();
	}else{
		//if not exist
		$sqllms  = $dblms->querylms("INSERT INTO ".EXAM_DEMAND."
															(						 
																  demand_status
																, is_publish
																, total_std
																, total_amount
																, id_session
																, id_examtype
																, id_campus
																, id_added
																, date_added
															) VALUES (	
																  '".cleanvars($_POST['demand_status'])."'
																, '".cleanvars($_POST['is_publish'])."'
																, '".cleanvars(array_sum($_POST['stds']))."'
																, '".cleanvars($_POST['grandTotal'])."'
																, '".cleanvars($_POST['id_session'])."'
																, '".cleanvars($_POST['id_examtype'])."'		
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, NOW()	
															)
									");
		
		if($sqllms){
			$idsetup = $dblms->lastestid();
			for($i=1; $i<= sizeof($_POST['id_class']); $i++){

				if($_POST['stds'][$i]>0){
					$sqllmsDetail = $dblms->querylms("INSERT INTO ".EXAM_DEMAND_DET."
																(						
																	  id_demand
																	, id_class
																	, no_of_std
																	, amount_per_std
																	, total_amount
																) VALUES (	
																	  '".cleanvars($idsetup)."'
																	, '".cleanvars($_POST['id_class'][$i])."'
																	, '".cleanvars($_POST['stds'][$i])."'	
																	, '".cleanvars($_POST['amount'][$i])."'	
																	, '".cleanvars($_POST['totalAmount'][$i])."'
																)");
				}
			}

			$remarks = 'Exam Demand Created ID#"'.cleanvars($idsetup).'"';
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
			header("Location: exam_demand.php", true, 301);
			exit();
		}
	}
}

// UPDATE EXAM DEMAND
if(isset($_POST['update_demand'])){
	$sqllmsattendance	= $dblms->querylms("SELECT demand_id
											FROM ".EXAM_DEMAND."
											WHERE id_session	= '".cleanvars($_POST['id_session'])."'
											AND id_examtype		= '".cleanvars($_POST['id_examtype'])."'
											AND id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
											AND demand_id	   != '".cleanvars($_POST['id_demand'])."' LIMIT 1");
	//if already exist
	if (mysqli_num_rows($sqllmsattendance)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_demand.php", true, 301);
		exit();
	}else{
		//if not exist
		$sqllms  = $dblms->querylms("UPDATE ".EXAM_DEMAND." SET  
														  demand_status		= '".cleanvars($_POST['demand_status'])."'
														, is_publish		= '".cleanvars($_POST['is_publish'])."'
														, total_std			= '".cleanvars(array_sum($_POST['stds']))."'
														, total_amount		= '".cleanvars($_POST['grandTotal'])."'
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
														, date_modify		= NOW()  
													  	  WHERE demand_id	= '".cleanvars($_POST['id_demand'])."'
									");
		if($sqllms){
			$idsetup = cleanvars($_POST['id_demand']);

			// DELETE OLD RECORD
			$sqldel  = $dblms->querylms("DELETE FROM ".EXAM_DEMAND_DET." WHERE id_demand = '".cleanvars($idsetup)."'");

			for($i=1; $i<= sizeof($_POST['id_class']); $i++){

				if($_POST['stds'][$i]>0){
					$sqllmsDetail = $dblms->querylms("INSERT INTO ".EXAM_DEMAND_DET."
																(						
																	  id_demand
																	, id_class
																	, no_of_std
																	, amount_per_std
																	, total_amount
																) VALUES (	
																	  '".cleanvars($idsetup)."'
																	, '".cleanvars($_POST['id_class'][$i])."'
																	, '".cleanvars($_POST['stds'][$i])."'	
																	, '".cleanvars($_POST['amount'][$i])."'	
																	, '".cleanvars($_POST['totalAmount'][$i])."'
																)
													");
				}
			}

			$remarks = 'Exam Demand Updated ID#"'.cleanvars($idsetup).'"';
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
			header("Location: exam_demand.php", true, 301);
			exit();
		}
	}
}

// DELETE EXAM DEMAND
if(isset($_GET['deleteid'])) {

	$sqllms  = $dblms->querylms("UPDATE ".EXAM_DEMAND." SET  
													is_deleted		= '1'
												  , id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted		= '".$ip."'
												  , date_deleted	= NOW()
													WHERE demand_id = '".cleanvars($_GET['deleteid'])."'");

	if($sqllms) { 
		$remarks = 'Exam Demand Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: exam_demand.php?id=".cleanvars($_GET['id'])."", true, 301);
		exit();
	}
}