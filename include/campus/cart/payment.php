<?php
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1 && isset($_GET['id_estimate'])) {
	$balance  			= $api->get_customerbalance($_SESSION['userlogininfo']['CAMPUSCODE']);
	$estimateData		= $api->get_myestimae($_GET['id_estimate']);
	$estimateDetailData	= $api->get_estimatedetail($_GET['id_estimate']);
	$estimate			= $estimateData['data'];
	$estimateDetail		= $estimateDetailData['data'];

	if($estimate['customer_email']){
		$emailaddress = $estimate['customer_email'];
	} else {
		$emailaddress = $_SESSION['userlogininfo']['LOGINUSER'];
	}
	
	if($estimate['customer_cellno']) {
		$cellno = $estimate['customer_cellno'];
	} else if($estimate['customer_personcell']) {
		$cellno = $estimate['customer_personcell'];
	} else {
		$cellno = '0300-0000000';
	}
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i> Order Payment Detail</h2>
	</header>
	<form action="http://localhost/gpt/msd.gptech.pk/api/PayFast/" method="post" id="inv_form" enctype="multipart/form-data" autocomplete="off">
		<div class="panel-body">
			<input type="hidden" name="estimate_id" name="estimate_id" value="'.$estimate['estimate_id'].'">
			<input type="hidden" name="customer_email" name="customer_email" value="'.$emailaddress.'">
			<input type="hidden" name="customer_cellno" name="customer_cellno" value="'.$cellno.'">
			<input type="hidden" name="customer_id" name="customer_id" value="'.$estimate['customer_id'].'">
			<input type="hidden" name="customer_name" name="customer_name" value="'.$estimate['customer_name'].'">
			<input type="hidden" name="control" name="control" value="estimatepayment">
			<div class="row form-group">
				<div class="col-md-3">
					<label class="control-label">Estimate #</label>
					<div class="form-group">
						<input type="text" name="estimate_no" id="estimate_no" class="form-control" value="'.$estimate['estimate_no'].'" aria-controls="table_export" readonly>
					</div> 
				</div>
				<div class="col-md-3">
					<label class="control-label">Dated </label>
					<div class="form-group">
						<input type="text" name="dated" id="dated" class="form-control" value="'.$estimate['estimate_date'].'" aria-controls="table_export" readonly>
					</div>
				</div>
				<div class="col-md-6">
					<label class="control-label">Customer Name</label>
					<div class="form-group">
						<input type="text" name="customername" id="customername" class="form-control" value="'.$estimate['customer_code'].' - '.$estimate['customer_name'].'" aria-controls="table_export" readonly>
					</div>
				</div>
			</div>
			<div class="row form-group mb-lg">
				<div class="col-md-3">
					<label class="control-label">ADE Name</label>
					<div class="form-group">
						<input type="text" name="ade_name" id="ade_name" class="form-control" value="'.$estimate['ade_name'].'" aria-controls="table_export" readonly>
					</div> 
				</div>
				<div class="col-md-3">
					<label class="control-label">ADE Phone #</label>
					<div class="form-group">
						<input type="text" name="ade_cellno" id="ade_cellno" class="form-control" value="'.$estimate['ade_cellno'].'" aria-controls="table_export" readonly>
					</div>
				</div>
				<div class="col-md-3">
					<label class="control-label">Company Name</label>
					<div class="form-group">
						<input type="text" name="company_name" id="company_name" class="form-control" value="'.$estimate['company_name'].'" aria-controls="table_export" readonly>
					</div>
				</div>
				<div class="col-md-3">
					<label class="control-label">Opening Balance</label>
					<div class="form-group">
						<input type="text" class="form-control" style="font-weight:600; font-size:18px !important; text-align:center; color:#00f;" value="'.number_format($balance['balance']).'" aria-controls="table_export" readonly>
					</div>
				</div>
			</div>
			<div class="row form-group" mb-lg">
				<div class="col-sm-offset-3 col-md-6">
					<section class="panel panel-featured panel-featured-primary">
						<header class="panel-heading">
							<h2 class="panel-title">Payment Detail</h2>
						</header>
						<div class="panel-body">
							<div class="form-group">

								<input type="radio" value="'.($estimate['estimate_total'] + $balance['balance']).'" id="fullpayment" name="payamount" value="'.($estimate['estimate_total'] +$balance['balance']).',1" required>
								<label class="control-label" for="fullpayment"><b>Pay Total Amount Due: <span style="color:#00f;">'.number_format($estimate['estimate_total'] + $balance['balance']).'</span></b></label>
								<br>
								
								<input id="orderpayment" name="payamount" type="radio" value="'.$estimate['estimate_total'].',1" required>
								<label class="control-label" for="orderpayment"><b>Pay Order Amount Due: <span style="color:#00f;">'.number_format($estimate['estimate_total']).'</span></b></label>
								<br>

								<input id="halfpayment" name="payamount" type="radio" value="'.round($estimate['estimate_total']/2).',1" required>
								<label class="control-label" for="halfpayment"><b>Pay Minimum Amount Due: <span style="color:#00f;">'.number_format($estimate['estimate_total']/2).'</span></b></label>
								<br>

								<input id="othername" name="payamount" type="radio" value="'.round($estimate['estimate_total']/2).',2" required>
								<label class="control-label" for="othername"><b>Pay Other Amount:</b></label>
								<br>

								<input type="hidden" id="otheramnt" name="otheramnt" class="form-control" min="'.round($estimate['estimate_total']/2).'">
								<small id="error-msg" style="color: red; display: none;">
									Amount must be at least '.round($estimate['estimate_total']/2).'.
								</small>

							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
		<div class="panel-footer" id="pfooter">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="submit_paynow" name="submit_paynow" value="savepayment" onclick="return confirmSubmit()">Pay Now</button>
					<a href="myestimates.php"class="btn btn-default">Cancel</a>
				</div>
			</div>
		</div>
	</form>
</section>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Order Detail</h2>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed invE_table" id="cartTable" style="margin-top: 10px;">
				<thead>
					<tr>
						<th class="center" width="50">Sr. #</th>
						<th>Item</th>
						<th class="center" width="150">Qty</th>
						<th class="center" width="150">Unit Price</th>
						<th class="center" width="150">Total</th>
					</tr>
				</thead>
				<tbody>';
					$srno 	= 0;
					foreach($estimateDetail as $detail){
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$detail['item_code'].'-'.$detail['item_name'].'</td>
							<td class="center">'.$detail['qty'].'</td>
							<td class="center">'.$detail['unit_price'].'</td>
							<td class="center">'.number_format($detail['total_price']).'</td>
						</tr>';
					}
					echo '
					<tr>
						<th colspan="4" style="text-align:right;">Total Bill Amount:</th>
						<th class="center">Rs. '.number_format($estimate['estimate_total']).'</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>