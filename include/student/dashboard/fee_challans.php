<?php
echo'
<section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
        <h2 class="panel-title"><i class="fa fa-list"></i>  Challans Payment List / History</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
            <thead>
                <tr>
                    <th class="center" width="40">Sr.</th>
                    <th>Challan No</th>
                    <th>Student</th>
                    <th>Issue On</th>
                    <th>Due On</th>
                    <th>Total</th>
                    <th>Payable</th>
                    <th width="70" class="center">Status</th>
                    <th width="50" class="center">Print</th>
                </tr>
            </thead>
            <tbody>';
                $sqllms	= $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.issue_date, f.due_date, f.total_amount, st.std_name
                                            FROM ".FEES." f				 
                                            INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
                                            INNER JOIN ".ADMINS." a ON a.adm_id = st.id_loginid	
                                            WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                            AND a.adm_id = '".$_SESSION['userlogininfo']['LOGINIDA']."'
                                            ORDER BY f.id DESC");
                $srno = 0;
                while($rowsvalues = mysqli_fetch_array($sqllms)) {
                    // Scholarship
                    $sql_scholarship	= $dblms->querylms("SELECT SUM(percent) as scholarship
                                                    FROM ".SCHOLARSHIP." 
                                                    WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                                    AND   id_type = '1' AND status = '1' AND id_std = '".$rowsvalues['std_id']."' ");
                    $values_scholarship = mysqli_fetch_array($sql_scholarship);

                    // Fee Concession
                    $sql_concess	= $dblms->querylms("SELECT SUM(percent) as concession
                                                    FROM ".SCHOLARSHIP." 
                                                    WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                                    AND   id_type = '2' AND status = '1' AND id_std = '".$rowsvalues['std_id']."' ");
                    $values_concess = mysqli_fetch_array($sql_concess);

                    // Fine
                    $sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
                                                    FROM ".SCHOLARSHIP." 
                                                    WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                                    AND   id_type = '3' AND status = '1' AND id_std = '".$rowsvalues['std_id']."' ");
                    $values_fine = mysqli_fetch_array($sql_fine);

                    // payabel amount after Scholarship & Fine
                    $amount = $rowsvalues['total_amount'];
                    $dis_per = $values_scholarship['scholarship'] + $values_concess['concession'];
                    $dis = ($amount * $dis_per) / 100;
                    $total_amount = $amount - $dis;
                    $payable = $total_amount + $values_fine['fine'];

                    $srno++;
                    echo'
                    <tr>
                        <td class="center">'.$srno.'</td>
                        <td>'.$rowsvalues['challan_no'].'</td>
                        <td>'.$rowsvalues['std_name'].'</td>
                        <td>'.$rowsvalues['issue_date'].'</td>
                        <td>'.$rowsvalues['due_date'].'</td>
                        <td>Rs. '.number_format(round($total_amount)).'</td>
                        <td>'.number_format(round($payable)).'</td>
                        <td class="center">'.get_payments($rowsvalues['status']).'</td>
                        <td class="center">
                            <a class="btn btn-success btn-xs mr-xs" class="center" href="feechallanprint.php?id='.$rowsvalues['challan_no'].'"> <i class="fa fa-file"></i></a>
                        </td>
                    </tr>';
                }
                echo'
            </tbody>
        </table>
    </div>
</section>';
?>