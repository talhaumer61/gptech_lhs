<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))){ 

//-----------------------------------------------
if(isset($_POST['day']))
{
    $day_id = $_POST['day'];
}
else{
    $day_id = "";
}
//-----------------------------------------------	
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="timetable_print.php?id_day='.$day_id.'" target="_blank" class="mb-lg validate" enctype="multipart/form-data" method="GET" accept-charset="utf-8">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-plus-square"></i> View Daywise Routine</h4>
		</div>
		<div class="panel-body">
			<div class="row mt-sm">
				<div class="col-md-4 col-md-offset-4">
					<label class="control-label">Day <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_day">
						<option value="">Select</option>
						<option value="0">All Days</option>';
							foreach($daytypes as $day) {
								if($day['id'] == $day_id){
									echo '<option value="'.$day['id'].'" selected>'.$day['name'].'</option>';
								}else{
									echo '<option value="'.$day['id'].'">'.$day['name'].'</option>';
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