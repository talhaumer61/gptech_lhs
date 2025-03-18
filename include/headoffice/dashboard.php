<?php 
$id_campus  =   '';
$sqlCampus  =   '';
if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
    $id_campus  =   implode(',',$_POST['id_campus']);
    $sqlCampus  =   "AND campus_id IN (".$id_campus.")";
}
echo '
<title> Dashboard | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Admin Panel</h2>
	</header>
    <!-- INCLUDEING PAGE -->
    <form action="#" method="POST" autocomplete="off">
        <div class="form-group mb-lg">
            <div class="row">
                <div class="col-sm-12">
					<label>Campuses <span class="text-danger">(Filter Dashboard)</span></label>
                    <div class="input-group">
                        <select data-plugin-selectTwo data-width="100%" name="id_campus[]" id="id_campus[]" multiple class="form-control populate">';
                            $sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
                                                                FROM ".CAMPUS." c  
                                                                WHERE c.campus_id != '' AND campus_status = '1'
                                                                ORDER BY c.campus_name ASC");
                            while($value_campus = mysqli_fetch_array($sqllmscampus)){
                                echo'<option value="'.$value_campus['campus_id'].'" '.(in_array($value_campus['campus_id'], $_POST['id_campus']) ? 'selected' : '').'>'.$value_campus['campus_name'].'</option>';
                            }
                            echo'
                        </select>
                        <div class="input-group-btn">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">';
        //include "dashboard/financegraph.php";
        include "dashboard/main_counter.php";
        echo '
    </div>
    <div class="row">';
        include "dashboard/notice_borad.php";
        include "dashboard/event_calendar.php";
        echo'
    </div>';
	include "dashboard/campuswisestudents.php";
	include "dashboard/campuswisestaff.php";
    echo '
    <div class="row">';
        //include "dashboard/event_calendar.php";
        //include "dashboard/calculation.php";
        echo '
    </div>
</section>';
?>