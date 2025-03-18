<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	require_once("../../../api/db_functions.php");
	$api 	= new main();
	$dblms 	= new dblms();

	checkCpanelLMSALogin();
//---------------------------------------------------------
if(isset($_GET['id'])) {

	$order 		= $api->get_returndetail($_GET['id']);
	$orderdate 	= new DateTime($order['customer']['dated']); 
//---------------------------------------------------------
echo '
<style type="text/css">
table td.label { font-weight:bold; margin: 0; text-align:left; padding:2px; vertical-align:middle !important; }

table.datatable { border: 1px solid #666; border-collapse: collapse; border-spacing: 0; margin-top:10px; }

table.datatable td { 
	border: 1px solid #888; border-collapse:collapse; border-spacing: 0; padding:5px; 
	font-family: Calibri, "Calibri Light", Arial, Helvetica, sans-serif; font-size:15px; color:#555; 
}

table.datatable th { 
	border: 1px solid #888; border-collapse:collapse; border-spacing: 0; padding:7px; background:#f4f4f4; 
	font-family: Calibri, "Calibri Light", Arial, Helvetica, sans-serif; font-size:14px; color:#333 !important;  font-weight:600; 
}

</style>
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="stationary-category.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-folder-close"></i> Order Details</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-hover">
				<tbody>
				<tr>
					<td><strong>Job #:</strong></td>
					<td colspan="3"><span class="label label-info">'.$order['customer']['order_no'].'</span>
						<span style="float:right;">
							<a class="btn btn-xs btn-purple" href="returnprint.php?id='.$_GET['id'].'" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
						</span>
					</td> 
				</tr>
				<tr>	
					<td  width="20%"><strong>Dated:</strong></td>
					<td colspan="3">'.$orderdate->format('d/m/Y').'</td>
				</tr>
				<tr>
					<td><strong>Customer Name:</strong></td>
					<td colspan="3">'.$order['customer']['customer_name'].' '.$order['customer']['customer_cellno'].'</td>
				</tr>
			</tbody>
		</table> 
		<div style="clear:both;"></div>
		
		<div style="clear:both;"></div>
		<table class="datatable" style="margin:3px 0; width:100%;">
		<thead>
		<tr>
			<th style="text-align:center; font-weight:bold;">Sr #</th>
			<th style="text-align:left; font-weight:bold; ">Item Code</th>
			<th style="text-align:left; font-weight:bold;">Item Name</th>
			<th style="text-align:center; font-weight:bold;">QTY</th>
			<th style="text-align:center; font-weight:bold;">Unit Price</th>
			<th style="text-align:center;">Discount %</th>
			<th style="text-align:center; font-weight:bold;">Total</th>
		</tr>
		</thead>
		<tbody>';
		$totalqty = 0;
		//------------------------------------------------
		$srno = 0;
		//------------------------------------------------

			foreach($order['orderdetail'] as $rowbills) {
		//------------------------------------------------
		$srno++;
		//------------------------------------------------
		echo '
		<tr>
			<td style="text-align:center; width:40px;">'.$srno.'</td>
			<td style="text-align:center; width:80px;">'.$rowbills['item_code'].'</td>
			<td style="text-align:left;">'.$rowbills['item_name'].'</td>
			<td style="text-align:center; width:50px;">
				'.number_format($rowbills['total_qty']).'
			</td>
			<td style="text-align:right; width:80px;">
				'.number_format($rowbills['unit_price']).'
			</td>
			<td style="text-align:center; width:90px;">
				'.($rowbills['discount']).'
			</td>
			<td style="text-align:right; width:70px;">
				'.number_format($rowbills['total_price']).'
			</td>
		</tr>';
		//------------------------------------------------
		} // end while loop
		//--------------------------------------------------
		echo '
		</tbody>
		<thead>
		<tr>
			<th colspan="5" style="text-align:right; font-weight:bold;">Total: </th>
			<th colspan="2" style="text-align:right; font-weight:bold;">'.number_format($order['customer']['total']).'</th>
		</tr>
		<tr>
			<th colspan="5" style="text-align:right; font-weight:bold;">Deducation %: </th>
			<th colspan="2" style="text-align:right; font-weight:600; color:#f00;">'.number_format($order['customer']['deducation_per']).'</th>
		</tr>
		<tr>
			<th colspan="5" style="text-align:right; font-weight:bold;">Deducation Amount: </th>
			<th colspan="2" style="text-align:right; font-weight:600;">'.number_format($order['customer']['deducation_amount']).'</th>
		</tr>
		<tr>
			<th colspan="5" style="text-align:right; font-weight:bold;">Grand Total: </th>
			<th colspan="2" style="text-align:right; font-weight:600;">'.number_format(($order['customer']['grand_total'])).'</th>
		</tr>
		</thead>
		</table>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
?>