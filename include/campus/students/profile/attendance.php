<?php 	
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT  s.std_id, s.std_status, s.std_name, s.id_class,
                                    s.id_section, s.id_session, s.std_rollno, s.std_regno, s.std_photo, s.id_campus 
                                    FROM ".STUDENTS." s
                                    WHERE s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  AND s.std_status  = '1'
                                    AND s.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
                                    AND s.std_id = '".$_GET['id']."'
                                ");
//-----------------------------------------------------
$sqlatten	= $dblms->querylms("SELECT a.id, a.dated, a.id_class, a.id_section, a.id_session, a.id_campus,
                                                    d.id, d.id_setup, d.id_std, d.status 
                                                    FROM ".STUDENT_ATTENDANCE." a
                                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
                                                    WHERE a.id_campus 	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                    AND   a.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                                    AND   d.id_std 	= '".$_GET['id']."'
                                                ");
//-----------------------------------------------------
echo'
<div id="attendance" class="tab-pane">

<div id="" class="" style=" overflow: auto;">
    <div class="panel-body">
        <div class="table-responsive">';
        if(mysqli_num_rows($sqlatten)>0){
            echo'
            <table class="table table-bordered table-striped table-condensed mb-none ">
                <thead>
                    <tr>
                        <th style="width:70px; text-align: center;">#</th>
                        <th>Date</th>
                        <th style="width:70px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>';
                    //-----------------------------------------------------
                    $srno = 0;
                    while($rowsvalues = mysqli_fetch_array($sqlatten)) {
                        //-----------------------------------------------------
                        $srno++;
                        echo'
                        <tr>
                            <td style="width:70px; text-align: center;">'.$srno.'</td>
                            <td>'.date('l d M, Y', strtotime($rowsvalues['dated'])).'</td>
                            <td style="width:70px; text-align: center;">'.get_attendtype($rowsvalues['status']).'</td>
                        </tr>
                        ';
                    }
                    echo'				
                </tbody>
            </table>';
        }
        else{
            echo'<h3 class="text-danger center">No Record Found!</h3>';
        }
        echo'
        </div>
    </div>
</div>
</div>';

?>