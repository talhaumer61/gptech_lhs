<?php
if($_SESSION['userlogininfo']['LOGINTYPE'] == 1) {
	$balance = $api->get_customerbalance($_SESSION['userlogininfo']['CAMPUSCODE']);
echo'
<style>
	.product {
		display: -ms-flexbox;
		display: flex;
		margin: 0 !important;
		padding: 2rem 0;
		-ms-flex-align: center;
		align-items: center;
		border-bottom: 1px solid #e6ebee;
		box-shadow: none !important;
		font-family: Poppins, sans-serif;
	}
	.product-details {
		margin-bottom: 3px;
		font-size: 1.3rem;
	}
	.product-title {
		padding-right: 1.5rem;
		margin-bottom: 1.1rem;
		font-size: 12px;
		line-height: 19px;
		color: #222529;
		font-weight: 600;
	}	
	.cart-product-info {
		color: #696969;
	}
	.product-image-container {
		position: relative;
		max-width: 70px;
		width: 100%;
		margin: 0;
		margin-left: auto;
		border: 1px solid #f4f4f4;
	}
	.btn-remove {
		position: absolute;
		top: -11px;
		right: -9px;
		width: 2rem;
		height: 2rem;
		border-radius: 50%;
		color: inherit;
		background-color: #fff;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
		text-align: center;
		line-height: 2rem;
		font-size: 1.8rem;
		font-weight: 500;
	}
	.dropdown-cart-total {
		display: flex;
		align-items: center;
		margin-top: 1.5rem;
		margin-bottom: 1.4rem;
		font-size: 1.3rem;
		font-family: Poppins, sans-serif;
		font-weight: 700;
		line-height: 38px;
		color: #0099e6 !important
	}
	.scrollable{
        overflow-y: auto;
        height: 950px;
    }
	.dropdown-cart-total .cart-total-price {
		margin-left: auto;
		font-size: 1.5rem;
		float: right !important;
	}
	.dropdown-cart-action .btn {
		padding: 1.3rem 2.5rem 1.4rem;
		border-radius: 0.2rem;
		color: #fff;
		height: auto;
		font-size: 1.2rem;
		font-weight: 600;
		font-family: Poppins, sans-serif;
		letter-spacing: 0.025em;
		border-color: transparent;
	}

	.quantity-container {
        display: flex;
        align-items: center;
        gap: 5px; /* Spacing between elements */
    }

    .quantity-container button {
        width: 30px;
        height: 30px;
        border: 1px solid #ccc;
        background-color: #f7f7f7;
        color: #333;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-container button:hover {
        background-color: #e0e0e0;
    }

    .quantity-container input {
        width: 30px;
        text-align: center;
        height: 30px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .quantity-container .price {
		font-weight: 600;
        font-size: 13px;
        color: #333;
        margin-left: 5px; /* Add spacing from the last button */
    }
</style>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
                
		<h2 class="panel-title"><i class="fa fa-list"></i>  Cart Items</h2>
	</header>
	<form class="form-horizontal" action="#" method="post" id="inv_form"  name="inv_form" enctype="multipart/form-data">
		<input type="hidden" id="campus_code" name="campus_code" value="'.$_SESSION['userlogininfo']['CAMPUSCODE'].'">
		<div class="panel-body scrollable" id="pbody">';
			echo '
			<div class="dropdown-cart-products" id="cart-products">';
				if($_SESSION['cart']){
					foreach($_SESSION['cart'] as $item){
						$srno++;
						$gtotal += ($item['pricesale'] * $item['quantity']);
						
						$itemstock = $api->get_itemstock($item['id']);
						echo '
						<div class="product">
							<div class="product-details">
								<h4 class="product-title">'.$item['code'].'-'.$item['name'].'</h4>
								<span class="cart-product-info">
									<div class="quantity-container">
										<button type="button" onclick="updateQuantity(\''.$item['id'].'\', -1)">-</button>
										<input type="text" value="'.$item['quantity'].'" id="quantity-'.$item['id'].'" onchange="updateSubtotal();">
										<button type="button" class="btn btn-primar" onclick="updateQuantity(\''.$item['id'].'\', 1)">+</button>
										<span class="price">× Rs.'.$item['pricesale'].'</span>
									</div>
								</span>
							</div>
							<figure class="product-image-container">
								<img src="'.$item['photo'].'" alt="product" width="50" height="50">
								<a onclick="removeitem(\''.$item['id'].'\')" class="btn-remove" title="Remove Product"><span>×</span></a>
							</figure>
						</div>';
					}
					echo '
					<div class="dropdown-cart-total">
						<span>SUBTOTAL:</span>
						<span class="cart-total-price" id="cart-subtotal">Rs.'.$gtotal.'.00</span>
					</div>
					<div class="dropdown-cart-action">
						<a href="cart.php" class="btn btn-primary btn-block">View Cart</a>
					</div>';
				}else{
					echo'
					<div class="text-center">
						<h2 class="text text-center text-danger mt-lg">No item!</h2>
					</div>';
				}
				echo'
			</div>';
		echo '
	</form>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>