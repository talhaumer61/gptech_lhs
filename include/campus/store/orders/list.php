<?php 
if(!isset($_GET['id']) && !LMS_VIEW) {
	$myorders = $api->get_myorders();
	echo'
	<title>My Orders | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2> My Orders</h2>
		</header>
		<div class="row">
			<div class="col-md-12"> 
			
			<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">

						<h2 class="panel-title"><i class="fa fa-folder-open"></i>  My Orders </h2>
					</header>
				
						<div class="panel-body" id="pbody">';
							if(count($myorders['data'])>0){
								echo '
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-condensed invE_table" id="cartTable" style="margin-top: 10px;">
										<thead>
											<tr>
												<th style="text-align:center; font-weight:bold;">Sr # </th>
												<th style="text-align:left; font-weight:bold; ">Ord #</th>
												<th style="text-align:left; font-weight:bold; ">Date </th>
												<th style="text-align:left; font-weight:bold; ">Customer Name</th>
												<th style="text-align:center; font-weight:bold;">Order Total</th>
												<th style="text-align:center; font-weight:bold;">Prv Balance</th>
												<th style="text-align:center; font-weight:bold;">Paid</th>
												<th style="text-align:center; font-weight:bold;">Balance</th>
												<th style="text-align:center; font-weight:bold;">Invoice #</th>
												<th style="text-align:center; font-weight:bold;">Status</th>
												<th style="text-align:center; font-weight:bold;">Action</th>
											</tr>
										</thead>
										<tbody>';
								$srno = 0;
								foreach($myorders['data'] as $rowbills):
											$srno++;
											//------------------------------------------------
											if($rowbills['invoice_no']) {
												$invoiceno = '<a class="btn btn-xs btn-purple modal-with-move-anim-pvs" onclick="showAjaxModalInvoice(\'include/modals/store/invoice_view.php?id='.$rowbills['invoice_id'].'\');" href="#show_invoice"> '.$rowbills['invoice_no'].' </a>';
											} else {
												$invoiceno = '';
											}
											echo '
											<tr>
												<td style="text-align:center; width:50px;">'.$srno.'</td>
												<td style="text-align:left; width:90px;">'.$rowbills['order_no'].'</td>
												<td style="text-align:left; width:90px;">'.date('d/m/Y', strtotime(cleanvars($rowbills['dated']))).'</td>
												<td style="text-align:left;">'.$rowbills['customer_name'].'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['total']).'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['opening_balance']).'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['advance']).'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['balance']).'</td>
												<td style="text-align:center; width:90px;">'.$invoiceno.'</td>
												<td style="text-align:center; width:100px;">'.$rowbills['order_status'].'</td>
												<td style="text-align:center;width:120px;">
													<a class="btn btn-xs btn-info" href="myorders.php?id='.$rowbills['order_id'].'"><i class="glyphicon glyphicon-edit"></i></a> 
													<a class="btn btn-xs btn-success modal-with-move-anim-pvs" onclick="showAjaxModalZoom(\'include/modals/store/order_view.php?id='.$rowbills['order_id'].'\');" href="#show_modal"><i class="glyphicon glyphicon-zoom-out"></i></a>  
													<a class="btn btn-xs btn-purple" href="#" title="Copied Order"><i class="glyphicon glyphicon-copy"></i></a> 
													<a class="btn btn-xs btn-information" href="ordersprint.php?id='.$rowbills['order_id'].'" target="_blank"><i class="glyphicon glyphicon-print"></i></a> 
													<a class="btn btn-xs btn-danger"> <i class="glyphicon glyphicon-trash"></i></a>
												</td>
											</tr>';
											//------------------------------------------------
								endforeach;
							
		echo '
										</tbody>
									</table>
								</div>';
							} else{
								echo'
								<div class="text-center">
									<h2 class="text text-center text-danger mt-lg">No record found!</h2>
								</div>';
							}
							echo'
						</div>
				</section>
			</div>
		</div>
	</section>
	<script type="text/javascript">
		function showAjaxModalZoom( url ) {
	// PRELODER SHOW ENABLE / DISABLE
			jQuery( \'#show_modal\' ).html( \'<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>\' );
	// SHOW AJAX RESPONSE ON REQUEST SUCCESS
			$.ajax( {
				url: url,
				success: function ( response ) {
					jQuery( \'#show_modal\' ).html( response );
				}
			} );
		}
		function showAjaxModalInvoice( url ) {
	// PRELODER SHOW ENABLE / DISABLE
			jQuery( \'#show_invoice\' ).html( \'<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>\' );
	// SHOW AJAX RESPONSE ON REQUEST SUCCESS
			$.ajax( {
				url: url,
				success: function ( response ) {
					jQuery( \'#show_invoice\' ).html( response );
				}
			} );
		}
	</script>
<!-- (STYLE AJAX MODAL)-->
<div id="show_modal" class="mfp-with-anim modal-block-primary mfp-hide" style="width: 60%; margin:auto;" tabindex="-1" role="dialog"></div>
<div id="show_invoice" class="mfp-with-anim modal-block-primary mfp-hide" style="width: 60%; margin:auto;" tabindex="-1" role="dialog"></div>';

}