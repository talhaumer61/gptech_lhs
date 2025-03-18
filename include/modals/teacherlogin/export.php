<?php 
echo '
<div id="export_teacherlogins" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="teacherlogin.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-download"></i>  Export Teacher Logins</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mb-md">
					<div class="col-md-12">';
                        $sqllmsdept	= $dblms->querylms("SELECT dept_id, dept_name 
                                        FROM ".DEPARTMENTS." 
                                        WHERE dept_status = '1' AND is_deleted != '1' AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                        ORDER BY dept_name ASC");
                        while($value_dept 	= mysqli_fetch_array($sqllmsdept)) {
                            echo'<div class="col-md-4 mt-xs"> <input type="checkbox" class="mr-xs" id="id_depts" name="id_depts[]" value="'.$value_dept['dept_id'].'" checked>'.$value_dept['dept_name'].'</div>';
                        }
                        echo'
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="export_logins" name="export_logins">Export</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
?>