<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '1')))
{
//-----------------------------------------------
	include_once("students_promote/query.php");
//-----------------------------------------------
echo '
<title>Student Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Students Promote</h2>
	</header>
	<!-- INCLUDEING PAGE -->
	<div class="row">
		<div class="col-md-12">';
			//-----------------------------------------------
			include_once("students_promote/promote.php");
			//-----------------------------------------------
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

	function get_classsection(id_class) {  
        $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
        $.ajax({  
            type: "POST",  
            url: "include/ajax/get_classsection.php",  
            data: "id_class="+id_class,  
            success: function(msg){  
                $("#getclasssection").html(msg); 
                $("#loading").html(''); 
            }
        });  
    }
</script>
<?php 
//------------------------------------
echo '
</section>
</div>
</section>';
//-----------------------------------------------
}
else
{
	header("location: dashboard.php");
}
?>