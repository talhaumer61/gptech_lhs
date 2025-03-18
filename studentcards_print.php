<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
    
    $sqllmscampus  = $dblms->querylms("SELECT * 
                                        FROM ".CAMPUS." 
                                        WHERE campus_status = '1' AND campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
    $value_campus = mysqli_fetch_array($sqllmscampus);

    $sql2 = "";
    if(isset($_GET['id_class']) && !empty($_GET['id_class']))
    {
        $sql2 = " AND s.id_class = '".$_GET['id_class']."' ";
    }
    $sqllms	= $dblms->querylms("SELECT  s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, 
                                s.std_nic, s.std_phone, s.id_class, s.id_session,
                                s.std_rollno, s.std_regno, s.std_photo, c.class_name, se.session_name
                                FROM ".STUDENTS." s
                                INNER JOIN ".CLASSES." c  ON c.class_id = s.id_class
                                INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
                                WHERE s.std_id != '' AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' $sql2
                                ORDER BY s.std_id DESC
                            ");
    echo '
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">  
            <title>Student Cards Print</title>
            <style type="text/css">
                body {
                    overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; 
                    width: 1320px;
                    margin: auto;
                }
                @media all {
                    .page-break	{ 
                        display: none; 
                    }
                }
                @media print {
                    .page-break	{ 
                        display: block; page-break-before: always; 
                    }
                    body {
                        -webkit-print-color-adjust: exact;
                    }
                    @page { 
                         
                    }
                    #printPageButton {
                        display: none;
                    }
                }
                body .btn-primary {
                    color: #ffffff;
                    text-shadow: 0 -1px 0 rgb(0 0 0 / 25%);
                    background-color: #cb3f44;
                    border-color: #cb3f44;
                }
                body .btn {
                    white-space: normal;
                }
                .ml-sm {
                    margin-left: 10px !important;
                }
                .mb-xs {
                    margin-bottom: 5px !important;
                }
                .pull-right {
                    float: right !important;
                }
                .btn {
                    margin-right:20px;
                    margin-top:20px;
                    display: inline-block;
                    padding: 6px 12px;
                    font-size: 14px;
                    font-weight: normal;
                    line-height: 1.42857143;
                    text-align: center;
                    vertical-align: middle;
                    touch-action: manipulation;
                    cursor: pointer;
                    user-select: none;
                    background-image: none;
                    border: 1px solid transparent;
                    border-radius: 4px;
                }
                h1 { 
                    text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:26px; font-weight:700; text-transform:uppercase; 
                }
                .spanh1 { 
                    font-size:14px; font-weight:normal; text-transform:none; text-align:right; float:right; margin-top:10px; 
                }
                h2 { 
                    text-align:left; margin:0; margin-top:0; margin-bottom:1px; font-size:24px; font-weight:700; text-transform:uppercase; 
                }
                .spanh2 { 
                    font-size:20px; font-weight:700; text-transform:none; 
                }
                h3 { 
                    text-align:center; margin:0; margin-top:0; margin-bottom:1px; font-size:18px; font-weight:700; text-transform:uppercase; 
                }
                h4 { 
                    margin:0;  margin-top: -33%; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em; margin-left: 53%;  
                }
                h5{
                    margin-left: 53%;
                }
                .table1 { 
                    font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; background-image: url("assets/images/student_card/card_front.jpeg");background-repeat: no-repeat; background-size: 100% 100%;
                }
                .table2 {
                    font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; background-image: url("assets/images/student_card/card_back.jpeg");background-repeat: no-repeat; background-size: 100% 100%;
                    }
                .line1 {
                    width:100%; margin-top:2px; margin-bottom:5px; 
                    }
            </style>
            <link rel="shortcut icon" href="images/favicon/favicon.ico">
        </head>
        <body>';
            while($rowsvalues = mysqli_fetch_array($sqllms)) {
                if($rowsvalues['std_photo']) { 
                    $photo = 'uploads/images/students/'.$rowsvalues['std_photo'].'';
                } else {
                    $photo = 'uploads/default-student.jpg';
                }
                echo'
                <table width="49%" height="300px;" class="page table1" cellpadding="8" cellspacing="15" align="left" style="border-collapse:collapse; margin-left: 10px;">
                    <tr>
                        <td>
                            <img src="'.$photo.'" style="width: 127px; height: 124px; margin-top: 22%; margin-bottom: 22px; margin-left: 26px;">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>'.$rowsvalues['std_name'].'</h4>
                            <h5 >'.$rowsvalues['class_name'].'</h5>
                            <h5 style="margin-top: -2px;">';if($rowsvalues['std_regno']){echo''.$rowsvalues['std_regno'].'';}else{echo'<br>';}echo'</h5>
                            <h5 style="margin-top: -0px;">'.$rowsvalues['session_name'].'</h5>
                        </td>

                    </tr>
                </table>';
            }
            echo '
            <br>
            <button type="button" id="printPageButton" onClick="window.print();" class="modal-with-move-anim ml-sm mb-xs btn btn-primary btn-xs pull-right">Print</button>
        </body>
    </html>';
?>