<?php
echo'
<section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
        <h2 class="panel-title"><i class="fa fa-list"></i>  Salary</h2>
    </header>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
            <thead>
                <tr>
                    <th width="40" class="center">Sr.</th>
                    <th>Month</th>
                    <th>Basic Salary</th>
                    <th>Allowance</th>
                    <th>Deductions</th>
                    <th>Net Salary</th>
                    <th>Dated</th>
                </tr>
            </thead>
            <tbody>';
                $sqllms	= $dblms->querylms("SELECT s.id, s.basic_salary, s.total_allowances, s.total_deductions, s.net_pay, s.dated, s.month,
                                            e.emply_name, e.emply_phone, e.emply_photo
                                            FROM ".SALARY." s      
                                            INNER JOIN ".EMPLOYEES." e ON e.emply_id = s.id_emply
                                            INNER JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
                                            WHERE s.id_campus   = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                            AND e.id_loginid    = '".$_SESSION['userlogininfo']['LOGINIDA']."'
                                            AND e.emply_status  = '1'
                                            ORDER BY id");
                $srno = 0;
                while($rowsvalues = mysqli_fetch_array($sqllms)){
                    $srno++;
                    echo'
                    <tr>
                        <td class="center">'.$srno.'</td>
                        <td>'.get_monthtypes($rowsvalues['month']).'</td>
                        <td>Rs. '.$rowsvalues['basic_salary'].' </td>
                        <td>Rs. '.$rowsvalues['total_allowances'].' </td>
                        <td>Rs. '.$rowsvalues['total_deductions'].' </td>
                        <td>Rs. '.$rowsvalues['net_pay'].' </td>
                        <td> '.date('d M, Y' , strtotime(cleanvars($rowsvalues['dated']))).' </td>
                    </tr>';
                }
                echo'
            </tbody>
        </table>
    </div>
</section>';
?>