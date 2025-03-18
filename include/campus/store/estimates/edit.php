<?php 
if(isset($_GET['id'])  && !LMS_VIEW) { 
	$balance = $api->get_customerbalance($_SESSION['userlogininfo']['CAMPUSCODE']);
	$order = $api->get_orderdetail($_GET['id']);
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

						<h2 class="panel-title"><i class="fa fa-folder-edit"></i>  Edit Job Order </h2>
					</header>
				
						<div class="panel-body" id="pbody">

			<form class="form-horizontal" action="#" method="post" id="inv_form"  name="inv_form" enctype="multipart/form-data">
				<input type="hidden" id="campus_code" name="campus_code" value="'.$_SESSION['userlogininfo']['CAMPUSCODE'].'">
				<input type="hidden" id="orderid" name="orderid" value="'.$_GET['id'].'">
				<div class="panel-body" id="pbody">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-condensed invE_table" id="cartTable" style="margin-top: 10px;">
						<thead>
							<tr>
								<th class="center" width="50">Sr. #</th>
								<th>Items</th>
								<th class="center" width="150">Avaiable Stock</th>
								<th class="center" width="150">Qty</th>
								<th class="center" width="150">Unit Price</th>
								<th class="center" width="150">Total</th>
							</tr>
						</thead>
						<tbody>';
							$srno 	= 0;
							$gtotal = 0;
							foreach($order['orderdetail'] as $item) {
								$srno++;
								$gtotal = $gtotal + ($item['unit_price'] * $item['total_qty']);
								
								$itemstock = $api->get_itemstock($item['item_id']);
								echo '
								<tr>
									<td class="center">'.$srno.'</td>
									<td>
										'.$item['item_code'].'-'.$item['item_name'].'
										<input type="hidden" name="id_item['.$srno.']" id="id_item_'.$srno.'" class="form-control" value="'.$item['item_id'].'" >
									</td>
									<td class="center">
										<span style="color:#00f; font-size:16px; font-weight:600; text-align:center;">'.number_format($itemstock['stock']).'</span>
									</td>
									<td class="center">
										<input type="number" name="total_qty['.$srno.']" id="total_qty_'.$srno.'"  style="color:#00f; font-weight:600; text-align:center;" class="form-control jQinv_item_qty" required value="'.$item['total_qty'].'" min="0" max="'.$itemstock['stock'].'" >
									</td>
									<td class="center">
										<input type="number" name="unit_price['.$srno.']" id="unit_price_'.$srno.'" style="color:#f00; font-weight:600; text-align:center;" readonly="" class="form-control jQinv_item_rate" value="'.$item['unit_price'].'" >
									</td>
									<td class="center">
										<input type="number" name="total_price['.$srno.']" id="total_price_'.$srno.'" style="color:orangered; font-weight:600; text-align:center;" readonly="" class="form-control jQinv_item_total" value="'.($item['total_price']).'.00" >
									</td>
								</tr>';
							}
							echo '
							<tr>
								<td colspan="7" style="text-align:right; padding: 11px 11px 0px 0px;">
									<p class="clearfix"><b> Total Bill Amount: <span class="inV_grandtotal">Rs.<span>'.$gtotal.'.00</span></span></b></p>
									<p class="clearfix"><b> Opening Balance: <span>Rs.<span>'.$balance['balance'].'.00</span></span></b></p>
									<p class="clearfix"><b> Total Receiveable: <span class="inV_receiveable">Rs.<span>'.($gtotal + $balance['balance']).'.00</span></span></b></p>
									<input type="hidden" id="inV_grandtotal" name="inV_grandtotal" value="'.$gtotal.'">
									<input type="hidden" id="opening_balance" name="opening_balance" value="'.$balance['balance'].'">
									<input type="hidden" id="inV_receiveable" name="inV_receiveable" value="'.($gtotal+ $balance['balance']).'">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
		</div>
			<div class="panel-footer" id="pfooter">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_changeorder" name="submit_changeorder" onclick="return confirmSubmit()">Check Out</button>
						<a href="myorders.php"class="btn btn-default">Cancel</a>
					</div>
				</div>
			</div>
	</form>
</section>
</div>
		</div>
	</section>';
?>

<?php 
	
}