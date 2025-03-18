<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
    
    $sqllmsChallan = $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.issue_date, f.due_date, f.total_amount, f.id_demand, c.campus_name, c.campus_phone, c.campus_head, d.total_std, s.session_name, t.type_name, dd.amount_per_std
                                        FROM ".EXAM_FEE_CHALLANS." f									
                                        INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
                                        INNER JOIN ".EXAM_DEMAND." d ON d.demand_id = f.id_demand
                                        INNER JOIN ".EXAM_DEMAND_DET." dd ON dd.id_demand = d.demand_id 
                                        INNER JOIN ".EXAM_TYPES." t ON t.type_id = f.id_examtype
                                        INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session
                                        WHERE f.challan_no = '".cleanvars($_GET['id'])."'
                                        AND f.is_deleted != '1' 
                                        GROUP BY dd.id_demand
                                        LIMIT 1
                                    ");
    $vlaueChallan = mysqli_fetch_array($sqllmsChallan); 
    $array  =   explode('-',$vlaueChallan['session_name']);
    $sesion =   $array[1];
    // CLASS DETAILS
    $sqlClass = "SELECT c.class_name, dd.no_of_std
                    FROM ".CLASSES." as c
                    INNER JOIN ".EXAM_DEMAND_DET." dd ON (
                                                            dd.id_class = c.class_id
                                                            AND dd.id_demand = '".cleanvars($vlaueChallan['id_demand'])."'  
                                                        )
                    WHERE c.is_deleted = '0'
                    AND c.class_status = '1'
                    ORDER BY c.class_id ASC
                ";
    echo '
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>Exam Fee Challan Print</title>
            <style type="text/css">
                body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
                @media all {
                    .page-break	{ display: none; }
                }
                @media print {
                    .page-break	{ display: block; page-break-before: always; }
                    body {-webkit-print-color-adjust: exact;}
                    @page { 
                         
                    }
                }
                h1 { 
                    text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:34px; font-weight:700; text-transform:uppercase; 
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
                    text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em;  
                }
                .line1 { 
                    border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; 
                }
                .payable { 
                    border:2px solid #000; padding:2px; text-align:center; font-size:14px; 
                }
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
                .text-center{
                    text-align:center;
                }
            </style>
            <link rel="shortcut icon" href="images/favicon/favicon.ico">
        </head>
        <body '.($vlaueChallan['status'] == 1 ? 'class="paid"' : '' ).'>
            <br>
            <table width="900px" align="center">
                <tr>
                    <td width="150px">
                        <img src="uploads/logo.png" class="img-fluid"  align="right" style="width: 130px; height: 130px;"> 
                    </td>
                    <td class="text-center">
                        <h1 style="border-bottom:2px solid;   display: inline;">'.SCHOOL_NAME.'</h1>
                        <p style=" font-weight:bold; margin-top:5px">'.SCHOOL_ADDRESS.' Tel: '.SCHOOL_PHONE.'</p>
                        <p style="font-weight:bold; margin-top:-15px"">Email: '.SCHOOL_EMAIL.', '.SCHOOL_WEBSITE.'</p>
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <table width="900px" align="center">
                <tr>
                    <td><h3>Invoice</h3></td>
                </tr>
            </table>
            <br>
            <table width="900px" align="center">
                <tr>
                    <td  width="180px">
                        <span style="font-weight:bold;">'.$vlaueChallan['campus_name'].'</span><br>
                        <span style="font-weight:bold;">'.$vlaueChallan['campus_head'].'</span><br>
                        <span style="font-weight:bold;">'.$vlaueChallan['campus_phone'].'</span>
                    </td>
                    <td></td>
                    <td  width="180px"><h
                        <span style="font-weight:bold;">Invoice # '.$_GET['id'].'</span><br>
                        <span style="font-weight:bold;">Issue Date : '.$vlaueChallan['issue_date'].'</span><br>
                        <span style="font-weight:bold;">Due Date : '.$vlaueChallan['due_date'].'</span><br>
                    </td>
                </tr>
            </table>
            <table width="900px" align="center" style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1">
                <tr style="background-color:black; color:white;">
                    <th class="text-center">Description</th>
                    <th class="text-center">Number of Students</th>
                    <th class="text-center">ExamFee</th>
                    <th class="text-center">Total</th>
                </tr>
                <tr>
                    <td class="text-center">'.$vlaueChallan['type_name'].' '.$sesion.'</td>
                    <td class="text-center">'.$vlaueChallan['total_std'].'</td>
                    <td class="text-center">'.$vlaueChallan['amount_per_std'].'</td>
                    <td class="text-center">'.$vlaueChallan['total_amount'].'</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-left-style : hidden!important; border-bottom-style : hidden!important;"></td>
                    <th class="text-center">Payable</th>
                    <th class="text-center">'.$vlaueChallan['total_amount'].'</th>
                </tr>
            </table>
            <table width="800px" align="center" style="border-collapse:collapse; border:1px solid #666; margin-top:100px;" cellpadding="2" border="1">
                <tr>';
                    $resultClass = $dblms->querylms($sqlClass);
                    while($valClass = mysqli_fetch_array($resultClass)){
                        echo '<td class="text-center">'.$valClass['class_name'].'</td>';
                    }
                    echo '
                </tr>
                <tr>';
                    $resultStd = $dblms->querylms($sqlClass);
                    while($valStd = mysqli_fetch_array($resultStd)){
                        echo '<td class="text-center">'.$valStd['no_of_std'].'</td>';
                    }
                    echo '
                </tr>
            </table>
            <table width="920px" align="center" style="margin-top:250px;">
                <tr>
                    <td><p style=" font-size: 18px; font-weight: bold; ">Terms & Conditions</p></td>
                </tr>
                <tr>
                    <td>
                        <ul>
                            <li>Make sure about Number of Students,</li>
                            <li>Above Mentionaed Students Should be Entered on CMS before '.date('d M-y', strtotime($vlaueChallan['due_date'])).'</li>
                            <li>Bank Name: Finja Bank Branch Code: 0293 Account Title :<b><u>Laurel Home International.</u></b> Account Number :<b><u>02930104623590</u></b></li>
                            <li>Account Title: Laurel Home International. Account Number: 02930104623590</li>
                            <li>Please deposit the dues before due date & ensure the sharing of scanned deposit slip by WhatsApp on 0309-0000994 (Central Coordinator). In case of late payment, In case of any query feel free to contact on 0309-0000994(Centeral Coordinator). 08:30 am to 04:30 pm</li>
                        </ul>
                    </td>
                </tr>
            </table>
        </body>
    </html>';
?>
<script type="text/javascript" language="javascript1.2">
    //Do print the page
    if (typeof(window.print) != "undefined") {
        window.print();
    }
</script>