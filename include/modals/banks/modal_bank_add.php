<?php 
echo '
<!-- Add Modal Box -->
<div id="make_bank" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Add Bank</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Fund Type <span class="required">*</span></label>
					<div class="col-md-9">
						<select data-plugin-selectTwo data-width="100%" name="id_type" id="id_type" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							foreach ($fundType as $fund):
								echo'<option value="'.$fund['id'].'">'.$fund['name'].'</option>';
							endforeach;
							echo'
						</select>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Bank Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="bank_name" id="bank_name" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Account Title <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="account_title" id="account_title" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Account Number <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="account_no" id="account_no" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">IBAN</label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="iban_no" id="iban_no" />
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Branch Code <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="branch_code" id="branch_code" required title="Must Be Required"/>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_bank" name="submit_bank">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';