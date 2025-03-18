<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
$today = date('m/d/Y');
echo'
<div id="make_challan" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="fee_challans.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Single Fee Challan</h2>
			</header>
			<div class="panel-body">		
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label">Class <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_class" onchange="get_classstudent(this.value)">
							<option value="">Select</option>';
							$sqllmsclass	= $dblms->querylms("SELECT class_id, class_name 
																FROM ".CLASSES." 
																WHERE class_status = '1' ORDER BY class_id ASC");
							while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
								echo'<option value="'.$value_class['class_id'].'">'.$value_class['class_name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-6">
						<label class="control-label">Student <span class="required">*</span></label>
						<div id="getclassstudent">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_std">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label">For Month <span class="required">*</span></label>
						<div id="getmonthdetail">
							<select data-plugin-selectTwo data-width="100%" name="id_month" id="id_month" required title="Must Be Required" class="form-control">
								<option value="">Select</option>
							</select>
						</div>
					</div>	
					<div class="col-md-4">
						<label class="control-label">Issue Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="issue_date" id="issue_date" data-plugin-datepicker value="'.$today.'" required title="Must Be Required" />
					</div>
					<div class="col-md-4">
						<label class="control-label">Due Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="due_date" id="due_date" data-plugin-datepicker required title="Must Be Required"/>
					</div>	
				</div>

				<div id="getchallandetail">
				</div>

                <div style="clear: both;"></div>

				<div class="form-group">
					<div class="col-md-12">
						<label class="control-label">Note</label>
						<textarea class="form-control" rows="2" name="note" id="note"></textarea>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="single_challan_generate" name="single_challan_generate">Save</button>
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
	function get_classstudent(id_class) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_challan-detail.php",
			data: "id_class="+id_class,  
			success: function(msg){  
				$("#getclassstudent").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	function get_mothdetail(id_std) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_challan-detail.php",
			data: "id_std="+id_std,  
			success: function(msg){  
				$("#getmonthdetail").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	function get_challandetail(id_month) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_challan-detail.php",  
			data: "id_month="+id_month,  
			success: function(msg){  
				$("#getchallandetail").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
</script>