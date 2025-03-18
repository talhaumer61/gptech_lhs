<?php
// UPDATE EXAM DEMAND
if(isset($_POST['update_demand'])){
	$sqllmsattendance = $dblms->querylms("SELECT demand_id
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
		$total_amount = $_POST['examFee'] * $_POST['total_std'];
		
		$sqllms  = $dblms->querylms("UPDATE ".EXAM_DEMAND." SET
														  is_publish		= '".cleanvars($_POST['is_publish'])."'
														, total_amount		= '".cleanvars($total_amount)."'
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify		= NOW()  
													  	  WHERE demand_id	= '".cleanvars($_POST['id_demand'])."'
									");
		if($sqllms){
			$idsetup = cleanvars($_POST['id_demand']);
			
			for($i=1; $i<= sizeof($_POST['id_class']); $i++){

				if($_POST['stds'][$i]>0){
					$total = $_POST['examFee'] * $_POST['stds'][$i];

					$sqlUpdate = $dblms->querylms("UPDATE ".EXAM_DEMAND_DET." SET
																	  no_of_std			= '".cleanvars($_POST['stds'][$i])."'
																	, amount_per_std	= '".cleanvars($_POST['examFee'])."'
																	, total_amount		= '".cleanvars($total)."'
																	  WHERE detail_id	= '".cleanvars($_POST['detail_id'][$i])."'
												");
				}
			}

			$remarks = 'Exam Demand Updated By Headoffice ID#"'.cleanvars($idsetup).'"';
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
?>