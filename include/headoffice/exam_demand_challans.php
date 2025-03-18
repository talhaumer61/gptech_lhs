<?php 

	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
	require_once("include/hostels/query_hostels.php");
	include_once("include/header.php");
    
echo '
<title> Exam Demand Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Exam Demand Panel </h2>
	</header>
    <!-- INCLUDEING PAGE -->
    <div class="row">
        <div class="col-md-12">';
	        if($_SESSION['userlogininfo']['LOGINAFOR'] == 1){
                
                include_once("include/headoffice/exam_demand/challans/query.php");
                if($view == 'add_single'){
                    include_once("include/headoffice/exam_demand/challans/add_single.php");
                } else if($view == 'add_bulk'){
                    include_once("include/headoffice/exam_demand/challans/add_bulk.php");
                }else if(isset($_GET['id'])){
                    include_once("include/headoffice/exam_demand/challans/edit.php");
                } else {
                    include_once("include/headoffice/exam_demand/challans/list.php");
                }
                
            }  else{
                header("Location: dashboard.php");
            }
            echo '
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
            sDom: "<'text-right mb-md'T>" + $.fn.dataTable.defaults.sDom,
            oTableTools: {
                sSwfPath: 'assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf',
                aButtons: [
                    {
                        sExtends: 'pdf',
                        sButtonText: 'PDF',
                        mColumns: [0,1,2,3,4]
                    },
                    {
                        sExtends: 'csv',
                        sButtonText: 'CSV',
                        mColumns: [0,1,2,3,4]
                    },
                    {
                        sExtends: 'xls',
                        sButtonText: 'Excel',
                        mColumns:[0,1,2,3,4]
                    },
                    {
                        sExtends: 'print',
                        sButtonText: 'Print',
                        sInfo: '',
                        fnClick: function (nButton, oConfig) {
                            datatable.fnSetColumnVis(5, false);
                            
                            this.fnPrint( true, oConfig );
                            
                            window.print();
                            
                            $(window).keyup(function(e) {
                                if (e.which == 27) {
                                    datatable.fnSetColumnVis(5, true);
                                }
                            });
                        }
                    }
                ]
            }
        });
	});
</script>
<?php 

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

	include_once("include/footer.php");
    
?>