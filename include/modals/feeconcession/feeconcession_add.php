<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '75', 'add' => '1'))){ 
echo '
<!-- Add Modal Box -->
<div id="make_feeconcession" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="feeconcession.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Fee Concession</h2>
			</header>
			<div class="panel-body">
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Category <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_cat">
						<option value="">Select</option>';
							$sqllms	= $dblms->querylms("SELECT cat_id, cat_type, cat_status, cat_name 
												FROM ".SCHOLARSHIP_CAT."
												WHERE cat_id != '' AND cat_status = '1' AND cat_type = '2' AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
												ORDER BY cat_name ASC");
							while($rowvalues = mysqli_fetch_array($sqllms)) {
								echo '<option value="'.$rowvalues['cat_id'].'">'.$rowvalues['cat_name'].'</option>';
							}
						echo '
					</select>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Class <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" name="id_class" onchange="get_classstudent_fee(this.value)">
						<option value="">Select</option>';
						$sqllmsclass	= $dblms->querylms("SELECT class_id, class_name 
																FROM ".CLASSES." 
																WHERE class_status = '1' ORDER BY class_id ASC");
						while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
						echo '<option value="'.$value_class['class_id'].'">'.$value_class['class_name'].'</option>';
						}
						echo '
					</select>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Student <span class="required">*</span></label>
				<div class="col-md-9">
					<div id="getclassstudent">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_std" name="id_std">
							<option value="">Select</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Concession (%) <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control percent" name="percent" id="percent" min="0" max="100" required title="Amount should be greater than 0 & less than 100"/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Amount <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="amount" id="conamount" required title="Must Be Required" readonly/>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Note </label>
				<div class="col-md-9">
					<textarea class="form-control" rows="2" name="note" id="note"></textarea>
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
						<button type="submit" class="btn btn-primary" id="submit_feeconcession" name="submit_feeconcession">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>
<script type="text/javascript">
	//Return Students Against Class
	function get_classstudent_fee(id_class) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_classstudent_fee.php",  
			data: "id_class="+id_class,  
			success: function(msg){  
				$("#getclassstudent").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}

	//Calculate Concession
	$(document).on("change", ".percent", function() {
		var percentage = document.getElementById("percent").value;
		var tuitionfee = document.getElementById("tuition_fee").value;
		concession = (percentage *  tuitionfee) / 100;
		$("#conamount").val(concession);
	});
</script>