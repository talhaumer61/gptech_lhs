<?php  
echo'
<!-- Add Modal Box -->
<div id="print_challan" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="feechallanprint.php" target="_blank" class="form-horizontal" id="form" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i> Print Monthly Challan</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mb-md">
					<label class="col-md-2 control-label">Session <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_session">
							<option value="">Select</option>';
							$sqllmsSession = $dblms->querylms("SELECT session_id, session_status, session_name 
																FROM ".SESSIONS."
																WHERE session_status	= '1'
																AND is_deleted			= '0'
																ORDER BY session_id ASC");
							while($valueSession = mysqli_fetch_array($sqllmsSession)) {
								echo'<option value="'.$valueSession['session_id'].'" '.($valueSession['session_id']==$_SESSION['userlogininfo']['ACADEMICSESSION'] ? 'selected' : '').'>'.$valueSession['session_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-2 control-label">Month <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_month">
							<option value="">Select</option>';
							foreach($monthtypes as $month) {
								echo'<option value="'.$month['id'].'">'.$month['name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit" name="submit">Print</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
?>