<?php 
echo '
<div class="col-md-4">
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title">Notice Borad</h2>
		</header>
		<div class="panel-body" style="height: 529.71px; overflow: scroll; overflow-x: hidden; ">';
			// NOTIFICATIONS AND EVENTS
			$condition = array(
								 'select'       =>  'n.not_title AS title, DATE_FORMAT(n.dated, \'%e %b, %Y\') AS date, n.not_description AS description, n.date_added FROM '.NOTIFICATIONS.' AS n WHERE n.not_status = 1 AND n.is_deleted = 0 UNION ALL SELECT e.subject AS title, CONCAT(DATE_FORMAT(e.date_from, \'%e %b, %Y\'), \' to \', DATE_FORMAT(e.date_to, \'%e %b, %Y\')) AS date, e.detail AS description, e.date_added '
								,'where'        =>  array(  
															 'e.is_deleted' => 0
															,'e.status'    	=> 1
													)
								,'order_by'  	=>  ' date_added DESC'
								,'return_type'  =>  'all'
			);
			$NOTICE_BORAD = $dblms->getRows(EVENTS.' AS e', $condition);
			foreach ($NOTICE_BORAD as $key => $val) {
				echo'
				<section class="panel panel-featured-left panel-featured-primary" style="border-left: 2px solid #cb3f44; border-right: 2px solid #f2f2f2; border-bottom: 2px solid #f2f2f2; border-top : 2px solid #f2f2f2; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);">
					<div class="panel-body">
						<div class="widget-summary widget-summary-sm">
							<div class="widget-summary-col">
								<div class="summary">
									<div class="row">
										<div class="col-md-8"><h6 class="title text-info"><b>'.$val['date'].'</b></h6></div>
										<div class="col-md-4 end"><span class="label label-primary">'.get_timeAgo($val['date_added']).'</span></div>
									</div>
									<h4 class="title text-primary"><b>'.$val['title'].'</b></h4>
									<div class="info">'.$val['description'].'</div>
								</div>
							</div>
						</div>
					</div>
				</section>';
			}
			echo '
		</div>
	</section>
</div>';