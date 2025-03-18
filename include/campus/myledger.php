<?php 
if($_SESSION['userlogininfo']['LOGINTYPE']  == 1){
	$myledger = $api->get_myledger();
	echo'
	<title>My Ledger | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2> My Ledger</h2>
		</header>
		<div class="row">
			<div class="col-md-12"> 
			
			<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">

						<h2 class="panel-title"><i class="fa fa-money"></i>  My Ledger </h2>
					</header>
				
						<div class="panel-body" id="pbody">';
							if(!empty($myledger)){
								echo '
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-condensed invE_table" id="cartTable" style="margin-top: 10px;">
										<thead>
											<tr>
												<th style="text-align:center; font-weight:bold;">Sr #</th>
												<th style="text-align:left; font-weight:bold; ">Date</th>
												<th style="text-align:left; font-weight:bold; ">Detail</th>
												<th style="text-align:center; font-weight:bold;">Debit</th>
												<th style="text-align:center; font-weight:bold;">Credit</th>
												<th style="text-align:center; font-weight:bold;">Balance</th>
											</tr>
										</thead>
										<tbody>';
								$srno = 0;
								foreach($myledger['data'] as $rowbills):
											$srno++;
											if($rowbills['debit_credit'] == 2) {
												$debit 		= $rowbills['amount'];
												$debitdisp 	= number_format($rowbills['amount']);
											} else {
												$debit 		= 0;
												$debitdisp 	= '---';
											}

											if($rowbills['debit_credit'] == 1) {
												$credit 	= $rowbills['amount'];
												$creditdisp = number_format($rowbills['amount']);
											} else {
												$credit 	= 0;
												$creditdisp = '----';
											}
											$balance = ($balance + ($debit - $credit));
											//------------------------------------------------
											echo '
											<tr>
												<td style="text-align:center; width:50px;">'.$srno.' '.$rowbills['code'].'</td>
												<td style="text-align:left; width:100px;">'.date('d/m/Y', strtotime(cleanvars($rowbills['dated']))).'</td>
												<td style="text-align:left;">'.$rowbills['remarks'].'</td>
												<td style="text-align:right; width:120px;">'.$debitdisp.'</td>
												<td style="text-align:right; width:120px;">'.$creditdisp.'</td>
												<td style="text-align:right; width:120px;">'.number_format($balance).'</td>
											</tr>';
											//------------------------------------------------
													$totalcredit 	= ($totalcredit+ $credit);
													$totaldebit 	= ($totaldebit+ $debit);
								endforeach;
							
		echo '
										</tbody>
										<thead>
											<tr>
												<th colspan="3" style="text-align:right; font-weight:bold;"> </th>
												<th style="text-align:right; color:#000; font-weight:bold;background-color: bisque; font-size:16px !important;">'.number_format($totaldebit).'</th>
												<th style="text-align:right; color:#000; font-weight:bold;background-color: darksalmon; font-size:16px !important;">'.number_format($totalcredit).'</th>
												<th style="text-align:right; color:#000; font-weight:bold; font-size:16px !important;background-color: mistyrose; color:#f00;">'.number_format($balance).'</th>
											</tr>

										</thead>	
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
	</section>';
}else{
	header("location: dashboard.php");
}
