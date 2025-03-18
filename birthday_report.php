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
        <title>Birthday Report Print</title>
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
                    // EMPLOYEES BIRTHDAY REPORT
                    if($view == 'emp'){
                        $date = $_GET['date'];
                        echo'
                        <div style="text-align:center;">                       
                            <img src="uploads/logo.png" class="img-fluid" style="width: 80px; height: 80px;">
                        </div>
                        <h2 style="text-align: center;">'.SCHOOL_NAME.'</h2>
                        <br>
                        <h4 style="color: red;">Employees  Birthday Report</h4>
                        <div style="font-size:12px; margin-top:10px;">';
                            // QUERY
                            $sqllmsEmp	= $dblms->querylms("SELECT e.emply_id, e.emply_name, e.emply_gender, e.emply_phone, e.emply_regno, e.emply_photo, e.emply_dob
                                                            FROM ".EMPLOYEES." e
                                                            WHERE e.is_deleted  = '0'
                                                            AND e.emply_status  = '1'
                                                            AND e.id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                            AND e.emply_dob LIKE '%".$date."'
                                                            ORDER BY DAY(e.emply_dob) ASC");
                            if(mysqli_num_rows($sqllmsEmp) > 0){
                                echo'
                                <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="40" style="text-align: center;">Sr.</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Regno</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Birthday</th>
                                        </tr>
                                    </thead>    
                                    <tbody>';
                                        $sr = 0;
                                        while($valuesEmp = mysqli_fetch_array($sqllmsEmp)):
                                            $sr++;
                                            if($valuesEmp['emply_photo']) { 
                                                $photo = "uploads/images/employees/".$valuesEmp['emply_photo']."";
                                            }else{
                                                $photo = "uploads/default.png";
                                            }
                                            echo '
                                            <tr>
                                                <td style="text-align: center;">'.$sr.'</td>
                                                <td width="40"><img src="'.$photo.'" style="width:40px; height:40px;"></td>
                                                <td>'.$valuesEmp['emply_name'].'</td>
                                                <td>'.$valuesEmp['emply_regno'].'</td>
                                                <td>'.$valuesEmp['emply_phone'].'</td>
                                                <td>'.$valuesEmp['emply_gender'].'</td>
                                                <td>'.date('d F',strtotime($valuesEmp['emply_dob'])).'</td>
                                            </tr>';
                                        endwhile;
                                        echo'
                                    </tbody>
                                </table>';
                            }else{
                                echo'<h2 style="text-align: center; color: red; margin-top: 50px;">No Record Found</h2>';
                            }
                            echo'
                        </div>
                        <!--<div class="page-break"></div>-->';                        
                    }
                    // STUDENTS BIRTHDAY REPORT
                    elseif($view == 'std'){
                        $date = $_GET['date'];
                        echo'
                        <div style="text-align:center;">                       
                            <img src="uploads/logo.png" class="img-fluid" style="width: 80px; height: 80px;">
                        </div>
                        <h2 style="text-align: center;">'.SCHOOL_NAME.'</h2>
                        <br>
                        <h4 style="color: red;">Students Birthday Report</h4>
                        <div style="font-size:12px; margin-top:10px;">';
                            // QUERY
                            $sqllmsStd	= $dblms->querylms("SELECT s.std_id, s.std_name, s.std_fathername, s.std_gender, s.std_phone, s.std_regno, s.std_photo, s.std_dob
                                                            FROM ".STUDENTS."	s
                                                            WHERE s.is_deleted  = '0'
                                                            AND s.std_status    = '1'
                                                            AND s.id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                            AND s.std_dob LIKE '%".$date."'
                                                            ORDER BY DAY(s.std_dob) ASC");
                            if(mysqli_num_rows($sqllmsStd) > 0){
                                echo'
                                <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="40" style="text-align: center;">Sr.</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Father Name</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Birthday</th>
                                        </tr>
                                    </thead>    
                                    <tbody>';
                                        $sr = 0;
                                        while($valuesStd = mysqli_fetch_array($sqllmsStd)):
                                            $sr++;
                                            if($valuesStd['std_photo']){
                                                $photo = "uploads/images/students/".$valuesStd['std_photo']."";
                                            }else{
                                                $photo = "uploads/default-student.jpg";
                                            }
                                            echo '
                                            <tr>
                                                <td style="text-align: center;">'.$sr.'</td>
                                                <td width="40"><img src="'.$photo.'" style="width:40px; height:40px;"></td>
                                                <td>'.$valuesStd['std_name'].'</td>
                                                <td>'.$valuesStd['std_fathername'].'</td>
                                                <td>'.$valuesStd['std_phone'].'</td>
                                                <td>'.$valuesStd['std_gender'].'</td>
                                                <td>'.date('d F',strtotime($valuesStd['std_dob'])).'</td>
                                            </tr>';
                                        endwhile;
                                        echo'
                                    </tbody>
                                </table>';
                            }else{
                                echo'<h2 style="text-align: center; color: red; margin-top: 50px;">No Record Found</h2>';
                            }
                            echo'
                        </div>
                        <!--<div class="page-break"></div>-->';                        
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