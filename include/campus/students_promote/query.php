<?php 
//-------------------------------------------
if(isset($_POST['promote_students'])) { 
	for($i=1; $i <= (COUNT($_POST['id_std'])); $i++){
		//--------------------------------------
		if(isset($_POST['is_promote'][$i])){

				//------------ Check rollno if already exist then increment ----------------
				$sqllms_rollno	= $dblms->querylms("SELECT std_rollno
													FROM ".STUDENTS." 	
													WHERE std_id != '' AND id_class = '".cleanvars($_POST['id_class'])."'
													AND id_section = '".cleanvars($_POST['id_section'])."'
													AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ");
				//-----------------------------------------------------
				$value_rollno = mysqli_fetch_array($sqllms_rollno);
				if($value_rollno['std_rollno'] != $i)
				{
					$rollno = $i;
				}
				else{
					$rollno = $i + 1;
				}
			//-----------------------------------------------------

			$sqllms  = $dblms->querylms("UPDATE ".STUDENTS." SET  
															  id_class			= '".cleanvars($_POST['id_class'])."' 
															, id_section		= '".cleanvars($_POST['id_section'])."' 
															, std_rollno		= '".$rollno."'
															, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
															, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, date_modify		= NOW()
														WHERE std_id			= '".cleanvars($_POST['id_std'][$i])."'");	
			// --------------------------------------
			if($sqllms) { 
				//--------------------------------------
				$remarks = 'Student Promoted ID: "'.cleanvars($_POST['id_std'][$i]).'" details';
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
																		'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		  ,
																		'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
																		'2'															  , 
																		NOW()														  ,
																		'".cleanvars($ip)."'										  ,
																		'".cleanvars($remarks)."'									  ,
																		'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																	)
												");
				
			}
		}
	}
	//--------------------------------------
	if($sqllms) { 
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: students_promote.php", true, 301);
		exit();
	}
	//--------------------------------------
}
//--------------------------------------
?>