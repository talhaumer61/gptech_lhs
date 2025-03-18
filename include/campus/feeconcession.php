<?php 
//-----------------------------------------------
	require_once("feeconcession/query.php");
//-----------------------------------------------
echo '
<title> Fee Concession Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Fee Concession Panel </h2>
	</header>
<!-- INCLUDEING PAGE -->
<div class="row">
<div class="col-md-12">';
	if($view == 'add') {
		include_once("feeconcession/add.php");
	} else if(isset($_GET['id'])) {
		include_once("feeconcession/edit.php");
	} else {
		include_once("feeconcession/list_feeconcession.php");
		include_once("include/modals/feeconcession/feeconcession_add.php");
	}
echo '
</div>
</div>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
<?php 
//-----------------------------------------------
if(isset($_SESSION['msg'])) { 
//-----------------------------------------------
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
//-----------------------------------------------
    unset($_SESSION['msg']);
//-----------------------------------------------
}
//-----------------------------------------------
?>	
var datatable = $('#table_export').dataTable({
			bAutoWidth : false,
			ordering: false,
		});
	});
	
	//Return Students Against Class
	function get_classstudent_fee(id_class) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_classstudent_fee.php",  
			data: "id_class="+id_class,  
			success: function(msg){  
				$("#getclassstudent").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	
	//Return Fee Amount Against Cat
	function get_feecat_amount(id_std) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_classstudent_fee.php", 
			data: "id_std="+id_std,  
			success: function(msg){  
				$("#getfeeamount").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	
	//Return Concession Input Fields
	function get_concession_fields(id_type) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({
			type: "POST",  
			url: "include/ajax/get_classstudent_fee.php",  
			data: "id_type="+id_type,
			success: function(msg){  
				$("#getconcessiontype").html(msg);
				$("#loading").html(''); 
			}
		});  
		
	}

	// //Calculate Concession Amount
	// $(document).on("input", ".percent", function() {
	// 	alert('test');
	// 	var percentage = document.getElementById("percent").value;
	// 	var tuitionfee = document.getElementById("amount").value;
	// 	concession = (percentage *  tuitionfee) / 100;
	// 	$("#conamount").val(concession);
	// });

	// //Calculate Concession Percentage
	// $(document).on("input", ".amount", function() {
	// 	alert('test');
	// 	var amount = document.getElementById("amount").value;
	// 	var tuitionfee = document.getElementById("totalamount").value;
	// 	concession = (amount /  tuitionfee) * 100;
	// 	$("#conpercentage").val(concession);
	// });
	
</script>
<?php 
//------------------------------------
echo '
</section>
</div>
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
			$.ajax( {
				url: delete_url,
				type: "POST"
			} )
			.done( function ( data ) {
				swal( {
					title: "Deleted",
					text: "Information has been successfully deleted",
					type: "success"
				}, function () {
					location.reload();
				} );
			} )
			.error( function ( data ) {
				swal( "Oops", "We couldn\'t\ connect to the server!", "error" );
			} );
		} );
	}
</script>    
<!-- INCLUDES BOTTOM -->';
//-----------------------------------------------
?>