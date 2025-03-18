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
        $date_sql = "AND f.issue_date >= '".cleanvars($start_date)."'";
    }
    else if(isset($_POST['end_date']) && !empty($_POST['end_date']) && empty($_POST['start_date'])){
        $end_date = date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])));
        $date_sql = "AND f.issue_date <= '".cleanvars($end_date)."'";
    }
    else if(isset($_POST['start_date']) && !empty($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['end_date'])){
        $start_date = date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])));
        $end_date = date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])));
        $date_sql = "AND (f.issue_date BETWEEN '".cleanvars($start_date)."' AND '".cleanvars($end_date)."')";
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
                                        AND st.is_deleted != '1' 
                                        AND f.id_section = '".cleanvars($_POST['id_section'])."' $id_month_sql $date_sql
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
                <h2 class="text-center">Class wise challans Details</h2>
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
                        <th class="text-center">Student</th>
                        <th class="text-center" width="200">Reg#</th>
                        <th class="text-center">Challans</th>
                        <th class="text-center">Arrears</th>';
                        $sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
                                                            FROM ".FEE_CATEGORY."
                                                            WHERE cat_status = '1'
                                                            AND  cat_id NOT IN (3, 4) 
                                                            ORDER BY cat_id ASC");

                        if(mysqli_num_rows($sqllmscats) > 0) {
                            while($rowdoc = mysqli_fetch_array($sqllmscats)):
                                echo '<th class="text-center">'.$rowdoc['cat_name'].'</th>';
                            endwhile;
                        }
                        echo'
                        <th class="text-center">Received</th>
                        <th class="text-center">Balance</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>';
                    $feeIds          =   array();
                    $totalRemaining  =   0;
                    $totalReceived   =   0;
                    $totalAmount     =   0;
                    while($rowresult = mysqli_fetch_array($sqllms)){
                        array_push($feeIds, $rowresult['id']);
                        $totalRemaining  =   $totalArrears + $rowresult['remaining_amount'];
                        $totalReceived   =   $totalReceived + $rowresult['paid_amount'];
                        $totalAmount     =   $totalAmount + $rowresult['total_amount'];
                        $srno ++;
                        echo'
                        <tr>
                            <td class="text-center">'.$srno.'</td>
                            <td>'.$rowresult['std_name'].'</td>
                            <td>'.$rowresult['std_regno'].'</td>
                            <td class="text-center">'.$rowresult['challan_no'].'</td>
                            <td class="text-center">'.$rowresult['remaining_amount'].'</td>';
                            $sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
                                                                FROM ".FEE_CATEGORY."
                                                                WHERE cat_status = '1'
                                                                AND  cat_id NOT IN (3, 4)
                                                                ORDER BY cat_id ASC");

                            $countcats 	= mysqli_num_rows($sqllmscats);
                            if($countcats >0) {
                                while($rowdoc 	= mysqli_fetch_array($sqllmscats)) {

                                    $sqllmsfeeprt  = $dblms->querylms("SELECT id_cat, amount 
                                                                        FROM ".FEE_PARTICULARS." 
                                                                        WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$rowresult['id']."' 
                                                                        LIMIT 1");

                                    if(mysqli_num_rows($sqllmsfeeprt)>0) {
                                        $valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
                                        $remarks = '';
                                    echo '
                                        <td class="text-center">'.number_format($valuefeeprt['amount']).'</td>'; 
                                    } else {  
                                    echo '
                                        <td class="text-center">0</td>'; 
                                    }
                                }
                            }

                            echo'
                            <td class="text-center">Rs. '.$rowresult['paid_amount'].'</td>
                            <td class="text-center">Rs. '.$rowresult['remaining_amount'].'</td>
                            <td class="text-center">Rs. '.$rowresult['total_amount'].'</td>
                        </tr>';
                    }
                    echo'
                    <tr>
                        <th class="text-center" colspan="3">Total</th>
                        <th class="text-center">'.mysqli_num_rows($sqllms).'</th>
                        <th class="text-center">'.number_format($totalRemaining).'</th>';
                        $idsFee      =   implode(", ",$feeIds);
                        $sqllmsCats  = $dblms->querylms("SELECT cat_id, cat_name  
                                                            FROM ".FEE_CATEGORY."
                                                            WHERE cat_status = '1'
                                                            AND  cat_id NOT IN (3, 4) 
                                                            ORDER BY cat_id ASC");

                        if(mysqli_num_rows($sqllmsCats) > 0) {
                            while($rowDoc = mysqli_fetch_array($sqllmsCats)):
                                $sqllmsFeePrt  = $dblms->querylms("SELECT SUM(amount) totalAmount 
                                                                    FROM ".FEE_PARTICULARS." 
                                                                    WHERE id_cat = '".$rowDoc['cat_id']."' AND id_fee  IN (".$idsFee.") 
                                                                ");
                                if(mysqli_num_rows($sqllmsFeePrt)>0) {
                                    $valuefeeprt = mysqli_fetch_array($sqllmsFeePrt);
                                    echo '
                                        <th class="text-center">'.number_format($valuefeeprt['totalAmount']).'</th>'; 
                                } 
                            endwhile;
                        }
                        echo '
                        <th class="text-center">'.number_format($totalReceived).'</th>
                        <th class="text-center">'.number_format($totalRemaining).'</th>
                        <th class="text-center">'.number_format($totalAmount).'</th>
                    </tr>
                </tbody>
            </table>';
        }else{
            echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
        }
        echo'
    </div>
';