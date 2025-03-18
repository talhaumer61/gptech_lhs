<?php
    $sqllmscls	= $dblms->querylms("SELECT c.class_name, s.section_name
                                    FROM ".CLASSES." c
                                    INNER JOIN ".CLASS_SECTIONS." s
                                    ON s.id_class  = c.class_id AND s.section_id = '".cleanvars($_POST['id_section'])."'
                                    WHERE class_id = '".cleanvars($_POST['id_class'])."'
                                    ");
    $resultclass = mysqli_fetch_array($sqllmscls);

    if(isset($_POST['id_month']) && !empty($_POST['id_month'])){
        $id_month_sql = "AND f.id_month = '".cleanvars($_POST['id_month'])."'";
    }else{
        $id_month_sql = '';
    }

    if(isset($_POST['start_date']) && !empty($_POST['start_date']) && empty($_POST['end_date'])){
        $start_date = date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])));
        $date_sql = "AND f.paid_date >= '".cleanvars($start_date)."'";
    }
    else if(isset($_POST['end_date']) && !empty($_POST['end_date']) && empty($_POST['start_date'])){
        $end_date = date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])));
        $date_sql = "AND f.paid_date <= '".cleanvars($end_date)."'";
    }
    else if(isset($_POST['start_date']) && !empty($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['end_date'])){
        $start_date = date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])));
        $end_date = date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])));
        $date_sql = "AND (f.paid_date BETWEEN '".cleanvars($start_date)."' AND '".cleanvars($end_date)."')";
    }
    else{
        $start_date ='';
        $end_date = '';
        $date_sql = '';
    }

    // SELECT QUERY
    if(isset($_POST['id_class']) && !empty($_POST['id_class']) && isset($_POST['id_section'])){

        $sqllms  = $dblms->querylms("SELECT *   
                                        FROM ".FEES." f									
                                        INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
                                       
                                        INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
                                        INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std	
										 INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = st.id_section
                                        WHERE f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                        AND f.id_class = '".cleanvars($_POST['id_class'])."' 
                                        AND f.id_section = '".cleanvars($_POST['id_section'])."' 
                                        AND f.id_std = '".cleanvars($_POST['id_std'])."' 
                                        AND f.status = '1' 
                                        AND st.is_deleted != '1'
                                        $id_month_sql $date_sql
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
                <h2 class="text-center">Individual Student Ledger</h2>
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
                echo'<div class="col-md-12 col-sm-12"><b> Month: </b>'.get_monthtypes($_POST['id_month']).'</div>';
            }
            if(isset($_POST['start_date']) && !empty($_POST['start_date'])){
                echo'<div class="col-md-12 col-sm-12"><b> Start Date: </b>'.$_POST['start_date'].'</div>';
            }
            if(isset($_POST['end_date']) && !empty($_POST['end_date'])){
                echo'<div class="col-md-12 col-sm-12"><b> End Date: </b>'.$_POST['end_date'].'</div>';
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
                        <th class="text-center">Day/Date</th>
                        <th class="text-center">Details</th>
                        <th class="text-center">Debit</th>
                        <th class="text-center">Credit</th>
                        <th class="text-center">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td>Opening Balance</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>';
                    $balanace =   0;
                    $credit   =   0;
                    while($rowresult = mysqli_fetch_array($sqllms)){
                        array_push($feeIds, $rowresult['id']);
                        $balanace   =   $balanace + $rowresult['remaining_amount'];
                        $credit     =   $credit + $rowresult['paid_amount'];
                        $srno ++;
                        echo'
                        <tr>
                            <td class="text-center">'.$srno.'</td>
                            <td class="text-center">'.date("D, j M Y ",  strtotime($rowresult['paid_date'])).'</td>
                            <td>Recieved Fee Against Challan#['.$rowresult['challan_no'].'] for the month of '.get_monthtypes($rowresult['id_month']).'-'.date("Y",  strtotime($rowresult['issue_date'])).'</td>
                            <td class="text-center"></td>
                            <td class="text-center">'.number_format($rowresult['paid_amount']).' PKR</td>
                            <td class="text-center">'.number_format($rowresult['remaining_amount']).'</td>
                        </tr>';
                    }
                    echo'
                    <tr>
                        <th colspan="2"></th>
                        <th>Closing Balance</th>
                        <th class="text-center"></th>
                        <th class="text-center">'.number_format($credit).'</th>
                        <th class="text-center">'.number_format($balanace).'</th>
                    </tr>
                </tbody>
            </table>';
        }else{
            echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
        }
        echo'
    </div>
';