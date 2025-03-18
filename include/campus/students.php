<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '1'))){
	include_once("students/query_students.php");
	echo'
	<title>Student Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>';
				if(isset($_GET['id'])){
					echo' Student Profile';
				}elseif($view == 'admission_letter'){
					echo' Admission Letter Panel';
				}elseif($view == 'leaving_certificate'){
					echo' School Leaving Certificate Panel';
				}elseif($view == 'character_certificate'){
					echo' Character Certificate Panel';
				}elseif($view == 'import_admissions'){
					echo' Import Student Panel';
				}else{
					echo' Student Panel';
				}
				echo'
			</h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if($view == 'add'){
					include_once("students/student_add.php");
				}
				elseif(isset($_GET['inquiry'])){
					include_once("students/inquiry_add.php");
				}
				elseif(isset($_GET['id'])){
					include_once("students/student_edit.php");
				}
				elseif($view == 'admission_letter'){
					include_once("students/admission_letter.php");
				}
				elseif($view == 'leaving_certificate'){
					include_once("students/leaving_certificate.php");
				}
				elseif($view == 'character_certificate'){
					include_once("students/character_certificate.php");
				}
				elseif($view == 'import_admissions'){
					include_once("students/import_admissions.php");
				}
				else{
					include_once("students/list_students.php");
				}
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
			$(document).ready(function() {
				$('#is_new').change(function() {
					if ($(this).is(':checked')) {
					// Checkbox is checked
					var id_class			= $('[name=id_class]').val();
					var id_section			= $('[name=id_section]').val();
					$.post('include/ajax/get_admission_challan.php',
						{
							 id_class: id_class
							,id_section: id_section
						}, function(response) {
						// RESPONSE
						$('#get_admission_challan').html(response);
					});
					} else {
					// Checkbox is unchecked
					$('#get_admission_challan').html(''); // Clear the contents of the div
					}
				});
			});
			function get_formno(form_no) {
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');
				$.ajax({
					type: "POST",
					url: "include/ajax/get_admissiondetail.php",
					data: "form_no=" + form_no,
					success: function(msg) {
						$("#getadmissiondetail").html(msg);
						$("#loading").html('');
					}
				});
			}
			function get_classsection(id_class) {
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');
				$.ajax({
					type: "POST",
					url: "include/ajax/get_classsection.php",
					data: "id_class=" + id_class,
					success: function(msg) {
						$("#getclasssection").html(msg);
						$("#loading").html('');
					}
				});
			}
			function get_editclasssection(id_class) {
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');
				$.ajax({
					type: "POST",
					url: "include/ajax/get_editclasssection.php",
					data: "id_class=" + id_class,
					success: function(msg) {
						$("#geteditclasssection").html(msg);
						$("#loading").html('');
					}
				});
			}			
			function get_section(id_class) {  
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_section.php",  
					data: "id_class="+id_class,  
					success: function(msg){  
						$("#id_section").html(msg); 
						$("#loading").html(''); 
					}
				});  
			}
			function get_classlevel(id_classlevel) {
				$.ajax({
					type: "POST",
					url: "include/ajax/get_classlevel.php",
					data: "id_classlevel=" + id_classlevel,
					success: function(msg) {
						$("#id_class").html(msg);
						$("#loading").html("");
					}
				});
			}
			function print_report(printResult) {
				var printContents = document.getElementById(printResult).innerHTML;
				var originalContents = document.body.innerHTML;
				document.body.innerHTML = printContents;
				window.print();
				document.body.innerHTML = originalContents;
			}
			$(document).on("input", ".amount", function() {
				var sum = 0;
				$(".amount").each(function(){
					sum += +$(this).val();
				});
				$("#total_amount").val(sum);
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
}else{
	header("location: dashboard.php");
}
?>