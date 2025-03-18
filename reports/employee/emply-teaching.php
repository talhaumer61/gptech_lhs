<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
//------------------------------------------------
$sqllmscampus  = $dblms->querylms("SELECT campus_name
									FROM ".CAMPUS." 
									WHERE campus_status = '1' AND campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
$value_campus = mysqli_fetch_array($sqllmscampus);
//------------------------------------------------
echo '
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Teaching Faculty</title>
<style type="text/css">
body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
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
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
	@page { 
		 
	   margin: 4mm 4mm 4mm 4mm; 
	}
    #printPageButton {
        display: none;
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
                        '.SCHOOL_NAME.'<span style="font-size: 18px;">('.$value_campus['campus_name'].')</span>
                    </h2>
                    <h4>Teaching Faculty</h4>
                </div>
                <div class="line1"></div>
                    <div style="font-size:12px;">
                        <table style="width:100%; border-collapse:collapse; margin-top: 10px;" border="1">
                            <tbody>				
                                <tr>
                                    <th>Sr#</th>
                                    <th>Designation</th>
                                    <th>Name</th>
                                    <th>Registration</th>
                                    <th>Joining Date</th>
                                </tr>';
                                $srno = 0;
                                $sqllmsEmply = $dblms->querylms("SELECT d.designation_name, e.emply_name, e.emply_regno, e.emply_joindate
                                                                        FROM ".DESIGNATIONS." d
                                                                        INNER JOIN ".EMPLOYEES." e ON e.id_designation = d.designation_id
                                                                        WHERE e.emply_status = '1' AND e.is_deleted != '1' AND e.id_type = '1'
                                                                        AND e.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
                                                                        ");
                                while($valEmply = mysqli_fetch_array($sqllmsEmply)) {
                                    echo'
                                    <tr>';
                                        $srno++;
                                        echo'
                                        <th width="50">'.$srno.'</th>
                                        <th width="200">'.$valEmply['designation_name'].'</th>
                                        <td>'.$valEmply['emply_name'].'</td>
                                        <td>'.$valEmply['emply_regno'].'</td>
                                        <td>'.date("D d-m-Y", strtotime($valEmply['emply_joindate'])).'</td>
                                    </tr>';
                                }
                                echo'
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>';
?>