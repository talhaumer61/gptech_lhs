<?php
    $sqllmscls	= $dblms->querylms("SELECT session_name
                                        FROM ".SESSIONS."
                                        WHERE session_id = '".cleanvars($_POST['id_session'])."'
                                        ");
    $resultclass = mysqli_fetch_array($sqllmscls);

    if(isset($_POST['start_month']) && !empty($_POST['start_month']) && empty($_POST['end_month'])){
        $month_sql = "AND f.id_month = '".cleanvars($_POST['start_month'])."'";
    }
    else if(isset($_POST['end_month']) && !empty($_POST['end_month']) && empty($_POST['start_month'])){
        $month_sql = "AND f.id_month = '".cleanvars($_POST['end_month'])."'";
    }
    else if(isset($_POST['start_month']) && !empty($_POST['start_month']) && isset($_POST['end_month']) && !empty($_POST['end_month'])){
        $month_sql = "AND (f.id_month BETWEEN '".cleanvars($_POST['start_month'])."' AND '".cleanvars($_POST['end_month'])."')";
    }
    else{
        $month_sql = '';
    }
    // SELECT QUERY
    if(isset($_POST['id_session']) && !empty($_POST['id_session'])){

        $sqllms  = $dblms->querylms("SELECT * , SUM(f.paid_amount) AS sum_paid_amount, SUM(f.remaining_amount) AS sum_remaining_amount, SUM(f.total_amount) AS sum_total_amount, COUNT(challan_no) AS count_challan, GROUP_CONCAT(f.id SEPARATOR ',') as ids
                                        FROM ".FEES." f									
                                        INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
                                       
                                        INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
                                        INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std	
										 INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = st.id_section
                                        WHERE f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                        AND f.is_deleted != '1'
                                        AND st.is_deleted != '1'
                                        AND f.id_session = '".cleanvars($_POST['id_session'])."' $month_sql
                                        GROUP BY f.id_class
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
                <h2 class="text-center">Month Wise Challans Summary</h2>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <b> Session: </b>'.$resultclass['session_name'].'
            </div>
            <div class="col-md-6 col-sm-6 text-right">
                <b> Print Date: </b>'.date("D, j M Y").'
            </div>';
            if(isset($_POST['start_month']) && !empty($_POST['start_month']) && isset($_POST['end_month']) && !empty($_POST['end_month'])){
                echo '<div class="col-md-12 col-sm-12"><b> From Month: </b>'.get_monthtypes($_POST['start_month']).'</div>';
                echo '<div class="col-md-12 col-sm-12"><b> To Month: </b>'.get_monthtypes($_POST['end_month']).'</div>';
            }elseif(isset($_POST['start_month']) && !empty($_POST['start_month'])){
                echo '<div class="col-md-12 col-sm-12"><b> Month: </b>'.get_monthtypes($_POST['start_month']).'</div>';
            }elseif(isset($_POST['end_month']) && !empty($_POST['end_month'])){
                echo '<div class="col-md-12 col-sm-12"><b> Month: </b>'.get_monthtypes($_POST['end_month']).'</div>';
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
                        <th class="text-center">Class</th>
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
                    $totalChallans   =   0;
                    $totalRemaining  =   0;
                    $totalReceived   =   0;
                    $totalAmount     =   0;
                    while($rowresult = mysqli_fetch_array($sqllms)){
                        array_push($feeIds, $rowresult['ids']);
                        $totalChallans   =   $totalChallans + $rowresult['count_challan'];
                        $totalRemaining  =   $totalArrears + $rowresult['sum_remaining_amount'];
                        $totalReceived   =   $totalReceived + $rowresult['sum_paid_amount'];
                        $totalAmount     =   $totalAmount + $rowresult['sum_total_amount'];
                        $srno ++;
                        echo'
                        <tr>
                            <td class="text-center">'.$srno.'</td>
                            <td class="text-center">'.$rowresult['class_name'].'</td>
                            <td class="text-center">'.$rowresult['count_challan'].'</td>
                            <td class="text-center">'.$rowresult['sum_remaining_amount'].'</td>';

                            $sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
                                                            FROM ".FEE_CATEGORY."
                                                            WHERE cat_status = '1' 
                                                            AND  cat_id NOT IN (3, 4)
                                                            ORDER BY cat_id ASC");
                        
                            $countcats 	= mysqli_num_rows($sqllmscats);
                            if($countcats >0) {
                                while($rowdoc 	= mysqli_fetch_array($sqllmscats)) {
                                    
                                    $sqllmsfeeprt  = $dblms->querylms("SELECT id_cat, SUM(amount) AS amount
                                                                        FROM ".FEE_PARTICULARS." 
                                                                        WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  IN (".$rowresult['ids'].") 
                                                                        LIMIT 1");
                                    if(mysqli_num_rows($sqllmsfeeprt)>0) {
                                        $valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
                                    echo '
                                        <td class="text-center">'.number_format($valuefeeprt['amount']).'</td>';
                                    } else { 
                                    echo '
                                        <td></td>';
                                    }
                                }
                            }

                            echo'
                            <td class="text-center">Rs. '.$rowresult['sum_paid_amount'].'</td>
                            <td class="text-center">Rs. '.$rowresult['sum_remaining_amount'].'</td>
                            <td class="text-center">Rs. '.$rowresult['sum_total_amount'].'</td>
                        </tr>
                        ';
                    }
                    echo'
                    <tr>
                        <th class="text-center" colspan="2">Total</th>
                        <th class="text-center">'.number_format($totalChallans).'</th>
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
                                        $valueFeePrt = mysqli_fetch_array($sqllmsFeePrt);
                                        echo '
                                            <th class="text-center">'.number_format($valueFeePrt['totalAmount']).'</th>'; 
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
    </div>';