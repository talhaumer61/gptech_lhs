<?php 
echo '
<div id="export_parentlogins" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="parentlogin.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-download"></i> Export Parent Logins</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mb-md">
					<div class="col-md-12">';
                        $sqllmsCls	= $dblms->querylms("SELECT class_id, class_name 
                                        FROM ".CLASSES." 
                                        WHERE class_status = '1' AND is_deleted != '1' 
                                        ORDER BY class_id ASC");
                        while($valueCls 	= mysqli_fetch_array($sqllmsCls)) {
                            echo'<div class="col-md-4 mt-xs"> <input type="checkbox" class="mr-xs" id="id_class" name="id_class[]" value="'.$valueCls['class_id'].'" checked>'.$valueCls['class_name'].'</div>';
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