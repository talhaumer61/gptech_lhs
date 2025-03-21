<?php
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
    if(!empty($_SESSION['userlogininfo']['LOGINCAMPUS'])){
        $sql = " WHERE campus_id = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ";
    }
    else{
        $sql = "";
    }
    // CAMPUS INFO
    $sqllmscampus	= $dblms->querylms("SELECT campus_id, campus_code, campus_name, campus_address, campus_phone
                                        FROM ".CAMPUS." $sql LIMIT 1");
    $value_camp = mysqli_fetch_array($sqllmscampus);

    // STUDENT DETAILS
    $sqllms_cir	= $dblms->querylms("SELECT c.cir_subject, c.cir_dated, c.cir_refrence, c.cir_details, c.cir_regards, c.digital_signature, d.designation_name
                                    FROM ".CIRCULARS." c
                                    INNER JOIN ".DESIGNATIONS." d ON d.designation_id = c.id_designation
                                    WHERE c.cir_id != '' AND c.cir_id = '".$_GET['id']."'  
                                    AND c.is_deleted != '1'
                                    AND c.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
                                    AND c.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                    LIMIT 1
                                  ");
    if(mysqli_num_rows($sqllms_cir) > 0){                               
        $value_cir = mysqli_fetch_array($sqllms_cir);

        echo '
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Circulars</title>
            <style type="text/css">
                body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
                @media all {
                    .page-break	{ display: none; }
                }
                @media print {
                    .page-break	{ display: block; page-break-before: always; }
                    @page { 
                        size: A4;
                    }
                }
                h1 { text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:26px; font-weight:700; text-transform:uppercase; }
                .spanh1 { font-size:14px; font-weight:normal; text-transform:none; text-align:right; float:right; margin-top:10px; }
                h2 { text-align:left; margin:0; margin-top:0; margin-bottom:1px; font-size:24px; font-weight:700; text-transform:uppercase; }
                .spanh2 { font-size:20px; font-weight:700; text-transform:none; }
                h3 { text-align:center; margin:0; margin-top:0; margin-bottom:1px; font-size:18px; font-weight:700; text-transform:uppercase; }
                h4 { text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em; }
                td { padding-bottom:4px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
            </style>
            <link rel="shortcut icon" href="images/favicon/favicon.ico">
        </head>
        <body>
            <div id="print" style="orientation: landscape">
                <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css"/>
                <link rel="stylesheet" href="assets/stylesheets/theme.css"/>
                <br>
                <img src="uploads/logo.png" style="max-height : 100px;">
                <center style="margin-top: -100px; margin-left: 100px; text-align:right">
                    <h3 style="font-weight: 600;">Laurel Home International Schools <span style="text-transform: capitalize;">('.$value_camp['campus_name'].')</span></h3>
                    <p>
                        Date: '.$value_cir['cir_dated'].' <br>
                        Ref# '.$value_cir['cir_refrence'].'
                    </p>
                </center>
                <br>
                <div class="center">
                    <b>Subject: </b> <u>'.$value_cir['cir_subject'].'</u>
                </div>
                <section class="panel mt-md">
                    <p>'.html_entity_decode($value_cir['cir_details'], ENT_QUOTES, "UTF-8").'</p>
                    <div>
                        <b>Regards: </b>  '.$value_cir['cir_regards'].' <br>';
                        if(!empty($value_cir['digital_signature'])){
                            echo '<img src="uploads/images/circulars/digital_signatures/'.$value_cir['digital_signature'].'" style="max-height : 80px;"><br>';
                        }
                        echo '
                        <b>'.$value_cir['designation_name'].'
                    </div>
                </section>
            </div>
        </body>';
    }
    else{
        echo'<h1 style="text-align: center; margin-top: 50px; color: red;">No Record Found!</h1>';
    }
    echo'
        <script type="text/javascript" language="javascript1.2">
        //Do print the page
        if (typeof(window.print) != "undefined") {
            window.print();
        }
        </script>
    </html>';
?>

