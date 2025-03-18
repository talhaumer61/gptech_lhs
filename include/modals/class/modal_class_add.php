<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'added' => '1'))){
echo'
<div id="make_class" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="class.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Class</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Class Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="class_name" id="class_name" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Class Numeric <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name = "class_code" id="class_code" required title="Must Be Required"></textarea>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Class Level<span class="required">*</span></label>
					<div class="col-md-9">
						<select data-plugin-selectTwo data-width="100%" name="id_classlevel" id="id_classlevel" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							foreach ($classlevel as $level):
								echo'<option value="'.$level['id'].'">'.$level['name'].'</option>';
							endforeach;
							echo'
						</select>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Class Subjects <span class="required"> *</span></label>
					<div class="col-md-9">
						<input type="file" class="form-control" name = "class_attachment" id="class_attachment" accept=".pdf" title="Must Be Required">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="class_status" name="class_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="class_status" name="class_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_class" name="submit_class">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}	
else{
	header("Location: dashboard.php");
}
?>