<?php
    $sqlStd = $dblms->querylms("SELECT	s.std_id, s.id_class
                                    FROM ".STUDENTS." s
                                    WHERE s.id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                    AND s.id_loginid	= '".$_SESSION['userlogininfo']['LOGINIDA']."'
                                    AND s.is_deleted	= '0'
                                    ");
    $valStd = mysqli_fetch_array($sqlStd);

    $sqllmsResult = $dblms->querylms("SELECT	s.std_id, s.std_name, s.std_fathername, s.id_session as std_session, s.id_class as std_class,
                                                GROUP_CONCAT(DISTINCT t.type_name) as examtype,
                                                SUM(d.obtain_marks) as total_obtained,
                                                SUM(m.total_marks) as total_marks, m.id_class, m.id_section, m.id_session, m.id_campus, m.id_exam
                                        FROM ".EXAM_MARKS_DETAILS." d
                                        INNER JOIN ".STUDENTS."		s ON (
                                            s.std_id = d.id_std
                                            AND s.id_deleted	= '0'
                                        )
                                        INNER JOIN ".EXAM_MARKS."	m ON m.id		=	d.id_setup
                                        INNER JOIN ".EXAM_TYPES."	t ON t.type_id	=	m.id_exam
                                        WHERE m.id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                        AND m.id_session	= '".$_SESSION['userlogininfo']['EXAM_SESSION']."' 
                                        AND d.id_std	    = '".$valStd['std_id']."' 
                                        AND m.id_class		= '".$valStd['id_class']."'
                                        AND m.is_deleted	= '0'
                                        GROUP BY s.std_id, m.id_exam
                                        ");
    echo'
    <section class="panel panel-featured panel-featured-primary mt-sm">
        <header class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> Students Progress Report </h2>
        </header>';
    $srno = 0;
    if(mysqli_num_rows($sqllmsResult)>0){
        echo'
            <div class="panel-body">
                <div class="table-responsive mt-sm mb-md">
                    <table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
                        <thead>
                            <tr>
                                <th class="center" width:"40">Sr.</th>
                                <th>Term</th>
                                <th>Obtained / Total</th>
                                <th>Percentage</th>
                                <th width="40">Options</th>
                            </tr>
                        </thead>
                        <tbody>';
                            while($valueResult = mysqli_fetch_array($sqllmsResult)){
                                $srno++;
                                $total_marks = $valueResult['total_marks'];
                                $total_obtained = $valueResult['total_obtained'];
                                $percentage = round((($total_obtained / $total_marks) * 100), 2);
                                echo'
                                <tr>
                                    <td class="center">'.$srno.'</td>
                                    <td>'.$valueResult['examtype'].'</td>
                                    <td>'.$total_obtained.' / '.$total_marks.'</td>
                                    <td>'.$percentage.' %</td>
                                    <td class="text-center">
                                        <a href="single_marksheetprint.php?std_id='.$valStd['std_id'].'&id_type='.$valueResult['id_exam'].'&id_class='.$valStd['id_class'].'&id_session='.$_SESSION['userlogininfo']['EXAM_SESSION'].'" class="btn btn-primary btn-xs" target="_blank">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    </td>
                                </tr>';
                            }
                            echo'	
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="panel-footer">
                <div class="text-right">
                    <a href="" target="_blank" class="btn btn-sm btn-primary">
                        <i class="glyphicon glyphicon-print"></i> Print
                    </a>
                </div>
            </div> -->';
    }else{
        echo'<h2 class="panel-body text-center font-bold mt-none text text-danger">No Record Found</h2>';
    }
echo'
</section>';
?>