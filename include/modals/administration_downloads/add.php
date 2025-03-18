<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '63', 'add' => '1'))){ 
echo '
<!-- Add Modal Box -->
<div id="make_download" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="administration_downloads.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Download</h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Type <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" onchange="chk_type(this.value)" data-minimum-results-for-search="Infinity" id="id_type" name="id_type">
							<option value="">Select</option>';
								$types	= get_downloadTypes();
								foreach($types as $key => $value) {
									echo '<option value="'.$key.'">'.$value.'</option>';
								}
						echo '
						</select>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Title <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="title" name="title" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group" id="file">
					<label class="col-md-3 control-label">File <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="file" class="form-control" name="file" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md" id="youtube_code" style="display:none;">
					<label class="col-md-3 control-label">Youtube Code <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="youtube_code" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Description</label>
					<div class="col-md-9">
						<textarea class="form-control" rows="2" name="description" id="description"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
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
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_download" name="submit_download">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>