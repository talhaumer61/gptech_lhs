<?php 
    ini_set('memory_limit', '-1');
    set_time_limit(1000); 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	include_once("include/header.php");
    $id_class = '';
    $id_session = '';
    if(isset($_POST['id_session'])){
        $arraySession 	= explode('|', $_POST['id_session']);
		$id_session		= $arraySession[0];
		$arraySesName	= explode('-', $arraySession[1]);
		$sesYear		= $arraySesName[1];
    }
    if(isset($_POST['id_class'])){
        $id_class = $_POST['id_class'];
    }	
    echo '
    <title>Genrate Student Login | '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Genrate Student Login</h2>
        </header>
        <!-- INCLUDEING PAGE -->
        <div class="row">
            <div class="col-md-12">';
                echo '  
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i>  Select Class</h2>
                    </header>
                    <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="panel-body">
                            <div class="row mb-lg">
                                <div class="col-md-6 col-sm-offset-3">
                                    <div class="form-group">
                                        <label class="control-label">Session <span class="required">*</span></label>
                                        <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session">
                                            <option value="">Select</option>';
                                            $sqllmsSession	= $dblms->querylms("SELECT session_id, session_status, session_name 
                                                                FROM ".SESSIONS."
                                                                WHERE is_deleted			= '0'
                                                                ORDER BY session_id ASC");
                                            while($valueSession = mysqli_fetch_array($sqllmsSession)) {
                                                echo '<option value="'.$valueSession['session_id'].'|'.$valueSession['session_name'].'" '.($valueSession['session_id'] == $id_session ? 'selected' : '').'>'.$valueSession['session_name'].'</option>';
                                            }
                                        echo '
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <center>
                                <button type="submit" name="view_lecture" id="view_lecture" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
                            </center>
                        </div>
                    </form>
                </section>';
                if(isset($_POST['view_lecture'])){
                    echo '
                    <section class="panel panel-featured panel-featured-primary">
                        <header class="panel-heading">
                            <h2 class="panel-title"><i class="fa fa-list"></i>  New Registeration Number List</h2>
                        </header>
                        <div class="panel-body">';
                            // ASSIGN NEW REGISTERATION NUMBER 
                            $sqllmsStd	= $dblms->querylms("SELECT std_id, std_status, std_regno, std_name, std_phone, id_campus 
                                                            FROM ".STUDENTS."
                                                            WHERE id_session = '".$id_session."'
                                                            AND std_regno != ''
                                                            AND id_loginid = '0'
                                                            ORDER BY std_id ASC
                                                          ");
                            while($valueStd = mysqli_fetch_array($sqllmsStd)){


                                // LOGIN INFORMATION
                                $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
                                $pass = 'lhis@786';
                                $password = hash('sha256', $pass . $salt);
                                for ($round = 0; $round < 65536; $round++) {
                                    $password = hash('sha256', $password . $salt);
                                }
                                // HASHING
                                $sqllms  = $dblms->querylms("INSERT INTO ".ADMINS."(
                                                                                      adm_status  
                                                                                    , adm_logintype 
                                                                                    , adm_username 
                                                                                    , adm_salt
                                                                                    , adm_userpass
                                                                                    , adm_fullname
                                                                                    , adm_phone
                                                                                    , id_campus 	
                                                                                )
                                                                                VALUES(
                                                                                    '".cleanvars($valueStd['std_status'])."' 
                                                                                    , '5'
                                                                                    , '".cleanvars($valueStd['std_regno'].'@lhis.edu.pk')."'
                                                                                    , '".cleanvars($salt)."'
                                                                                    , '".cleanvars($password)."'
                                                                                    , '".cleanvars($valueStd['std_name'])."'
                                                                                    , '".cleanvars($valueStd['std_phone'])."'
                                                                                    , '".cleanvars($valueStd['id_campus'])."'	
                                                                                )
                                                            ");
                                $adm_id = $dblms->lastestid();

                                $sqllmsestd  = $dblms->querylms("UPDATE ".STUDENTS." 
                                                                            SET id_loginid = '".(cleanvars($adm_id))."' 
												                            WHERE std_id = '".cleanvars($valueStd['std_id'])."'
                                                               ");
                                echo $adm_id. '<br>';
                            }
                            echo '
                        </div>
                    </section>';
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