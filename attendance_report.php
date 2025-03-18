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
        <title>Attendance Report Print</title>
        <style type="text/css">
            body { -webkit-print-color-adjust: exact !important; overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
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
            th { padding:5px; font-size: 14px;}
            td { padding-bottom:4px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
            .line1 { border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; }
            .payable { border:2px solid #000; padding:2px; text-align:center; font-size:14px; }
            
            .label {
                display: inline;
                padding: 0.2em 0.6em 0.3em;
                font-size: 75%;
                font-weight: bold;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: 0.25em;
            }
            .label-success{
                background: #47a447;
                color: #FFF;
            }
            .label-danger{
                background: #d2322d;
                color: #FFF;
            }
            .label-primary{
                background: #0088cc;
                color: #FFF;
            }
            .paid:after{
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
                <td width="341" valign="top">';

                    $month  = date('m');
                    $today  = date('Y-m-d');
                    $sql    = "";
                    $sql1   = "";
                    $sql2   = "";

                    // FEE REPORT
                    if($_GET['type'] == 'emp'){
                        $sqllms	= $dblms->querylms("SELECT e.emply_photo, e.emply_name, e.emply_email, d.designation_status, d.designation_name, ad.status,
                                                    COUNT(CASE WHEN ad.status = '1' THEN 1 else null end) as `present`, 
                                                    COUNT(CASE WHEN ad.status = '2' THEN 1 else null end) as `absent`, 
                                                    COUNT(CASE WHEN ad.status = '3' THEN 1 else null end) as `leave`, 
                                                    COUNT(CASE WHEN ad.status = '4' THEN 1 else null end) as `late`
                                                    FROM ".EMPLOYEES_ATTENDCE." a
                                                    INNER JOIN ".EMPLOYEES_ATTENDCE_DETAIL." ad ON ad.id_setup = a.id 
                                                    INNER JOIN ".EMPLOYEES." e ON e.emply_id = ad.id_emply AND e.emply_status = '1' AND e.is_deleted != '1'
                                                    INNER JOIN ".DESIGNATIONS." d ON d.designation_id = e.id_designation 
                                                    WHERE a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                                    AND a.dated = '".date('Y-m-d', strtotime($today))."'
                                                    GROUP BY ad.id_emply
                                                ");
                        if(mysqli_num_rows($sqllms) > 0){
                            echo'
                            <div style="text-align:center;">                       
                                <img src="uploads/logo.png" class="img-fluid" style="width: 80px; height: 80px;">
                            </div>
                            <h2 style="text-align: center;">'.SCHOOL_NAME.'</h2>
                            <br>
                            <h4 style="color: red;">Employees  Attendance Report <b>('.date('d M, Y', strtotime($today)).')</b> </h4>
                            <div style="font-size:12px; margin-top:10px;">
                                <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:40px; text-align: center;">Sr.</th>
                                            <th width="40" style="text-align: center;">Photo</th>
                                            <th>Employees</th>
                                            <th style="text-align: center;">Present</th>
                                            <th style="text-align: center;">Absent</th>
                                            <th style="text-align: center;">Leave</th>
                                            <th style="text-align: center;">Late</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        $srno = 0;
                                        $grandPresent = 0;
                                        $grandAbsent = 0;
                                        $grandLeave = 0;
                                        $grandLate = 0;
                                        while($rowsvalues = mysqli_fetch_array($sqllms)){
                                            if(!empty($rowsvalues['emply_photo'])){
                                                $photo = 'uploads/images/employees/'.$rowsvalues['emply_photo'].'';
                                            }else{
                                                $photo = 'uploads/defualt.png';
                                            }
                                            $srno++;                                        
                                            echo'
                                            <tr>
                                                <td style="width:40px; text-align: center;">'.$srno.'</td>
                                                <td style="text-align:center;"><img src="'.$photo.'" width="35" height="35"></td>
                                                <td>
                                                    <b>'.$rowsvalues['emply_name'].'</b>
                                                    <span class="ml-sm label label-primary"> '.$rowsvalues['designation_name'].'</span>
                                                </td>
                                                <td style="text-align:center;">'.($rowsvalues['status']==1 ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>').'</td>
                                                <td style="text-align:center;">'.($rowsvalues['status']==2 ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>').'</td>
                                                <td style="text-align:center;">'.($rowsvalues['status']==3 ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>').'</td>
                                                <td style="text-align:center;">'.($rowsvalues['status']==4 ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>').'</td>
                                            </tr>';
                                            $grandPresent = $grandPresent + $rowsvalues['present'];
                                            $grandAbsent = $grandAbsent + $rowsvalues['absent'];
                                            $grandLeave = $grandLeave + $rowsvalues['leave'];
                                            $grandLate = $grandLate + $rowsvalues['late'];
                                        }
                                        echo'
                                        <tr>
                                            <th colspan="3" style="text-align:center; border:1px solid #333;">Grand Total</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandPresent).'</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandAbsent).'</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandLeave).'</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandLate).'</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--<div class="page-break"></div>-->';
                        }
                    }
                    // FEE REPORT
                    elseif($_GET['type'] == 'std'){
                        $sqllms	= $dblms->querylms("SELECT c.class_name,
                                                    COUNT(CASE WHEN ad.status = '1' THEN 1 else null end) as `present`, 
                                                    COUNT(CASE WHEN ad.status = '2' THEN 1 else null end) as `absent`, 
                                                    COUNT(CASE WHEN ad.status = '3' THEN 1 else null end) as `leave`, 
                                                    COUNT(CASE WHEN ad.status = '4' THEN 1 else null end) as `late`
                                                    FROM ".STUDENT_ATTENDANCE." a
                                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." ad ON ad.id_setup = a.id
                                                    INNER JOIN ".CLASSES." c ON c.class_id = a.id_class AND c.class_status = '1' AND c.is_deleted = '0'
                                                    WHERE a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                                    AND a.dated = '".date('Y-m-d', strtotime($today))."'
                                                    GROUP BY a.id_class
                                                ");
                        if(mysqli_num_rows($sqllms) > 0){
                            echo'
                            <div style="text-align:center;">                       
                                <img src="uploads/logo.png" class="img-fluid" style="width: 80px; height: 80px;">
                            </div>
                            <h2 style="text-align: center;">'.SCHOOL_NAME.'</h2>
                            <br>
                            <h4 style="color: red;">Student Attendance Report <b>('.date('d M, Y', strtotime($today)).')</b> </h4>
                            <div style="font-size:12px; margin-top:10px;">
                                <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:40px; text-align: center;">Sr.</th>
                                            <th>Class Name</th>
                                            <th style="text-align: center;">Present</th>
                                            <th style="text-align: center;">Absent</th>
                                            <th style="text-align: center;">Leave</th>
                                            <th style="text-align: center;">Late</th>
                                            <th style="text-align: center;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        $srno = 0;
                                        $grandPresent = 0;
                                        $grandAbsent = 0;
                                        $grandLeave = 0;
                                        $grandLate = 0;
                                        $grandTotal = 0;
                                        while($rowsvalues = mysqli_fetch_array($sqllms)){
                                            $srno++;
                                            $total = $rowsvalues['present'] + $rowsvalues['absent'] + $rowsvalues['leave'] + $rowsvalues['late'];                                    
                                            echo'
                                            <tr>
                                                <td style="width:40px; text-align: center;">'.$srno.'</td>
                                                <td>'.$rowsvalues['class_name'].'</td>
                                                <td style="text-align:center;">'.$rowsvalues['present'].'</td>
                                                <td style="text-align:center;">'.$rowsvalues['absent'].'</td>
                                                <td style="text-align:center;">'.$rowsvalues['leave'].'</td>
                                                <td style="text-align:center;">'.$rowsvalues['late'].'</td>
                                                <td style="text-align:center;">'.$total.'</td>
                                            </tr>';
                                            $grandPresent = $grandPresent + $rowsvalues['present'];
                                            $grandAbsent = $grandAbsent + $rowsvalues['absent'];
                                            $grandLeave = $grandLeave + $rowsvalues['leave'];
                                            $grandLate = $grandLate + $rowsvalues['late'];
                                            $grandTotal = $grandTotal + $total;
                                        }
                                        echo'
                                        <tr>
                                            <th colspan="2" style="text-align:center; border:1px solid #333;">Grand Total</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandPresent).'</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandAbsent).'</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandLeave).'</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandLate).'</th>
                                            <th style="text-align:center; border:1px solid #333;">'.number_format($grandTotal).'</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--<div class="page-break"></div>-->';
                        }
                    }
                    else{
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