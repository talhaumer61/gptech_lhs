<?php 
//Add Estimate
if(isset($_POST['submit_estimate'])) { 
	
	// ADD Estimate
	$result	  = $api->add_estimate($_POST);
	
	if($result['result_code'] == '000'){
		$_SESSION['msg']['title'] 	= 'Success';
		$_SESSION['msg']['text'] 	= $result['MSG'];
		$_SESSION['msg']['type'] 	= 'success';
		
		// Unset the session variable that holds the shopping cart data
		unset($_SESSION['cart']);
		header("Location: cart.php?id_estimate=".$result['id_estimate']."", true, 301);
		exit();

	} else{
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= $result['MSG'];
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: cart.php", true, 301);
		exit();
	}
}

//Edit Job Order
if(isset($_POST['submit_changeorder'])) { 
	
	// Edit Job Order
	$result	  = $api->edit_joborder($_POST);
	
	if($result['result_code'] == '000'){
		$_SESSION['msg']['title'] 	= 'Success';
		$_SESSION['msg']['text'] 	= $result['MSG'];
		$_SESSION['msg']['type'] 	= 'success';
		
		header("Location: myorders.php", true, 301);
		exit();

	} else{
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= $result['MSG'];
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: myorders.php", true, 301);
		exit();
	}
}