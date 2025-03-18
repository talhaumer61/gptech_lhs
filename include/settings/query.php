<?php 
if(isset($_POST['submit_settings'])) {  
	
//----------------------DEL ALL RECORDS--------------------------
$sqllms  = $dblms->querylms("UPDATE ".SETTINGS." s SET s.status = '2', s.is_deleted	= '1' ,	s.date_modify	= NOW() , s.id_modify = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	
							WHERE s.id_zone = '".cleanvars($_POST['id_zone'])."'");


//-----------------------ADD NEW ONE----------------------------
if ($sqllms){
	$sqllms  = $dblms->querylms("INSERT INTO ".SETTINGS."(
														status			, 
														id_zone			,
														adm_session		,
														acd_session		, 
														exam_session	,
														date_added		,
														id_added					
													  )
	   											VALUES(
													   	'1'														,
														'".cleanvars($_POST['id_zone'])."'						, 
														'".cleanvars($_POST['adm_session'])."'					, 
														'".cleanvars($_POST['acd_session'])."'					, 
														'".cleanvars($_POST['exam_session'])."'					, 
														NOW()													,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'					
													  )
								");
}
//-----------------------end---------------

//--------------------------------------
$latest_id = $dblms->lastestid();	
//--------------------------------------

	if($sqllms) { 
//--------------------------------------
	$remarks = 'New Setting Added ID: "'.cleanvars($latest_id).'" detail';
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
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 	, 
															'1'																, 
															NOW()															,
															'".cleanvars($ip)."'											,
															'".cleanvars($remarks)."'										,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )
											");
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: settings.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
?>
