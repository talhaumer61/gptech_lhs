<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

echo'
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Fee Defaulter Report Print</title>
        <style type="text/css">
            body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .page-break	{ display: block; page-break-before: always; }
                @page {
                    margin: 4mm 4mm 4mm 4mm; 
                }
            }
            h1 { text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:26px; font-weight:700; text-transform:uppercase; }
            .spanh1 { font-size:14px; font-weight:normal; text-transform:none; text-align:right; float:right; margin-top:10px; }
            h2 { text-align:left; margin:0; margin-top:0; margin-bottom:1px; font-size:24px; font-weight:700; text-transform:uppercase; }
            .spanh2 { font-size:20px; font-weight:700; text-transform:none; }
            h3 { text-align:center; margin:0; margin-top:0; margin-bottom:1px; font-size:18px; font-weight:700; text-transform:uppercase; }
            h4 { 
                text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em;  
            }
            td { padding-bottom:4px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
            .line1 { border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; }
            .payable { border:2px solid #000; padding:2px; text-align:center; font-size:14px; }

            .paid:after
            {
                content:"PAID";
                
                position:absolute;
                top:30%;
                left:20%;
                z-index:1;
                font-family:Arial,sans-serif;
                -webkit-transform: rotate(-5deg); /* Safari */
                -moz-transform: rotate(-5deg); /* Firefox */
                -ms-transform: rotate(-5deg); /* IE */
                -o-transform: rotate(-5deg); /* Opera */
                transform: rotate(-5deg);
                font-size:250px;
                color:green;
                background:#fff;
                border:solid 4px yellow;
                padding:5px;
                border-radius:5px;
                zoom:1;
                filter:alpha(opacity=50);
                opacity:0.1;
                -webkit-text-shadow: 0 0 2px #c00;
                text-shadow: 0 0 2px #c00;
                box-shadow: 0 0 2px #c00;
            }
        </style>
        <link rel="shortcut icon" href="images/favicon/favicon.ico">
    </head>
    <body>
        <table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
            <tr>
                <td width="341" valign="top">
                    <h2 style="text-align: center;">
                        <img src="uploads/logo.png" class="img-fluid" style="width: 50px; height: 50px;"> 
                        <span style="">'.SCHOOL_NAME.'</span>
                    </h2>';
                    $year   = date('Y');
                    $month  = date('m');
                    $today  = date('Y-m-d');
                    $sql    = "";
                    $sql1   = "";
                    $sql2   = "";

                    if ($_GET['type'] == '1') {
                        $sql = "AND f.status IN (2,4)";
                        $sql1 = "AND f.id_month = '".cleanvars($month)."' AND f.yearmonth 	= '".$year."-".$month."'";
                        $reportName = "Fee Defaulter";
                    } elseif($_GET['type'] == '2') {
                        $sql = "AND f.status IN (1,4)";
                        $sql1 = "AND f.id_month = '".cleanvars($month)."' AND f.yearmonth 	= '".$year."-".$month."'";
                        $reportName = "Fee Paid";
                    } elseif($_GET['type'] == '3') {
                        $sql1 = "AND f.id_month = '".cleanvars($month)."' AND f.yearmonth 	= '".$year."-".$month."'";
                        $reportName = "Total Fee Challans";
                    } elseif($_GET['type'] == '4') {
                        $sql = "AND f.status IN (1,4)";
                        $sql2 = "AND f.paid_date = '".cleanvars($today)."'";
                        $reportName = "Today Collection";
                    } elseif($_GET['salary'] == 1) {
                        $reportName = "Salary Report";
                    } elseif($_GET['events'] == 1) {
                        $reportName = "Upcoming Events Report";
                    }

                    // FEE REPORT
                    if($_GET['type']){
                        //Class Wise Defaulter
                        $sqllmsClass = $dblms->querylms("SELECT class_id, class_name
                                                                FROM ".CLASSES."			   
                                                                WHERE class_status  = '1'
                                                                AND is_deleted      = '0'
                                                                ORDER BY class_id ASC");
                        if(mysqli_num_rows($sqllmsClass)>0){
                            while($rowClass = mysqli_fetch_array($sqllmsClass)){
                                $sqllmsFeeDefaulter	= $dblms->querylms("SELECT f.id, f.status, GROUP_CONCAT(f.challan_no) as challans, GROUP_CONCAT('01','-',f.id_month,'-',YEAR(f.due_date)) as challan_month, f.id_class, f.challan_no, st.std_name, st.std_fathername, c.class_name,
                                                                            SUM(CASE WHEN f.due_date > '".date('Y-m-d')."' THEN f.total_amount
                                                                                ELSE f.total_amount + '".LATEFEE."'
                                                                                END) as total,
                                                                            SUM(f.paid_amount) paid
                                                                            FROM ".FEES." f				   
                                                                            INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
                                                                            INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	
                                                                            WHERE f.id_type IN (1,2)
                                                                            $sql $sql1 $sql2
                                                                            AND f.id_class      = ".$rowClass['class_id']."
                                                                            AND f.is_deleted    = '0' 
                                                                            AND st.is_deleted   = '0'
                                                                            AND f.id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
                                                                            AND st.std_status  != '3'
                                                                            GROUP BY st.std_id
                                                                            ORDER BY st.std_id, f.id DESC");
                                if(mysqli_num_rows($sqllmsFeeDefaulter) > 0){
                                    echo'
                                    <div style="font-size:12px; margin-top:10px;">
                                        <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                            <thead>
                                                <tr>
                                                    <td colspan="9"><h4 style="margin-top: 10px; color: red;">'.$reportName.' Report of Class '.$rowClass['class_name'].' '.(!empty($std_gender) ? '- '.$std_gender.'' : '').' '.(!empty($is_hostelized) ? '- '.$is_hostelized.'' : '').'</h4></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center; font-size:12px; font-weight:bold;">Sr #</td>
                                                    <td style="text-align:center; font-size:12px; font-weight:bold;">Challan #</td>
                                                    <td style="text-align:center; font-size:12px; font-weight:bold;">Student</td>
                                                    <td style="text-align:center; font-size:12px; font-weight:bold;">Narration</td>
                                                    <td style="text-align:center; font-size:12px; font-weight:bold;">Status</td>';
                                                    if($_GET['type']=='1'){
                                                        echo'<td style="text-align:center; font-size:12px; font-weight:bold;">Remaining</td>';
                                                    }elseif($_GET['type']=='2'){
                                                        echo'<td style="text-align:center; font-size:12px; font-weight:bold;">Paid</td>';
                                                    }elseif($_GET['type'] == '3'){
                                                        echo'<td style="text-align:center; font-size:12px; font-weight:bold;">Total Amount</td>';
                                                    }elseif($_GET['type'] == '4'){
                                                        echo'<td style="text-align:center; font-size:12px; font-weight:bold;">Total Amount</td>';
                                                    }
                                                    echo'
                                                </tr>
                                            </thead>
                                            <tbody>';
                                                $sr = 0; 
                                                $grandTotal = 0; 
                                                $grandPaid = 0;
                                                $grandPending = 0;

                                                while($rowStudent = mysqli_fetch_array($sqllmsFeeDefaulter)){
                                                    $narration = explode(",",$rowStudent['narration']);
                                                    $challan_month = explode(",",$rowStudent['challan_month']);
                                                    $totalAmount = $rowStudent['total'];

                                                    $paidAmount = $rowStudent['paid'];
                                                    $remainingAmount = $totalAmount - $paidAmount;
                                                    $sr++;
                                                    echo '
                                                    <tr>
                                                        <td style="text-align:center; padding-left: 5px;width: 50px;">'.$sr.'</td>
                                                        <td style="width:100px; text-align:center;">'.$rowStudent['challans'].'</td> 
                                                        <td>'.$rowStudent['std_name'].' / '.$rowStudent['std_fathername'].'</td> 
                                                        <td style="text-align:center;">';

                                                        $arrayNar = array();
                                                        foreach ($challan_month as $nar):
                                                            $narDate = date('M-Y' , strtotime($nar));
                                                            array_push($arrayNar, $narDate);
                                                            // echo date('M-y' , strtotime($nar)).' ,';
                                                        endforeach;
                                                        $narComma 	= 	implode(", ",$arrayNar);

                                                        echo' '.$narComma.'
                                                        </td>
                                                        <td style="text-align:center; width:80px;">'.get_payments($rowStudent['status']).'</td>';
                                                        if($_GET['type']=='1'){
                                                            echo'<td style="text-align:right; width:80px;">'.number_format(round($remainingAmount)).'</td>';
                                                        }elseif($_GET['type']=='2'){
                                                            echo'<td style="text-align:right; width:80px;">'.number_format(round($paidAmount)).'</td>';
                                                        }elseif($_GET['type'] == '3'){
                                                            echo'<td style="text-align:right; width:80px;">'.number_format(round($totalAmount)).'</td>';
                                                        }elseif($_GET['type'] == '4'){
                                                            echo'<td style="text-align:right; width:80px;">'.number_format(round($paidAmount)).'</td>';
                                                        }
                                                        echo'
                                                    </tr>';
                                                    if($_GET['type']=='1'){
                                                        $grandTotal = $grandTotal + $remainingAmount;
                                                    }elseif($_GET['type']=='2'){
                                                        $grandTotal = $grandTotal + $paidAmount;
                                                    }elseif($_GET['type'] == '3'){
                                                        $grandTotal = $grandTotal + $totalAmount;
                                                    }elseif($_GET['type'] == '4'){
                                                        $grandTotal = $grandTotal + $paidAmount;
                                                    }
                                                }
                                                echo'
                                                <tr>
                                                    <td colspan="5" style="text-align:center; font-size:12px; font-weight:bold; border:1px solid #333;">Grand Total</td>
                                                    <td style="text-align:right; font-size:12px; font-weight:bold;  border:1px solid #333;">'.number_format($grandTotal).'</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--<div class="page-break"></div>-->';
                                }
                            }
                        }
                    }
                    // SALARY REPORT
                    elseif($_GET['salary']){
                        $sqlPaidSal	= $dblms->querylms("SELECT s.*, e.emply_name, e.emply_photo, e.emply_phone
                                                            FROM ".SALARY." s
                                                            INNER JOIN ".EMPLOYEES." e ON e.emply_id = s.id_emply
                                                            WHERE s.status	= '1'
                                                            AND s.month		= '".cleanvars($month)."'
                                                            AND s.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                                        ");
                        if(mysqli_num_rows($sqlPaidSal) > 0){
                            echo'
                            <div style="font-size:12px; margin-top:10px;">
                                <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <td colspan="9"><h4 style="margin-top: 10px; color: red;">'.$reportName.' for month of '.date('F', strtotime($today)).' </h4></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Sr.</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;" width="50">Photo</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Name</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Phone</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Dated</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Basic Salary</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Total Allowance</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Total Deduction</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Net Salary</td>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        $sr = 0; 
                                        $grandBasicSalary = 0;
                                        $grandAllowances = 0;
                                        $grandDeductions = 0;
                                        $grandTotal = 0;

                                        while($valPaidSal = mysqli_fetch_array($sqlPaidSal)){
                                            $sr++;
                                            if($valPaidSal['emply_photo']) { 
                                                $photo = '<img src="uploads/images/employees/'.$valPaidSal['emply_photo'].'" width="45" height="45">';
                                            }else{
                                                $photo = '<img src="uploads/defualt.png" width="45" height="45">';
                                            }
                                            echo'
                                            <tr>
                                                <td style="text-align:center; padding-left: 5px;width: 50px;">'.$sr.'</td>
                                                <td style="text-align:center;">'.$photo.'</td>
                                                <td>'.$valPaidSal['emply_name'].'</td>
                                                <td>'.$valPaidSal['emply_phone'].'</td>
                                                <td style="text-align: center;">'.$valPaidSal['dated'].'</td>
                                                <td style="text-align: center;">'.$valPaidSal['basic_salary'].'</td>
                                                <td style="text-align: center;">'.$valPaidSal['total_allowances'].'</td>
                                                <td style="text-align: center;">'.$valPaidSal['total_deductions'].'</td>
                                                <td style="text-align: center;">'.$valPaidSal['net_pay'].'</td>
                                            </tr>';
                                            $grandBasicSalary = $grandBasicSalary + $valPaidSal['basic_salary'];
                                            $grandAllowances = $grandAllowances + $valPaidSal['total_allowances'];
                                            $grandDeductions = $grandDeductions + $valPaidSal['total_deductions'];
                                            $grandTotal = $grandTotal + $valPaidSal['net_pay'];
                                        }
                                        echo'
                                        <tr>
                                            <td colspan="5" style="text-align:center; font-size:12px; font-weight:bold; border:1px solid #333;">Grand Total</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;  border:1px solid #333;">'.number_format($grandBasicSalary).'</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;  border:1px solid #333;">'.number_format($grandAllowances).'</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;  border:1px solid #333;">'.number_format($grandDeductions).'</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;  border:1px solid #333;">'.number_format($grandTotal).'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--<div class="page-break"></div>-->';
                        } 
                    }
                    // EVENTS REPORT
                    elseif($_GET['events']){
                        $sqlNot = $dblms->querylms("SELECT not_id, not_status, id_type, not_title, dated, not_description
                                                        FROM ".NOTIFICATIONS." 
                                                        WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                        AND dated > '".date('Y-m-d')."'
                                                        AND not_status = '1'
                                                        AND is_deleted = '0'
                                                        ORDER BY dated ASC
                                                    ");
                        if(mysqli_num_rows($sqlNot) > 0){
                            echo'
                            <div style="font-size:12px; margin-top:10px;">
                                <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <td colspan="9"><h4 style="margin-top: 10px; color: red;">'.$reportName.'</h4></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center; font-size:12px; font-weight:bold;">Sr.</th>
                                            <th style="text-align:left; font-size:12px; font-weight:bold;">Title</th>
                                            <th style="text-align:left; font-size:12px; font-weight:bold;">Detail</th>
                                            <th style="text-align:center; font-size:12px; font-weight:bold;">Date</th>
                                            <th style="text-align:center; font-size:12px; font-weight:bold;">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        $sr = 0;

                                        while($rowsvalues = mysqli_fetch_array($sqlNot)){
                                            $sr++;
                                            if($rowsvalues['id_type'] == 1){
                                                $type = "Popup";
                                            }elseif($rowsvalues['id_type'] == 2){
                                                $type = "Navbar";
                                            }
                                            else{ }
                                            echo'
                                            <tr>
                                                <td style="text-align:center;">'.$sr.'</td>
                                                <td>'.$rowsvalues['not_title'].'</td>
                                                <td>'.$rowsvalues['not_description'].'</td>
                                                <td>'.date('d M Y' , strtotime(cleanvars($rowsvalues['dated']))).'</td>
                                                <td>'.$type.'</td>
                                            </tr>';
                                        }
                                        echo'
                                    </tbody>
                                </table>
                            </div>
                            <!--<div class="page-break"></div>-->';
                        } 
                    }
                    else{
                        echo 'hello';
                        echo'<h2 style="text-align: center; color: red; margin-top: 50px;">No Record Found</h2>';
                    }
                    echo'
                    <span style="font-size:9px;">issue by: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>
                    <span style="font-size:9px; float:right; margin-top:3px;">Print Date: '.date("m/d/Y").'</span>
                </td>
            </tr>
        </table>
    </body>
    <script type="text/javascript" language="javascript1.2">
        //Do print the page
        if (typeof(window.print) != "undefined") {
            window.print();
        }
    </script>
</html>';
?>