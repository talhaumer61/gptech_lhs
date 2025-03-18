<?php
echo'
<div id="emply_report" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="employee-report.php?view" target="_blank" class="form-horizontal validate" enctype="multipart/form-data" method="GET" accept-charset="utf-8">
            <div class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-print"></i> Employee Reports</h2>
            </div>
            <div class="panel-body">
                <label class="control-label">Report <span class="required">*</span></label>
                <div class="form-group mt-sm mb-md">
                    <div class="col-md-12">
                    <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="report">
                        <option value="">Select</option>
                        <option value="emply-dept-wise">Department Wise Report</option>
                        <option value="emply-teaching">Teaching Faculty Report</option>
                        <option value="emply-qulification-report">Teachers Qualification Report</option>
                    </select>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">View</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </div>
            </footer>
		</form>
    </section>
</div>';
?>