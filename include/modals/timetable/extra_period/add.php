<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'add' => '1'))){
echo '
<!-- Add Modal Box -->
<div id="make_timetable" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="timetable_extra_period.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" auto-complete="off">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Extra Lecture</h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-md-4">
						<label class="control-label">Class <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_sectionsubject(this.value)">
							<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
													FROM ".CLASSES."
													WHERE class_status = '1' 
													AND is_deleted != '1' 
													ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
							}
						echo '
						</select>
					</div>
					<div id="getsectionsubject">
						<div class="col-md-4">
							<label class="control-label">Section <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_section">
								<option value="">Select</option>
							</select>
						</div>
						<div class="col-md-4">
							<label class="control-label">Subject <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_subject">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<label class="control-label">Teacher <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_teacher">
							<option value="">Select</option>';
							$sqllmsTeacher	= $dblms->querylms("SELECT emply_id, emply_name
															FROM ".EMPLOYEES." 
															WHERE emply_status = '1' AND is_deleted != '1' AND id_type = '1'
															AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
															ORDER BY emply_name ASC");
							while($valTeacher = mysqli_fetch_array($sqllmsTeacher)){
								echo'<option value="'.$valTeacher['emply_id'].'">'.$valTeacher['emply_name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-6">
						<label class="control-label">Class Room <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_room">
							<option value="">Select</option>';
								$sqllmsRooms = $dblms->querylms("SELECT room_id, room_status, room_no 
																		FROM ".CLASS_ROOMS."
																		WHERE room_status = '1' AND is_deleted != '1' 
																		AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																		ORDER BY room_id ASC");
								while($valueRoom = mysqli_fetch_array($sqllmsRooms)) {
									echo '<option value="'.$valueRoom['room_id'].'">'.$valueRoom['room_no'].'</option>';
								}
						echo '
						</select>
					</div>
					<div class="col-md-12">
						<label class="control-label">Time <span class="required">*</span></label>
						<div class="input-timerange input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar-o"></i>
							</span>
							<input type="text" class="form-control valid" name="from_date" id="from_date" required  data-plugin-datepicker title="Must Be Required" aria-required="true">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" name = "to_date" id="to_date" required data-plugin-datepicker title="Must Be Required"  aria-required="true">
						</div>
					</div>
					<div class="col-md-12">
						<label class="control-label">Time <span class="required">*</span></label>
						<div class="input-timerange input-group">
							<span class="input-group-addon">
								<i class="fa fa-clock-o"></i>
							</span>
							<input type="text" class="form-control valid" name="from_time" id="from_time" required  data-plugin-timepicker title="Must Be Required" aria-required="true">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" name="to_time" id="to_time" required data-plugin-timepicker title="Must Be Required"  aria-required="true">
						</div>
					</div>
					
					<div class="col-md-12">
						<label class="col-sm-2 control-label mt-sm">Status <span class="required">*</span></label>
						<div class="col-md-10 mt-sm">
							<div class="radio-custom radio-inline">
								<input type="radio" id="status" name="status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="status" name="status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_extra_period" name="submit_extra_period">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>