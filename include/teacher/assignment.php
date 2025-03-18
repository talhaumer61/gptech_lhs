<?php
include_once('assignment/query_assignment.php');
echo'
<title> Assignment Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Assignment Panel </h2>
	</header>
	<div class="row">
		<div class="col-md-12">';
				include_once('assignment/list.php');
				include_once("include/modals/assignments/modal_add.php");
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
				bAutoWidth : false,
				ordering: false,
			});
		});
		
		// SECTION
		$(document).on('change', '#id_class', function() {
			var id_class = $(this).val();
			$.ajax({
				url: "include/ajax/get_section.php",
				type: 'POST',
				data: { 
						id_class: id_class
						},
				success: function(data) {
					$('#id_section').html(data);
				}
			});
		});

		// SECTION
		$(document).on('change', '#id_section', function() {
			var id_section	= $(this).val();
			var id_class	= document.getElementById("id_class").value;
			$.ajax({
				url: "include/ajax/get_classsubject.php",
				type: 'POST',
				data: { 
						 id_section: id_section
						,id_class: id_class
						},
				success: function(data) {
					$('#id_subject').html(data);
				}
			});
		});
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
?>