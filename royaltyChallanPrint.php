<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();

    $sqllmsChallan = $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.id_month, f.issue_date, f.due_date, f.total_amount,
                                        c.campus_name, c.campus_phone, c.campus_head, d.*
                                        FROM ".FEES." f									
                                        INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
                                        INNER JOIN ".ROYALTY_CHALLAN_DET." d ON d.id_setup = f.id
                                        WHERE f.challan_no = '".cleanvars($_GET['id'])."'
                                        AND f.is_deleted != '1' LIMIT 1");
    $vlaueChallan = mysqli_fetch_array($sqllmsChallan); 
    echo '
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Royalty Challan Print</title>
            <style type="text/css">
                body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
                @media all {
                    .page-break	{ display: none; }
                }

                @media print {
                    .page-break	{ display: block; page-break-before: always; }
                    @page { 
                        size: A4 portrait;
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
                    top:15%;
                    left:10%;
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
            <table width="99%" border="0" class="page '.($vlaueChallan['status'] == 1 ? 'paid' : '').'" cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
                <tr>
                    <td width="45%">
                        <h2>'.SCHOOL_NAME.'</h2>
                        <p style="">'.SCHOOL_ADDRESS.'</p>
                        <a href="'.SCHOOL_WEBSITE.'" target="_blank">'.SCHOOL_WEBSITE.'</a>
                        <p>'.SCHOOL_PHONE.'</p>
                    </td>
                    <td width="30%"></td>
                    <td width="25%">
                        <img src="uploads/logo.png" class="img-fluid"  align="right" style="width: 100px; height: 100px;"> 
                    </td>
                </tr>
                <tr>
                    <td>
                        Bill To: <span style="text-decoration:underline;">'.$vlaueChallan['campus_name'].'</span>
                        <p>'.$vlaueChallan['campus_phone'].'</p>
                    </td>
                    <td>
                        Ship To: <span style="text-decoration:underline;">'.$vlaueChallan['campus_head'].'</span>
                        <p>'.$vlaueChallan['campus_phone'].'</p>
                    </td>
                    <td style="text-align: right;">
                        Invoice # <span style="text-decoration:underline;">'.$_GET['id'].'</span><br>
                        Issue Date : <span style="text-decoration:underline;">'.$vlaueChallan['issue_date'].'</span><br>
                        Due Date : <span style="text-decoration:underline;">'.$vlaueChallan['due_date'].'</span><br>
                        Month : <span style="text-decoration:underline;">'.get_monthtypes($vlaueChallan['id_month']).'</span><br>
                    </td>
                </tr>
                <tr >
                    <td colspan="3">
                            <div style="font-size:12px; margin-top:10px;">
                                <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Royalty Type</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Royalty Amount</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">No Of Students</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Months</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;">Total </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td style="text-align:center;">'.get_royaltyTypes($vlaueChallan['royalty_type']).'</td>
                                                <td style="text-align:center;">'.$vlaueChallan['royalty_amount'].'</td> 
                                                <td style="text-align:center;">'.$vlaueChallan['no_of_std'].'</td>
                                                <td style="text-align:center;">'.$vlaueChallan['no_of_month'].'</td>
                                                <td style="text-align:center; width:80px;">'.number_format(round($vlaueChallan['total_amount'])).'</td>
                                            </tr>
                                        <tr>
                                            <td colspan="4" style="text-align:center; font-size:12px; font-weight:bold; border:1px solid #333;">Grand Totals</td>
                                            <td style="text-align:center; font-size:12px; font-weight:bold;  border:1px solid #333;">'.number_format($vlaueChallan['total_amount']).'</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table align="right" width="100%" style="text-align: right; font-size:16px; padding: 5px;">
                                        <tr>
                                            <td style="font-weight:bold;"> Sub Total </td>
                                            <td style="text-decoration:underline; width: 100px;">'.number_format($vlaueChallan['total_amount']).'</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold;"> Arears </td>
                                            <td style="text-decoration:underline; width: 100px;">0.00</td>
                                        </tr>
                                        <tr style="font-weight:bold; font-size: 18px;">
                                            <td >  Balance Due </td>
                                            <td style="text-decoration:underline; width: 100px;">'.number_format($vlaueChallan['total_amount']).'</td>
                                        </tr>
                                </table>
                                <div align="left" width="100%"  style="text-align: left;">
                                        <p style="border-bottom: 2px solid black; font-size: 18px; font-weight: bold; width: 180px;">Terms & Conditions</p>
                                        <ul>
                                            <li>Bank Name: Mezan Bank Branch Code: 0293</li>
                                            <li>Account Title: Laurel Home International. Account Number: 02930104623590</li>
                                            <li>Please deposit the dues before due date & ensure the sharing of scanned deposit slip by WhatsApp on 0309-0000994 (Central Coordinator).</li>
                                            <li>In case of any query feel free to contact on 0309-0000994 (Central Coordinator).</li>
                                            <li>08:30 am to 04:30 pm</li>
                                        </ul>					
                                </div>
                            </div>
                        <span style="font-size:9px;">issue by: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>
                        <span style="font-size:9px; float:right; margin-top:3px;">Print Date: '.date("m/d/Y").'</span>
                    </td>
                </tr>
            </table>
        </body>
        <script type="text/javascript" language="javascript1.2">
            <!--
            //Do print the page
            if (typeof(window.print) != "undefined") {
                window.print();
            }
            -->
        </script>
    </html>';
?>