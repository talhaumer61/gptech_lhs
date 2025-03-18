<?php
require_once("profile/query_profile.php");
echo'
<title> Profile | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Control Profile</h2>
	</header>
	<div class="row appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
		include_once("profile/detail.php");
		echo'
		<div class="col-md-8">
			<div class="tabs tabs-primary">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#edit" data-toggle="tab"><i class="fa fa-user"></i> <span class="hidden-xs"> My Profile</span></a>
					</li>
					<li>
						<a href="#bankDetail" data-toggle="tab"><i class="fa fa-building"></i> <span class="hidden-xs"> Bank Details</span></a>
					</li>
					<li>
						<a href="#social_links" data-toggle="tab"><i class="fa fa-link"></i> <span class="hidden-xs"> Social Links</span></a>
					</li>
					<li>
						<a href="#resetpass" data-toggle="tab"><i class="fa fa-lock"></i> <span class="hidden-xs"> Change Password</span></a>
					</li>
				</ul>
				<div class="tab-content">';
					include_once("profile/edit_profile.php");
					include_once("profile/bankDetails.php");
					include_once("profile/social_links.php");
					include_once("profile/change_password.php");
					include_once("include/modals/social_links/add.php");
					echo'
				</div>
			</div>
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
</script>';
?>