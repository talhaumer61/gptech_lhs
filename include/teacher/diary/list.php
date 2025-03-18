<?php
echo'
<section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
        <a href="#add" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Diary Note</a>
        <h2 class="panel-title"><i class="fa fa-list"></i> Diary List</h2>
    </header>
    <div class="panel-body">';
        $sqlEmp = $dblms->querylms("SELECT emply_id
                                        FROM ".EMPLOYEES."
                                        WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                        AND id_loginid = '".$_SESSION['userlogininfo']['LOGINIDA']."'
                                    ");
        $valEmp = mysqli_fetch_array($sqlEmp);

        $sqllmsDiary = $dblms->querylms("SELECT d.*, c.class_name, cs.section_name, s.subject_name
                                        FROM ".DIARY." d
                                        INNER JOIN ".CLASSES." c ON c.class_id = d.id_class
                                        INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = d.id_section
                                        INNER JOIN ".CLASS_SUBJECTS." s ON s.subject_id = d.id_subject
                                        WHERE d.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                        AND d.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                        AND d.id_teacher = '".$valEmp['emply_id']."'
                                        ORDER BY d.id DESC
                                    ");
        if(mysqli_num_rows($sqllmsDiary) > 0) {
            echo'
            <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
                <thead>
                    <tr>
                        <th width="40" class="center">Sr.</th>
                        <th>Note</th>
                        <th width="70" class="center">Class</th>
                        <th width="70" class="center">Section</th>
                        <th width="70" class="center">Subject</th>
                        <th width="100" class="center">Dated</th>
                        <th width="70" class="center">Status</th>
                        <th width="100" class="center">Options</th>
                    </tr>
                </thead>
                <tbody>';
                    $sratt = 0;
                    while($valueDiary = mysqli_fetch_assoc($sqllmsDiary)) { 
                        $sratt ++;
                        echo '
                        <tr>
                            <td class="center">'.$sratt.'</td>
                            <td>'.$valueDiary['note'].'</td>
                            <td class="center">'.$valueDiary['class_name'].'</td>
                            <td class="center">'.$valueDiary['section_name'].'</td>
                            <td class="center">'.$valueDiary['subject_name'].'</td>
                            <td class="center">'.$valueDiary['dated'].'</td>
                            <td class="center">'.get_status($valueDiary['status']).'</td>
                            <td class="center">
                            <a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/diary/update.php?id='.$valueDiary['id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a></td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>';
        }else{
            echo'<h2 class="center text-danger">No Record Found</h2>';
        }
        echo'
    </div>
</section>';
?>