<?php
echo'
<div id="edit" class="tab-pane active">
	<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="campus_id" id="campus_id" value="'.cleanvars($_GET['id']).'">
		<fieldset class="mt-lg">
			<div class="form-group">
				<label class="col-sm-3 control-label">Photo</label>
				<div class="col-md-8">
					<div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
							<img src="'.$photo.'" class="rounded img-responsive">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
						<div>
							<span class="btn btn-xs btn-default btn-file">
								<span class="fileinput-new">Select image</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="campus_logo" accept="image/*">
							</span>
							<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Area Zone <span class="required">*</span></label>
				<div class="col-md-8">
					<select data-plugin-selectTwo data-width="100%" name="id_zone" id="id_zone" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						foreach(get_AreaZone() as $key => $val):
								echo'<option value="'.$key.'" '.($rowsvalues['id_zone'] == $key ? 'selected': '').'>'.$val.'</option>';
						endforeach;
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Govt. Reg #</label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="campus_regno_gov" id="campus_regno_gov" value="'.$rowsvalues['campus_regno_gov'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Name <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_name" id="campus_name" value="'.$rowsvalues['campus_name'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Campus Code <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="campus_code" readonly id="campus_code" value="'.$rowsvalues['campus_code'].'" required title="Must Be Required" />
				</div>
			</div>
			<!-- div class="form-group">
				<label class="col-sm-3 control-label">Regno# <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_regno" id="campus_regno" value="'.$rowsvalues['campus_regno'].'"/>
				</div>
			</div -->
			<div class="form-group">
				<label class="col-sm-3 control-label">Campus Head <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_head" id="campus_head" value="'.$rowsvalues['campus_head'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Eamil <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_email" id="campus_email" value="'.$rowsvalues['campus_email'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Phone <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_phone" id="campus_phone" value="'.$rowsvalues['campus_phone'].'"/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"> Opening Date <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="month" class="form-control" name="campus_opendate" id="campus_opendate" value="'.$rowsvalues['campus_opendate'].'" required title="Must Be Required"/>
				</div>
			</div>
			<!-- div class="form-group">
				<label class="col-sm-3 control-label">Fax </label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="campus_fax" id="campus_fax" value="'.$rowsvalues['campus_fax'].'"/>
				</div>
			</div -->
			<div class="form-group">
				<label class="col-sm-3 control-label">Address <span class="required">*</span></label>
				<div class="col-md-8">
					<textarea name="campus_address" rows="2" class="form-control" value="" aria-required="true">'.$rowsvalues['campus_address'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="campus_status" name="campus_status" value="1"'; if($rowsvalues['campus_status'] == 1) {echo'checked';} echo'>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="campus_status" name="campus_status" value="2"'; if($rowsvalues['campus_status'] == 2) {echo'checked';} echo'>
						<label for="radioExample2">Inactive</label>
					</div>		
				</div>
			</div>
		</fieldset>
		<div class="panel-footer">
			<div class="row">
				<div class="col-sm-offset-3 col-sm-5">
					<button type="submit"  name="changes_campus" id="changes_campus" class="btn btn-primary">Update Campus</button>
				</div>
			</div>
		</div>
	</form>
</div>';
