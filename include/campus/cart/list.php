<?php
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1) {
	$balance = $api->get_customerbalance($_SESSION['userlogininfo']['CAMPUSCODE']);
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
                
		<h2 class="panel-title"><i class="fa fa-list"></i>  Cart Items</h2>
	</header>
	<form class="form-horizontal" action="#" method="post" id="inv_form"  name="inv_form" enctype="multipart/form-data">
		<input type="hidden" id="campus_code" name="campus_code" value="'.$_SESSION['userlogininfo']['CAMPUSCODE'].'">
		<div class="panel-body" id="pbody">';
			if($_SESSION['cart']){
				
				echo '
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-condensed invE_table" id="cartTable" style="margin-top: 10px;">
						<thead>
							<tr>
								<th class="center" width="40"></th>
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
							foreach($_SESSION['cart'] as $item){
								$srno++;
								$gtotal = $gtotal + ($item['pricesale'] * $item['quantity']);
								
								$itemstock = $api->get_itemstock($item['id']);
								echo '
								<tr>
									<td class="center">
										<a onclick="removeitem(\''.$item['id'].'\')" class="btn btn-primary btn-xs"><i class="fa fa-trash"></i></a>
									</td>
									<td class="center">'.$srno.'</td>
									<td>
										'.$item['code'].'-'.$item['name'].'
										<input type="hidden" name="id_item['.$srno.']" id="id_item_'.$srno.'" class="form-control" value="'.$item['id'].'" >
									</td>
									<td class="center">
										<span style="color:#00f; font-size:16px; font-weight:600; text-align:center;">'.number_format($itemstock['stock']).'</span>
										<input type="hidden" name="is_available['.$srno.']" id="is_available_'.$srno.'" value="'.($itemstock['stock'] > 0 ? '1' : '0').'">
									</td>
									<td class="center">
										<input type="number" name="total_qty['.$srno.']" id="total_qty_'.$srno.'"  onchange="updatequantity(\''.$item['id'].'\',this.value)" style="color:#00f; font-weight:600; text-align:center;" class="form-control jQinv_item_qty" required value="'.$item['quantity'].'" min="0" max="'.$itemstock['stock'].'" >
									</td>
									<td class="center">
										<input type="number" name="unit_price['.$srno.']" id="unit_price_'.$srno.'" style="color:#f00; font-weight:600; text-align:center;" readonly="" class="form-control jQinv_item_rate" value="'.$item['pricesale'].'" >
									</td>
									<td class="center">
										<input type="number" name="total_price['.$srno.']" id="total_price_'.$srno.'" style="color:orangered; font-weight:600; text-align:center;" readonly="" class="form-control jQinv_item_total" value="'.($item['pricesale'] * $item['quantity']).'.00" >
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
				</div>';
			}else{
				echo'
				<div class="text-center">
					<h2 class="text text-center text-danger mt-lg">No item found in the cart!</h2>
					<a href="items.php" class="btn btn-primary">Go to items!</a>
				</div>';
			}
			echo'
		</div>';
		if($_SESSION['cart']){
			echo '
			<div class="panel-footer" id="pfooter">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_estimate" name="submit_estimate" onclick="return confirmSubmit()">Check Out</button>
						<a href="items.php"class="btn btn-default">Cancel</a>
					</div>
				</div>
			</div>';
		}
		echo '
	</form>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>