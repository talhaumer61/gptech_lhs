<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'add' => '1'))){
echo'
<!-- Add Modal Box -->
<div id="make_circular" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="circular.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Circular</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Refrence <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cir_refrence" id="cir_refrence" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Subject <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cir_subject" id="cir_subject" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Address To <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cir_addressto" id="cir_addressto" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Date <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" data-plugin-datepicker required title="Must Be Required" name="cir_dated" id="cir_dated"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Details <span class="required">*</span></label>
					<div class="col-md-9">
						<textarea data-plugin-summernote class=""form-group summernote summernoteEx" name="cir_details" id="cir_details" row="50" required title="Must Be Required"></textarea>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Regards <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cir_regards" id="cir_regards" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Designation <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_designation">
							<option value="">Select</option>';
								$sqllmsdesg	= $dblms->querylms("SELECT designation_id, designation_name 
																FROM ".DESIGNATIONS."
																WHERE designation_status = '1' 
																AND is_deleted != '1'
																ORDER BY designation_name ASC
															  ");
								while($valuedesg = mysqli_fetch_array($sqllmsdesg)) {
									echo '<option value="'.$valuedesg['designation_id'].'">'.$valuedesg['designation_name'].'</option>';
								}
								echo'
						</select>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Digital Signature </label>
					<div class="col-md-9">
						<input type="file" class="form-control" name="digital_signature" id="digital_signature" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="cir_status" name="cir_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="cir_status" name="cir_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_circular" name="submit_circular">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}	
?>