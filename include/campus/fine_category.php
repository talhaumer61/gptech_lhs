<?php 
	echo '
	<title> Fine Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fine Panel </h2>
		</header>
		<!-- INCLUDEING PAGE -->
		<div class="row">
			<div class="col-md-12">';
				include_once("fine/list_cat.php");
				echo '
			</div>
		</div>';
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		var datatable = $('#table_export').dataTable({
				bAutoWidth : false,
				ordering: false,
			});
		});
	</script>
	<?php 
	echo '
</section>';
?>