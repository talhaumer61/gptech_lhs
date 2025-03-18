<?php 
if($_SESSION['userlogininfo']['LOGINTYPE']  == 1){ 
	include_once("cart/query.php");
	include_once("store/orders/list.php"); 	
	include_once("store/orders/edit.php"); 
?>
<script type="text/javascript">

			jQuery(document).ready(function($) {
				<?php 
				if(isset($_SESSION['msg'])) { 
					echo 'new PNotify({
						title	: "'.$_SESSION['msg']['title'].'"	,
						text	: "'.$_SESSION['msg']['text'].'"	,
						type	: "'.$_SESSION['msg']['type'].'"	,
						hide	: true	,
						buttons: {
							closer	: true	,
							sticker	: false
						}
					});';
					unset($_SESSION['msg']);
				}
				?>
				var datatable = $('#table_export').dataTable({
					bAutoWidth: false,
					ordering: false,
				});
			});

			/* [ ---- Beoro Admin - invoices ---- ] */
			$(document).ready(function() {

				//* clone row
				var id = 0;
				$(".inv_clone_btn").click(function() {
					id++;
					var table = $(this).closest("table");
					var clonedRow = table.find(".inv_row").clone();
					clonedRow.removeAttr("class")
					clonedRow.find(".id").attr("value", id);
					clonedRow.find(".inv_clone_row").html('<i class="icon-minus inv_remove_btn"></i>');
					clonedRow.find("input").each(function() {
						$(this).val('');
					});
					table.find(".last_row").before(clonedRow);
				});

				//* remove row
				$(".invE_table").on("click",".inv_remove_btn", function() {
					$(this).closest("tr").remove();
					rowInputs();
				});

				//* subtotal sum
				$('#inv_form').on('keyup','.jQinv_item_qty, .jQinv_item_rate', function() {
					rowInputs();
				});

				function rowInputs() {
					//var balance = 0;
					//var subTotal = 0;
					var grandTotal = 0;
					//var taxTotal = 0;
					$(".invE_table tr").not('.last_row').each(function () {
						var $qty 			= $('.jQinv_item_qty', this).val();
						var $rate 			= $('.jQinv_item_rate', this).val();
						//monthly
						var $linetotal 	= ($rate * $qty); 
						var parsedSubTotal = parseFloat( ('0' + $linetotal).replace(/[^0-9-\.]/g, ''), 10 );
						$('.jQinv_item_total',this).val($linetotal.toFixed(2));
						grandTotal += parsedSubTotal;
						
					});
					var openingbalance = document.getElementById("opening_balance").value;  
            		var totalReceivable = ((openingbalance * 1) + (grandTotal * 1)); 
					$(".inV_grandtotal span").html(grandTotal.toFixed(2));
					$('#inV_grandtotal').val(grandTotal.toFixed(2));
					$(".inV_receiveable span").html(totalReceivable.toFixed(2));
					$('#inV_receiveable').val(totalReceivable.toFixed(2));
				}

				function clearInvForm() {
					$('#inv_form').find('input').each(function() {
						$(this).val('');
						$(".inV_grandtotal span").html('0.00');
					})
				}



			});
			
			function updatequantity(id,value) {
				$("#loading").html('<img src="assets/images/preloader.gif"> loading...');
				$.ajax({
					type: "POST",
					url: "include/ajax/cart/updatequantity.php", 
					data: {
								id: id,
								value: value
							},
					success: function(msg) {
						data = JSON.parse(msg);
						new PNotify({
							title	: data.title,
							text	: data.text,
							type	: data.type,
							hide	: true,
							buttons: {
								closer	: true	,
								sticker	: false
							}
						});
					}
				});
			}

			function removeitem(item_id) {
				$("#loading").html('<img src="assets/images/preloader.gif"> loading...');
				$.ajax({
					type: "POST",
					url: "include/ajax/cart/removeitem.php", 
					data: {
							item_id: item_id
						  },
					success: function(msg) {
						data = JSON.parse(msg);
						if (data.status_code == '11') {
							$('#pbody').html(data.pbody);
							$('#pfooter').hide();
						} else if (data.status_code == '00') {
							$('#cartTable tbody').html(data.tbody);
						}
						new PNotify({
							title	: data.title,
							text	: data.text,
							type	: data.type,
							hide	: true,
							buttons: {
								closer	: true	,
								sticker	: false
							}
						});
					}
				});
			}

			function confirmSubmit() {
				var agree=confirm("Are you sure you wish to continue?");
				if (agree)
					return true ;
				else
					return false ;
			}
		</script>
<?php 
}else{
	header("location: dashboard.php");
}
