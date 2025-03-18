<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();

    // Class
    if(!empty($_GET['cls'])) {
        $classSql = "AND sub.id_class = '".cleanvars($_GET['cls'])."'";
    } else {
        $classSql = "";
    }

    // Subject Type    
    if(!empty($_GET['type'])) {
        $subType = "AND sub.subject_type = '".cleanvars($_GET['type'])."'";
    } else {
        $subType = "";
    }

    // Academic Calendar
    $sqllms	= $dblms->querylms("SELECT sub.subject_code, sub.subject_name, sub.subject_type, sub.weekly_period, sub.instruction_medium, sub.monthly_totalmarks, sub.monthly_passmarks, sub.term_totalmarks, sub.term_passmarks, c.class_name
                                    FROM ".CLASS_SUBJECTS." sub 
                                    INNER JOIN ".CLASSES." c ON c.class_id = sub.id_class
                                    WHERE sub.subject_id != '' AND sub.is_deleted != '1'
                                    AND sub.subject_status = '1' $classSql $subType
                                    ORDER BY c.class_name, sub.subject_name ASC");
                                    
echo '
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Class Subjects - '.SCHOOL_NAME.'</title>
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
                <div class="row">
                    <h2>
                        <img src="uploads/logo.png" class="img-fluid" style="width: 50px; height: 50px; vertical-align: middle;">
                        '.SCHOOL_NAME.'
                    </h2>
                    <h4>Class Subjects</h4>
                </div>
                <div class="line1"></div>
                    <div style="font-size:12px;">
                        <table style="width:100%; border-collapse:collapse; margin-top: 10px;" border="1">
                                <tr>
                                    <th style="text-align:center;">No.</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Weekly Lectures</th>
                                    <th>Medium Of Instruction</th>
                                    <th>Subject Type</th>
                                    <th>Class Name</th>
                                    <th>Montly Total Marks</th>
                                    <th>Montly Passing Marks</th>
                                    <th>Term Total Marks</th>
                                    <th>Term Passing Name</th>
                                </tr>
                            <tbody>	';
                                $srno = 0;
                                while($rowsvalues = mysqli_fetch_array($sqllms)) {
                                    $srno++;
                                    echo '
                                    <tr>
                                        <td style="text-align:center;">'.$srno.'</td>
                                        <td>'.$rowsvalues['subject_code'].'</td>
                                        <td>'.$rowsvalues['subject_name'].'</td>
                                        <td>'.$rowsvalues['weekly_period'].'</td>
                                        <td>'.get_instrmedium($rowsvalues['instruction_medium']).'</td>
                                        <td>'.get_subjecttype($rowsvalues['subject_type']).'</td>
                                        <td>'.$rowsvalues['class_name'].'</td>
                                        <td>'.$rowsvalues['monthly_totalmarks'].'</td>
                                        <td>'.$rowsvalues['monthly_passmarks'].'</td>
                                        <td>'.$rowsvalues['term_totalmarks'].'</td>
                                        <td>'.$rowsvalues['term_passmarks'].'</td>
                                    </tr>';
                                }
                                echo '
                            </tbody>
                        </table>
                    </div>
                </div>
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