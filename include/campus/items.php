<?php 
if($_SESSION['userlogininfo']['LOGINTYPE']  == 1){
	echo'
	<title>Items Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2> Items Panel</h2>
		</header>
		<div class="row">
			<div class="col-md-9">';
				include_once("items/list.php");
				echo'
			</div>
			<div class="col-md-3">';
				include_once("items/cart.php");
				echo'
			</div>
		</div>';
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
			function addtocart(id,photo,code,name,pricesale) {
				$.ajax({
					type: "POST",
					url: "include/ajax/cart/addtocart.php", 
					data: {
								id: id,
								photo: photo,
								code: code,
								name: name,
								pricesale: pricesale
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
						$('#cart-item').text(data.cnt);
						$('#cart-products').html(data.cartHtml);
					}
				});
			}
			function removeitem(item_id) {
				$.ajax({
					type: "POST",
					url: "include/ajax/cart/removeproduct.php", 
					data: {
							item_id: item_id
						  },
					success: function(msg) {
						data = JSON.parse(msg);
							$('#cart-products').html(data.cartHtml);
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
			function search() {
				$("#loading").html('<img src="assets/images/preloader.gif"> loading...');
				var search_word = $('#search_word').val();
				var id_cls 		= $('#id_cls').val();
				var id_cat 		= $('#id_cat').val();
				$.ajax({
					type: "POST",
					url: "include/ajax/cart/searchitems.php", 
					data: {
								search_word: search_word,
								id_cls: id_cls,
								id_cat: id_cat
							},
					success: function(msg) {
						$('#items').html(msg);
					}
				});
			}
			function updateQuantity(productId, change) {
				const quantityInput = document.getElementById(`quantity-${productId}`);
				let currentQuantity = parseInt(quantityInput.value);
				if (!isNaN(currentQuantity)) {
					currentQuantity = Math.max(1, currentQuantity + change); // Ensure quantity is at least 1
					quantityInput.value = currentQuantity;
				}
				updateSubtotal();
				updatequantity(productId, currentQuantity);
			}
			function updateSubtotal() {
				let subtotal = 0;
				const products = document.querySelectorAll('.product');
				products.forEach(product => {
					const price = parseFloat(product.querySelector('.cart-product-info').textContent.split('× Rs.')[1]);
					const quantityInput = product.querySelector('input[type="text"]');
					const quantity = parseInt(quantityInput.value);
					if (!isNaN(price) && !isNaN(quantity)) {
						subtotal += price * quantity;
					}
				});
				document.getElementById('cart-subtotal').textContent = `Rs.${subtotal.toFixed(2)}`;
			}
		</script>
		<?php
		echo'
	</section>
	
	<!-- INCLUDES MODAL -->
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
	</script>

	<!-- (STYLE AJAX MODAL)-->
	<div id="show_modal" class="mfp-with-anim modal-block modal-block-primary mfp-hide"></div>
	<script type="text/javascript">
		function confirm_modal( delete_url ) {
			swal( {
				title: "Are you sure?",
				text: "Are you sure that you want to delete this information?",
				type: "warning",
				showCancelButton: true,
				showLoaderOnConfirm: true,
				closeOnConfirm: false,
				confirmButtonText: "Yes, delete it!",
				cancelButtonText: "Cancel",
				confirmButtonColor: "#ec6c62"
			}, function () {
				// location.reload();
				window.location.href = delete_url;
			} );
		}
	</script>';
}else{
	header("location: dashboard.php");
}
?>