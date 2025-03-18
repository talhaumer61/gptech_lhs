<?php
if(!isset($_POST['view_student']) && !isset($_GET['edit_id'])){
    $sqllmsattendance	= $dblms->querylms("SELECT sa.id, sa.dated, et.type_name
                                            FROM ".STUDENT_ATTENDANCE." sa
                                            INNER JOIN ".EXAM_TYPES." et ON et.type_id = sa.id_examtype
                                            WHERE status = '1' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                            AND id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
                                            AND id_teacher = '".$value_emp['emply_id']."' 
                                            AND id_section = '".$_GET['section']."' AND id_class = '".$_GET['class']."' AND id_subject = '".$_GET['id']."'
                                            ORDER BY sa.dated DESC
                                            ");
    if (mysqli_num_rows($sqllmsattendance) > 0) {
        echo'
        <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
            <thead>
                <tr>
                    <th class="center" width="40">Sr.</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th class="center">Total Students</th>
                    <th class="center">Present</th>
                    <th class="center">Absent</th>
                    <th class="center">Leave</th>
                    <th width="70" class="center">Options</th>
                </tr>
            </thead>
            <tbody>';
                $sratt = 0;
                while($value_att = mysqli_fetch_assoc($sqllmsattendance)) { 
                    $sratt ++;

                    $sqllmsprsent  = $dblms->querylms("SELECT COUNT(dt.id) AS totalpresent     
                                                            FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                            INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std  
                                                            WHERE dt.status = '1' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                            AND std.std_status = '1'");
                    $valuepresent = mysqli_fetch_array($sqllmsprsent);

                    $sqllmsabsent  = $dblms->querylms("SELECT COUNT(dt.id) AS totalabsent     
                                                            FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                            INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std  
                                                            WHERE dt.status = '2' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                            AND std.std_status = '1'");
                    $valueabsent = mysqli_fetch_array($sqllmsabsent);                    

                    $sqlLeave  = $dblms->querylms("SELECT COUNT(dt.id) AS totalleave     
                                                            FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                            INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std  
                                                            WHERE dt.status = '3' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                            AND std.std_status = '1'");
                    $valueleave = mysqli_fetch_array($sqlLeave);

                    echo'
                    <tr>
                        <td class="center">'.$sratt.'</td>
                        <td>'.$value_att['dated'].'</td>
                        <td>'.$value_att['type_name'].'</td>
                        <td class="center">'.($valuepresent['totalpresent'] + $valueabsent['totalabsent'] + $valueleave['totalleave']).'</td>
                        <td class="center">'.$valuepresent['totalpresent'].'</td>
                        <td class="center">'.$valueabsent['totalabsent'].'</td>
                        <td class="center">'.$valueleave['totalleave'].'</td>';
                        if($value_att['dated'] == date('Y-m-d')){
                            echo'<td class="center"><a class="btn btn-success btn-xs" href="subject.php?id='.$_GET['id'].'&section='.$_GET['section'].'&class='.$_GET['class'].'&view=attendance&edit_id='.$value_att['id'].'"> <i class="fa fa-pencil"></i></a></td>';
                        }
                        echo'
                    </tr>';
                }
                echo'
            </tbody>
        </table>';
    }else{
        echo'<h4 class="center">No Record Found</h4>';
    }
}