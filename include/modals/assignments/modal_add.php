<?php 
echo'
<div id="make_assignment" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Assignment</h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Class <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required>
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																FROM ".CLASSES."
																WHERE class_status = '1' 
																ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo'<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Section <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>
							<option value="">Select</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_subject" name="id_subject" required>
							<option value="">Select</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Title <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="assig_title" id="assig_title" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Open <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="input-daterange input-group" data-plugin-datepicker>
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type="text" class="form-control valid" name="open_date" id="open_date" required="" title="Must Be Required" aria-required="true" aria-invalid="false">
							<span class="input-group-addon">Due </span>
							<input type="text" class="form-control" name="close_date" id="close_date" required="" title="Must Be Required"  aria-required="true">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">File </label>
					<div class="col-md-9">
						<input type="file" class="form-control" name="assig_file" id="assig_file"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Note </label>
					<div class="col-md-9">
						<textarea class="form-control" rows="2" name="assig_note" id="assig_note"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="assig_status" name="assig_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="assig_status" name="assig_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_assignment" name="submit_assignment">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
?>