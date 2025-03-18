<?php
    $sqllmscls	= $dblms->querylms("SELECT c.class_name, s.section_name, ses.session_startdate
                                    FROM ".CLASSES." c
                                    INNER JOIN ".CLASS_SECTIONS." s ON s.id_class  = c.class_id AND s.section_id = '".cleanvars($_POST['id_section'])."'
                                    INNER JOIN ".SESSIONS." ses ON ses.session_id   =   '".cleanvars($_POST['id_session'])."'
                                    WHERE class_id = '".cleanvars($_POST['id_class'])."'
                                  ");
    $resultclass = mysqli_fetch_array($sqllmscls);

    if(isset($_POST['id_month']) && !empty($_POST['id_month'])){
        $id_month_sql = "AND f.id_month >= '".cleanvars($_POST['id_month'])."'";
    }else{
        $id_month_sql = '';
    }

    // SELECT QUERY
    if(isset($_POST['id_class']) && !empty($_POST['id_class']) && isset($_POST['id_section'])){

        $sqllms  = $dblms->querylms("SELECT *
                                        FROM ".STUDENTS." st 
                                        WHERE st.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                        AND st.id_class = '".cleanvars($_POST['id_class'])."'
                                        AND st.id_section = '".cleanvars($_POST['id_section'])."' 
                                        AND st.id_session = '".cleanvars($_POST['id_session'])."' 
                                        AND st.is_deleted != '1' 
                                        "); 
        $srno = 0;                                   
    }
    echo'
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-md-2 col-sm-2 text-right">
                <img src="uploads/logo.png" height="150"/>
            </div>
            <div class="col-md-10 col-sm-10">
                <br>
                <h1 class="text-center">Laurel Home International Schools ('.$value_campus['campus_name'].')</h1>
                <br>
                <h2 class="text-center">Class-wise fee Receipt report</h2>
                </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <b> Class: </b>'.$resultclass['class_name'].'
            </div>
            <div class="col-md-6 col-sm-6 text-right">
                <b> Print Date: </b>'.date("D, j M Y").'
            </div>
            <div class="col-md-12 col-sm-12"><b> Section: </b>'.$resultclass['section_name'].'</div>';
            if(isset($_POST['id_month']) && !empty($_POST['id_month'])){
                echo'<div class="col-md-12 col-sm-12"><b>Start Month: </b>'.get_monthtypes($_POST['id_month']).'</div>';
            }
            echo '
        </div>';
        if(mysqli_num_rows($sqllms) > 0){
            echo'
            <br>
            <table class="table table-bordered table-striped table-condensed mb-none">
                <thead>
                    <tr>
                        <th class="text-center">Sr#</th>
                        <th class="text-center" width="200">Reg.no</th>
                        <th class="text-center">Student Name</th>
                        <th class="text-center">Father Name</th>';
                        foreach($monthtypes as $month) {
                            if($month['id'] >= $_POST['id_month']){
                                echo '<th class="text-center">'.$month['name'].'-'.date("y", strtotime($resultclass['session_startdate'])).'<br>[Arear], [Mon fee], [Rec], [Bal]</th>';
                            }
                        }
                        echo'
                    </tr>
                </thead>
                <tbody>';
                    while($rowresult = mysqli_fetch_array($sqllms)){
                        $challans   =  explode(",",$rowresult['challans']);
                        $srno ++;
                        echo'
                        <tr>
                            <td class="text-center">'.$srno.'</td>
                            <td>'.$rowresult['std_regno'].'</td>
                            <td>'.$rowresult['std_name'].'</td>
                            <td>'.$rowresult['std_fathername'].'</td>
                            ';
                                foreach($monthtypes as $month) {
                                    if($month['id'] >= $_POST['id_month'])
                                    {
                                        $sqllmsFee  = $dblms->querylms("SELECT *   
                                                                            FROM ".FEES." f
                                                                            WHERE f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                            AND f.id_class = '".cleanvars($_POST['id_class'])."' 
                                                                            AND f.id_std = '".cleanvars($rowresult['std_id'])."' 
                                                                            AND f.status = '1' 
                                                                            and f.id_month = '".$month['id']."'
                                                                            AND f.id_session = '".cleanvars($_POST['id_session'])."'
                                                                            $id_month_sql
                                                                    "); 
                                        if(mysqli_num_rows($sqllmsFee) > 0){
                                            $rowFee =   mysqli_fetch_array($sqllmsFee);

                                            echo '<td class="text-center">['.$rowFee['remaining_amount'].'], ['. $rowFee['total_amount'].'], ['.$rowFee['paid_amount'].'], ['.$rowFee['remaining_amount'].']</td>';
                                        }else{
                                            echo '<td class="text-center">[0], [0], [0], [0] </td>';
                                        }
                                    }
                                }
                                echo'
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>';
        }else{
            echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
        }
        echo'
    </div>
';