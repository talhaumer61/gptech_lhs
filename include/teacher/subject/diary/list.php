<?php 
if(($view != 'add') && !isset($_GET['edit_id'])) {

    $sqllmsDiary	= $dblms->querylms("SELECT id, status, dated, note
                                        FROM ".DIARY."
                                        WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                        AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                        AND id_teacher = '".$value_emp['emply_id']."' 
                                        AND id_section = '".$_GET['section']."' AND id_class = '".$_GET['class']."' 
                                        AND id_subject = '".$_GET['id']."'");
                                        
    if (mysqli_num_rows($sqllmsDiary) > 0) {
        echo '
        <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
            <thead>
                <tr>
                    <th class="center" width="50">#</th>
                    <th class="center" width="150">Date</th>
                    <th>Note</th>
                    <th class="center" width="70">Status</th>
                    <th width="100px;" class="center">Options</th>
                </tr>
            </thead>
            <tbody>';
                $sratt = 0;
                while($valueDiary = mysqli_fetch_assoc($sqllmsDiary)) { 
                    $sratt ++;
                    echo '
                    <tr>
                        <td class="text-center">'.$sratt.'</td>
                        <td class="center">'.date('D d M Y' , strtotime(cleanvars($valueDiary['dated']))).'</td>
                        <td>'.$valueDiary['note'].'</td>
                        <td class="center">'.get_status($valueDiary['status']).'</td>
                        <td class="center">
                            <a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" 
                                onclick="showAjaxModalZoom(\'include/modals/diary/update.php?id='.$valueDiary['id'].'&stat='.$valueDiary['status'].'&note='.$valueDiary['note'].'\');">
                                <i class="glyphicon glyphicon-edit"></i> Edit
                            </a>
                        </td>
                    </tr>';
                }
                echo '
            </tbody>
        </table>';
    } else {
        echo'<h4 class="center">No Record Found</h4>';
    }
}
?>