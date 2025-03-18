<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

echo '
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student List Print</title>
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

            $sql1 = "";
            $sql2 = "";
            $sql3 = "";
            $sql4 = "";
            $sql5 = "";
            $class = "";
            $id_campus = "";
            $std_gender = "";
            $std_status = "";
            $id_classlevel = "";

            //  class
            if(isset($_GET['id_class']) && !empty($_GET['id_class'])){
                $sql1 = "AND s.id_class = '".$_GET['id_class']."' ";
                $class = $_GET['id_class'];
            }
            //  gender
            if($_GET['std_gender']){
                $sql2 = "AND s.std_gender = '".$_GET['std_gender']."'";
                $std_gender = $_GET['std_gender'];
            }
            //  status
            if($_GET['std_status']){
                $sql3 = "AND s.std_status = '".$_GET['std_status']."'";
                $std_status = $_GET['std_status'];
            }
            //  campus
            if(isset($_GET['id_campus']) && !empty($_GET['id_campus'])){
                $sql4 = "AND s.id_campus = '".$_GET['id_campus']."' ";
                $id_campus = $_GET['id_campus'];
            }
            //  classlevel
            if(isset($_GET['id_classlevel']) && !empty($_GET['id_classlevel'])){
                $sql5 = "AND c.id_classlevel = '".$_GET['id_classlevel']."' ";
                $id_classlevel = $_GET['id_classlevel'];
            }
            
            $sqllms	= $dblms->querylms("SELECT s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, 
                                                s.std_phone, s.id_class, s.id_session,
                                                s.std_rollno, s.std_regno, s.std_photo, c.class_name, c.id_classlevel, se.section_name,
                                                sn.session_name
                                        FROM ".STUDENTS." s
                                        INNER JOIN ".CLASSES."  c  ON c.class_id = s.id_class
                                        LEFT JOIN ".CLASS_SECTIONS."  se  ON se.section_id = s.id_section
                                        LEFT JOIN ".SESSIONS."  sn  ON sn.session_id = s.id_session
                                        WHERE s.std_id != '' AND s.is_deleted != '1' $sql1 $sql2 $sql3 $sql4 $sql5
                                        ORDER BY c.id_classlevel, c.class_id ASC");
            if(mysqli_num_rows($sqllms) > 0){
                echo'
                    <div style="font-size:12px; margin-top:10px;">
                        <table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
                            <thead>
                                <tr>
                                    <td colspan="10"><h4 style="margin-top: 10px; color: red;">Students of '.(!empty($class) ? 'class '.$class : SCHOOL_NAME).' '.(!empty($std_gender) ? '- '.$std_gender.'' : '').' '.(!empty($std_status) ? '- '.$std_status.'' : '').'</h4></td>
                                </tr>
                                <tr>
                                    <th class="center" width="30">Sr.</th>
                                    <th width= 40>Photo</th>
                                    <th>Student Name</th>
                                    <th>Father Name</th>
                                    <th>Roll no</th>
                                    <th>Phone</th>
                                    <th>Class</th>
                                    <th>Level</th>
                                    <th width="70">Section</th>
                                    <th width="70">Session</th>
                                    <th width="70" class="center">Status</th>
                                </tr>
                            </thead>
                            <tbody>';
                                while($rowsvalues = mysqli_fetch_array($sqllms)){
                                    $sr++;
                                    if($rowsvalues['std_photo']) { 
                                        $photo = "uploads/images/students/".$rowsvalues['std_photo']."";
                                    }
                                    else{
                                        $photo = "uploads/default-student.jpg";
                                    }
                                    echo'
                                    <tr>
                                        <td style="text-align: center">'.$sr.'</td>
                                        <td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
                                        <td>'.$rowsvalues['std_name'].'</td>
                                        <td>'.$rowsvalues['std_fathername'].'</td>
                                        <td>'.$rowsvalues['std_rollno'].'</td>
                                        <td>'.$rowsvalues['std_phone'].'</td>
                                        <td>'.$rowsvalues['class_name'].'</td>
                                        <td>'.get_classlevel($rowsvalues['id_classlevel']).'</td>
                                        <td>'.$rowsvalues['section_name'].'</td>
                                        <td style="text-align: center">'.$rowsvalues['session_name'].'</td>
                                        <td style="text-align: center">'.get_status($rowsvalues['std_status']).'</td>
                                    </tr>';
                                }
                                echo'
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="page-break"></div> -->';
            }
            else{
                echo'<h2 style="text-align: center; color: red; margin-top: 50px;">No Record Found</h2>';
            }
			echo '
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