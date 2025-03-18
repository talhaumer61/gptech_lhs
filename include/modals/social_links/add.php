<?php 
echo '
<!-- Add Modal Box -->
<div id="add" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Add Link</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Type <span class="required">*</span></label>
					<div class="col-md-9">
						<select data-plugin-selectTwo data-width="100%" name="id_social" id="id_social" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							$socialtype = get_socialtype();
							foreach($socialtype as $key => $value){
								echo'<option value="'.$key.'">'.$value.'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Link <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="link" id="link" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<select data-plugin-selectTwo data-width="100%" name="status" id="status" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							foreach($admstatus as $status){
								echo'<option value="'.$status['id'].'">'.$status['name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_link" name="submit_link">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';