<?php 
echo '
<!-- Add Modal Box -->
<div id="make_onlineclass" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Online Class</h2>
			</header>
			<div class="panel-body">

                <input type="hidden" name="id_class" value="'.$_GET['class'].'">
                <input type="hidden" name="id_section" value="'.$_GET['section'].'">
                <input type="hidden" name="id_subject" value="'.$_GET['id'].'">

                <div class="form-group">
                    <label class="col-md-3 control-label">Title <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="zoom_title" id="zoom_title" required title="Must Be Required">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Date <span class="required">*</span></label>
                    <div class="col-md-9">
                        <div class="input-daterange input-group" data-plugin-datepicker>
                            <input type="text" class="form-control" name="dated" id="dated" required title="Must Be Required">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label">Time <span class="required">*</span></label>
                    <div class="col-md-9">
                        <div class="input-daterange input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="time" class="form-control valid" name="start_time" id="start_time" required="" title="Must Be Required" aria-required="true" aria-invalid="false">
                            <span class="input-group-addon">Due </span>
                            <input type="time" class="form-control" name="end_time" id="end_time" required="" title="Must Be Required"  aria-required="true">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Link <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="zoom_link" id="zoom_link" required title="Must Be Required">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Password <span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="zoom_pass" id="zoom_pass" required title="Must Be Required">
                    </div>
                </div>
                <div class="form-group mb-md">
                    <label class="col-md-3 control-label">Note </label>
                    <div class="col-md-9">
                        <textarea class="form-control" rows="2" name="details" id="details"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status <span class="required">*</span></label>
                    <div class="col-md-9">
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="zoom_status" name="zoom_status" value="1" checked>
                            <label for="radioExample1">Active</label>
                        </div>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="zoom_status" name="zoom_status" value="2">
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
?>