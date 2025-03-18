<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))){ 

//-----------------------------------------------
if(isset($_POST['id_teacher']))
{
    $teacher = $_POST['id_teacher'];
}
else{
    $teacher = "";
}
//-----------------------------------------------	
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="timetable_print.php?id_teacher='.$teacher.'" target="_blank" class="mb-lg validate" enctype="multipart/form-data" method="GET" accept-charset="utf-8">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-plus-square"></i> View Teacher Routine</h4>
		</div>
		<div class="panel-body">
			<div class="row mt-sm">
				<div class="col-md-4 col-md-offset-4">
					<label class="control-label">Teacher <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_teacher">
						<option value="">Select</option>';
							$sqllmsemply	= $dblms->querylms("SELECT emply_id, emply_status, emply_name 
												FROM ".EMPLOYEES."
												WHERE emply_status = '1' AND is_deleted != '1' 
                                                AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
												ORDER BY emply_name ASC");
							while($valueemply = mysqli_fetch_array($sqllmsemply)) {
								if($valueemply['emply_id'] == $teacher){
									echo '<option value="'.$valueemply['emply_id'].'" selected>'.$valueemply['emply_name'].'</option>';
								}else{
									echo '<option value="'.$valueemply['emply_id'].'">'.$valueemply['emply_name'].'</option>';
								}
						    }
					    echo '
					</select>
				</div>
			</div>		
		</div>
		<footer class="panel-footer mt-sm">
			<div class="row">
				<div class="col-md-12 text-center">
					<button type="submit" class="mr-xs btn btn-primary">Get Details</button>
				</div>
			</div>
		</footer>
	</form>
</section>';
}
else{
	header("location: dashboard.php");
}
?>