<?php
echo'
<section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
        <h2 class="panel-title"><i class="fa fa-list"></i> Assignment List</h2>
    </header>
    <div class="panel-body">';
        $sqllms	= $dblms->querylms("SELECT s.std_id, s.id_class, s.id_section
                                    FROM  ".STUDENTS." s
                                    WHERE s.id_campus   = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                    AND s.id_loginid    = '".$_SESSION['userlogininfo']['LOGINIDA']."' LIMIT 1");
        $rowsvalues = mysqli_fetch_array($sqllms);

        $sqllmsassignment	= $dblms->querylms("SELECT a.assig_id, a.assig_status, a.assig_title, a.assig_file, a.assig_note, a.open_date, a.close_date,
                                                s.subject_name
                                                FROM ".ASSIGNMENT." a
                                                INNER JOIN ".CLASS_SUBJECTS." s ON s.subject_id = a.id_subject
                                                WHERE a.id_campus   = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                                AND a.id_session    = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
                                                AND a.id_class      = '".$rowsvalues['id_class']."'
                                                AND a.id_section    = '".$rowsvalues['id_section']."'
                                            ");
        if(mysqli_num_rows($sqllmsassignment) > 0) {
            echo'
            <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
                <thead>
                    <tr>
                        <th width="40" class="center">Sr.</th>
                        <th>Title</th>
                        <th width="70" class="center">Subject</th>
                        <th width="100" class="center">Start Date</th>
                        <th width="100" class="center">End Date</th>
                        <th width="70" class="center">Status</th>
                        <th width="100" class="center">Options</th>
                    </tr>
                </thead>
                <tbody>';
                    $sratt = 0;
                    while($value_assign = mysqli_fetch_assoc($sqllmsassignment)) { 
                        $sratt ++;
                        echo '
                        <tr>
                            <td class="center">'.$sratt.'</td>
                            <td>'.$value_assign['assig_title'].'</td>
                            <td class="center">'.$value_assign['subject_name'].'</td>
                            <td class="center">'.$value_assign['open_date'].'</td>
                            <td class="center">'.$value_assign['close_date'].'</td>
                            <td class="center">'.get_status($value_assign['assig_status']).'</td>
                            <td class="center">';
                            if(!empty($value_assign['assig_file'])){
                                echo'<a href="uploads/resources/'.$value_assign['assig_file'].'" download="'.$value_assign['assig_file'].'" class="btn btn-success btn-xs m-xs");"><i class="glyphicon glyphicon-download"></i> </a>';
                            }
                            if(!empty($value_assign['assig_note'])){
                                echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/assignments/assignment_detail.php?note='.$value_assign['assig_note'].'\');"><i class="fa fa-info-circle"></i></a>';
                            }
                            echo'
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