<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) ||($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '21', 'added' => '1'))){ 
echo '
<!-- Add Modal Box -->
<div id="make_royalty" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="campuses.php?id='.cleanvars($_GET['id']).'" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Add Royalty</h2>
			</header>
			<div class="panel-body">

				<div class="form-group mt-sm">
					<label class="col-md-4 control-label">Royalty Type <span class="required">*</span></label>
					<div class="col-md-8">
						<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($_GET['id']).'">
						<select name="royalty_type" data-plugin-selectTwo data-width="100%" id="royalty_type" onchange="chk_royalty_type(this.value)" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							$types	= get_royaltyTypes();
							foreach($types as $key => $value) {
								echo '<option value="'.$key.'">'.$value.'</option>';
							}
							echo '
						</select>
					</div>
				</div>

				<div class="form-group mt-sm" id="per_month">
					<label class="col-md-4 control-label">Per Month <span class="required">*</span></label>
					<div class="col-md-8">
						<input type="number" class="form-control" required name="per_month" >
					</div>
				</div>

				<div class="form-group mt-sm" id="per_student" style="display:none;">
					<label class="col-md-4 control-label">Per Student <span class="required">*</span></label>
					<div class="col-md-8">
						<input type="number" class="form-control" required name="per_student" >
					</div>
				</div>

				<div class="form-group mb-md">
					<label class="col-md-4 control-label">Date  <span class="required">*</span></label>
					<div class="col-md-8">
						<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type="text" class="form-control" required title="Must Be Required" name="start_date" value="'.date('d-m-Y').'">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" required title="Must Be Required" name="end_date" value="'.date('d-m-Y', strtotime(' + 1 years')).'" >
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Status <span class="required">*</span></label>
					<div class="col-md-8">
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
						<button type="submit" class="btn btn-primary" id="submit_royalty" name="submit_royalty">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>