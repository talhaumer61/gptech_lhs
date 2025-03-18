<?php
$id = $_GET['id'];
if (!empty($id)){
    require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
    $sqllms	= $dblms->querylms("SELECT s.*, c.class_name, c.id_classlevel, se.section_name, sn.session_name, cm.campus_name, cm.campus_email, cm.campus_phone, cm.campus_address, cm.campus_regno, a.adm_username,	
                                (SELECT class_name FROM ".CLASSES." WHERE class_id > c.class_id ORDER BY class_id LIMIT 1) AS next_class_name
                                FROM ".STUDENTS." s
                                INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
                                LEFT JOIN ".CLASS_SECTIONS." se ON se.section_id = s.id_section
                                LEFT JOIN ".SESSIONS." sn ON sn.session_id = s.id_session
                                LEFT JOIN ".ADMINS." a ON a.adm_id = s.id_loginid
                                INNER JOIN ".CAMPUS." cm ON cm.campus_id = s.id_campus
                                WHERE s.std_id      = '".$id."'
                                AND s.is_deleted	= '0'
                                AND s.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                ORDER BY s.std_id DESC");
    echo'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Print Leaving Certificate</title>';
        include_once("include/header-css.php");
        echo'
        <style>
            .display {
                display: none;
            }
            .page-break {
                -webkit-print-color-adjust: exact !important;
            }
            @media print {
                .page-break	{

                }
                @page { 
                    
                }
            }
            .page-break{
                color: #333;
                padding: 0 5rem;
            }
            .font-times{
                font-family:"sans, serif; 
                color: #333; 
                font-weight:bold;
            }
            .border-bottom{
                border-bottom: 1px solid #777;
            }
            .pr-10{
                padding-right: 5px;
            }
            .pl-10{
                padding-left: 5px;
            }
            td{
                font-size: 12px;
            }
        </style>
    </head>
    <body class="" id="body">';
        $rowsvalues = mysqli_fetch_array($sqllms);
            $photo = ($rowsvalues['std_photo'])? "uploads/images/students/".$rowsvalues['std_photo']."": "uploads/default-student.jpg";
            echo'
            <div class="page-break">
                <div class="table-responsive">
                <!--
                <button id="Print" style="" onclick="submitPrint();"><i class="fa fa-print"></i> Print</button>
                -->
                    <div class="">
                        <table width="100%" class="mt-lg">
                            <tbody>
                                <tr>
                                    <td width="100" rowspan="4">
                                        <img src="uploads/logo.png" style="max-height: 150px; margin-right: 40px;">  
                                    </td>
                                    <td width="60" colspan="3" style="margin-left: 40px;"> 
                                        <h1 style="text-decoration:underline; font-weight: bold; font-size: 30px; margin-left: 5px;"> Laurel Home International Schools </h1>
                                        <h5>
                                            <b style="margin-left: 5px;">Campus Name: </b><span style="border-bottom: dotted 1px #000; "> '.$rowsvalues['campus_name'].'</span>
                                            <b style="margin-left: 40px;">Campus Reg: </b><span style="border-bottom: dotted 1px #000; "> '.$rowsvalues['campus_regno'].'</span>
                                        </h4>
                                        <h5>
                                            <b style="margin-left: 5px;">Email: </b><span style="border-bottom: dotted 1px #000; "> '.$rowsvalues['campus_email'].'</span>
                                            <b style="margin-left: 70px;">Phone: </b><span style="border-bottom: dotted 1px #000; "> '.$rowsvalues['campus_phone'].'</span>
                                        </h5>
                                        <h5>
                                            <b style="margin-left: 5px;">Address: </b><span style="border-bottom: dotted 1px #000; ">'.$rowsvalues['campus_address'].'</span>
                                        </h5>   
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td class="text-center" >
                                    <h4 style="margin-top: 5rem;" class="font-times mb-lg">CERTIFICATE OF SCHOOL LEAVING</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <img src="'.$photo.'" class="mb-lg mt-lg" width="100" height="100" style="border-radius: 50%;" style="margin-top: 5rem;">
                                    <h4 style="text-align: justify; font-size: 15px; margin-top: 5rem;">
                                        <b>'.$rowsvalues['std_name'].'</b> 
                                        '.($rowsvalues['std_gender']=='Male' ? 's/o' : 'd/o').' 
                                        <b>'.$rowsvalues['std_fathername'].'</b> 
                                        with CNIC: '.$rowsvalues['std_nic'].' 
                                        Registration No: <b>'.$rowsvalues['std_regno'].'</b>,
                                        Date of Birth <b>'.date('d F, Y', strtotime($rowsvalues['std_dob'])).'</b>, 
                                        Address: <b>'.$rowsvalues['std_c_address'].'</b> 
                                        Certified that the above mentioned student attended this school from <b>'.date('d F, Y', strtotime($rowsvalues['std_admissiondate'])).'</b> 
                                        to <b>'.date('d F, Y').'</b> 
                                        has paid all dues to the school and was allowed on the above date to withdraw '.($rowsvalues['std_gender']=='Male' ? 'his' : 'her').' name. '.($rowsvalues['std_gender']=='Male' ? 'He' : 'She').' was reading in class 
                                        <b>'.$rowsvalues['class_name'].'</b> 
                                        and';
                                        $str = '';
                                        if (get_duringclass($_GET['duringClass']) == 'Passed')
                                            $str = '<b> '.get_duringclass($_GET['duringClass']).'</b> in the examination. '.($rowsvalues['std_gender']=='Male' ? 'He' : 'She').' was promised for promotion to the next class <b>'.$rowsvalues['next_class_name'].'</b>';
                                        else if (get_duringclass($_GET['duringClass']) == 'Failed')
                                            $str = '<b> '.get_duringclass($_GET['duringClass']).'</b> in the examination and '.($rowsvalues['std_gender']=='Male' ? 'he' : 'she').' and will continue in the class '.$rowsvalues['class_name'].'';
                                        else
                                            $str = ' he left the school <b>'.get_duringclass($_GET['duringClass']).'</b>';
                                        echo $str.((get_duringclass($_GET['duringClass']) == 'Passed') ? ' which was given to '.($rowsvalues['std_gender']=='Male' ? 'him' : 'her'): '').'.
                                    </h4>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table width="100%" style="margin-top: 20rem;">
                        <body>
                            <tr>
                                <td width="90">Date of issue:</td>
                                <td width="100" class="border-bottom center">'.date('d-m-Y').'</td>
                                <td width=""></td>
                                <td width="80">Principal:</td>
                                <td width="200" class="border-bottom"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <script type="text/javascript" language="javascript1.2">
            // var printButton = document.getElementById("Print");
            // var printClicked = false;
            var printClicked = true;

            // printButton.addEventListener("click", function() {
            // printClicked = true;
            // printButton.style.display = "none";
            window.print();
            // });

            window.onafterprint = function() {
                if (printClicked) {
                    window.location.href = "students.php?view=leaving_certificate&changeStdId='.$id.'";
                }
            };
            </script>
        </body>
    </html>';
}
?>