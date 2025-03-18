<?php 
if($_SESSION['userlogininfo']['LOGINTYPE']  == 1){
	$myorders = $api->get_myreturns();
	echo'
	<title>My Sales Return | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2> My Sales Return</h2>
		</header>
		<div class="row">
			<div class="col-md-12"> 
			
			<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">

						<h2 class="panel-title"><i class="fa fa-folder-open"></i>  My Sales Return </h2>
					</header>
				
						<div class="panel-body" id="pbody">';
							if(!empty($myorders)){
								echo '
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-condensed invE_table" id="cartTable" style="margin-top: 10px;">
										<thead>
											<tr>
												<th style="text-align:center;"> Sr #</th>
												<th style="text-align:center;"> Ord.#</th>
												<th> Dated</th>
												<th> Customer</th>
												<th style="text-align:center;">Total</th>
												<th style="text-align:center;">Ded. %</th>
												<th style="text-align:center;">Ded. Amount</th>
												<th style="text-align:center;">Grand Total</th>
												<th style="text-align:center;"> Status</th>
												<th style="width:70px; text-align:center; font-size:14px;"><i class="fa fa-reorder"></i> </th>
											</tr>
										</thead>
										<tbody>';
									$srno = 0;
								foreach($myorders['data'] as $rowbills):
									$srno++;
											//------------------------------------------------
											echo '
											<tr>
												<td style="text-align:center; width:50px;">'.$srno.'</td>
												<td style="text-align:left; width:90px;">'.$rowbills['order_no'].'</td>
												<td style="text-align:left; width:90px;">'.date('d/m/Y', strtotime(cleanvars($rowbills['dated']))).'</td>
												<td style="text-align:left;">'.$rowbills['customer_name'].'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['total']).'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['deducation_per']).'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['deducation_amount']).'</td>
												<td style="text-align:right; width:100px;">'.number_format($rowbills['grand_total']).'</td>
												<td style="text-align:center; width:100px;">'.$rowbills['order_status'].'</td>
												<td style="text-align:center;">
													<a class="btn btn-xs btn-success modal-with-move-anim-pvs" onclick="showAjaxModalZoom(\'include/modals/store/return_view.php?id='.$rowbills['order_id'].'\');" href="#show_modal"><i class="glyphicon glyphicon-zoom-out"></i></a>  
													<a class="btn btn-xs btn-information" href="returnprint.php?id='.$rowbills['order_id'].'" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
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
						</div>';
						
						echo '
					</form>
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
}else{
	header("location: dashboard.php");
}
