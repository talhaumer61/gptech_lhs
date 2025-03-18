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
echo'
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Fee Summary Report</title>
        
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
        <link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
        <link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
        <link rel="stylesheet" href="assets/vendor/bootstrap-switch/css/bootstrap-switch.min.css" />

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

            h4 { text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em;}

            td { padding-bottom:4px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
            .line1 { border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; }
            .payable { border:2px solid #000; padding:2px; text-align:center; font-size:14px; }

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
        <br>
        <div class="text-right mr-lg on-screen">
            <button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
            <button id="export_button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</button>
        </div>
	    <div id="printResult">';
            if(isset($_POST['submit'])){

                if(isset($_POST['id_summary']) && !empty($_POST['id_summary']) && $_POST['id_summary'] == 1){
                    // Class-wise Challans Details
                    require_once 'include/financial_reports/class_challans_detail.php';
                    
                }elseif(isset($_POST['id_summary']) && !empty($_POST['id_summary']) && $_POST['id_summary'] == 2){
                    // Month Wise Challans Summary
                    require_once 'include/financial_reports/monthly_challans_summary.php';

                }elseif(isset($_POST['id_summary']) && !empty($_POST['id_summary']) && $_POST['id_summary'] == 3){
                    // Individual Student Ledger
                    require_once 'include/financial_reports/individual_student_ledger.php';

                }elseif(isset($_POST['id_summary']) && !empty($_POST['id_summary']) && $_POST['id_summary'] == 4){
                    // Class Wise Fees collection Report
                    require_once 'include/financial_reports/class_fees_collection.php';
                    
                }elseif(isset($_POST['id_summary']) && !empty($_POST['id_summary']) && $_POST['id_summary'] == 5){
                    // Accumulative Fees Collection Report
                    require_once 'include/financial_reports/accumulative_fees_collection.php';
                    
                }elseif(isset($_POST['id_summary']) && !empty($_POST['id_summary']) && $_POST['id_summary'] == 6){
                    // Class-wise fee Receipt report
                    require_once 'include/financial_reports/class_fee_receipt.php';
                    
                }elseif(isset($_POST['id_summary']) && !empty($_POST['id_summary']) && $_POST['id_summary'] == 7){
                    // Class-wise fee Receipt report
                    require_once 'include/financial_reports/student_fee_receipt.php';
                    
                }

            }
            echo'
        </div>
    </body>
</html>';
?>
<!-- EXPORT TO EXCEL -->
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script type="text/javascript">
    // PRINT PAGE
    function print_report(printResult) {
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
    // EXPORT TO EXCEL
    function html_table_to_excel(type){
        var data = document.getElementById('printResult');
        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
        XLSX.writeFile(file, 'report.' + type);
    }

    const export_button = document.getElementById('export_button');
    export_button.addEventListener('click', () =>  {
        html_table_to_excel('xlsx');
    });
</script>