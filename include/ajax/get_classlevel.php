<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_classlevel'])){
	if(!empty($_POST['id_classlevel'])){
		$sql = "AND id_classlevel = '".cleanvars($_POST['id_classlevel'])."'";
	}else{
		$sql = "";
	}
	echo'
	<option value="">Select</option>';
	$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
									FROM ".CLASSES." 
									WHERE class_status = '1'
									AND is_deleted != '1'
									$sql
									ORDER BY class_id ASC");
	while($valuecls = mysqli_fetch_array($sqllmscls)){
		echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id']==$class ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
	}
}
?>