<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '11', 'added' => '1'))){ 
echo '
<!-- Add Modal Box -->
<div id="make_parentlogin" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="parentlogin.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i> Make Parent Login</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Family No <span class="required">(Father CNIC)*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="std_fathercnic" onchange="get_classstudent(this.value)">
							<option value="">Select</option>';
							$sqllmsclass	= $dblms->querylms("SELECT DISTINCT std_fathercnic 
																FROM ".STUDENTS." 
																WHERE std_status = '1' 
																AND is_deleted = '0' 
																AND std_fathercnic IS NOT NULL 
																AND std_fathercnic <> '' 
																ORDER BY std_fathercnic ASC;
															");
							while($value_std 	= mysqli_fetch_array($sqllmsclass)) {
							echo '<option value="'.$value_std['std_fathercnic'].'">'.$value_std['std_fathercnic'].'</option>';
							}
							echo '
						</select>
					</div>
				</div>
				<div id="getparentdetail">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Father Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="adm_fullname" name="adm_fullname" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Phone </label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="adm_phone" name="adm_phone"/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Username <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="adm_username" name="adm_username" required title="Must Be Required"/>
					</div>
				</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Password <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="password" class="form-control" id="adm_userpass" name="adm_userpass" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="adm_status" name="adm_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="adm_status" name="adm_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_parent" name="submit_parent">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>