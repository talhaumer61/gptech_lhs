<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
require_once("api/db_functions.php");
$dblms 	= new dblms();
$api 	= new main();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

if(isset($_GET['id'])) {

	$order = $api->get_returndetail($_GET['id']);
	
	$contactperson = '';
	if($order['customer']['contactperson']){
		$contactperson = '<br>'.$order['customer']['contactperson'].' '.$order['customer']['customer_cellno'];
	} else {
		$contactperson = '<br>'.$order['customer']['customer_cellno'];
	}
		
	$customerAddress = '';
	if($order['customer']['customer_address']){
		$customerAddress = '<br>'.$order['customer']['customer_address'];
	}
	
	//echo $order['customer']['dated'];
	echo '
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sale Return Print</title>
<style type="text/css">
body {
	font: normal normal normal 16px/15px  "Calibri", "Lucida Grande", "Lucida Sans Unicode", Helvetica, Arial, Verdana, sans-serif;
    overflow: -moz-scrollbars-vertical; margin:0; color:#111;
}
h1 { font-size:28px; font-weight:bold; margin-top:10px; color:#111; text-transform:uppercase; }
h2 { font-size:20px; font-weight:bold; margin-bottom:3px; color:#111; text-transform:uppercase; text-align:center; text-decoration:underline; }
h3 { font-size:18px; font-weight:bold; color:#333; text-transform:uppercase; text-align:center; }
h4 { font-size:14px; font-weight:bold; margin-bottom:5px; margin-top:5px; color:#333; }

table td.label { font-weight:bold; margin: 0; text-align:left; padding:2px; vertical-align:middle !important; }

table.datatable { border: 1px solid #333; border-collapse: collapse; border-spacing: 0; margin-top:10px; }

table.datatable td { 
	border: 1px solid #888; border-collapse:collapse; border-spacing: 0; padding:5px; 
	font-family: Calibri, "Calibri Light", Arial, Helvetica, sans-serif; font-size:15px; color:#000; 
}

table.datatable th { 
	border: 1px solid #888; border-collapse:collapse; border-spacing: 0; padding:7px; background:#f4f4f4; 
	font-family: Calibri, "Calibri Light", Arial, Helvetica, sans-serif; font-size:14px;  font-weight:600; 
}

.table-blue-fotter{
    position: static;
    bottom: 0px;/* for bottom gap */
    left: 0px;
    width:100%;
}

@page {

        margin-bottom: 20px;
        counter-increment: page;

        @bottom-right {
            border-top: 1px solid #000000;
            padding-right:20px;
            font-size: 12px !important;
            content: "Page " counter(page) " of " counter(pages);
        }
        @bottom-left {
            content: "Footer content goes here.";
            border-top: 1px solid #000000;
        }

    }
@media print {
  .table-blue-fotter {
    position: static; /* <-- Key line */
    bottom: 0px;
    left: 0px;
    width: 100%;
  }


</style>
<link rel="shortcut icon" href="https://msd.gptech.pk/images/favicon/favicon.ico">
<script language="JavaScript1.2">
function openwindow() {
	window.open("ordersprint.php", "feechallan","toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,directories=no,status=no,width=800,height=700");
}
</script>
</head>

<body>
<center>
<div style="width:700px !important; height:700px !important; " class="page">
<table width="700" height="80" border="0" cellpadding="5" cellspacing="9" style="border-collapse:collapse;">

<tr>
	<td width="58%" rowspan="5" valign="top">
		<img src="'.$order['customer']['invoicelogo'].'" style="width:80; height:75px; margin-top:0px;margin-right:10px" align="left"> 
		<h3 style="margin-top:10px;margin-bottom:0px;text-align:left;font-weight:900;">'.$order['customer']['invoiceheading'].'</h3>
		<h4 style="margin-top:10px;font-weight:400; width:90%;">'.$order['customer']['companyaddress'].'</h4>
	</td>
	<td width="25%" colspan="2" style="text-align:right; font-size:30px; font-weight:700;letter-spacing:3px; padding-right:0;">
	<div style="text-align:right; font-size:9px; padding-bottom:3px; color:#888;">'.date("F j, Y h:iA").'</div>
		SALE RETURN
	</td>
</tr>
<tr>
	<td colspan="2" height="2"> </td>
</tr>
<tr>
	<td height="10" style="border:1px solid #111; text-align:center; font-size:14px;font-weight:700;padding:0px;padding-right:3px;">
		Dated: 
	</td>
	<td height="10" style="border:1px solid #111; text-align:center; font-size:14px; font-weight:700; padding:0px; padding-right:3px;">
		J.O. No: 
	</td>
	
	
</tr>
<tr>
	<td style="border:1px solid #111; text-align:center; font-size:12px;padding:3px;">
		'.date("F j, Y", strtotime($order['customer']['dated'])).'
	</td>
	<td style="border:1px solid #111; text-align:center; font-size:14px;padding:3px;">
		'.$order['customer']['order_no'].'
	</td>
</tr>
</table>



<table  style="float:left;width: 400px;border-collapse:collapse;margin-top:10px" align="left" border="0"  >
<tr>
	<td height="10" style="border:1px solid #111; text-align:left; font-size:14px;font-weight:700;padding:8px;padding-right:3px;">
		Name/Address 
	</td>
	
</tr>
<tr>
	<td style="border:1px solid #111; height:40px; text-align:left;font-size:14px;padding:8px;margin-top:0px;">
		'.$order['customer']['customer_name'].' '.$contactperson.$customerAddress.'<br>
	</td>
	
</tr>
</table>
<table  style="float:right;width:212px;border:1px solid #111;border-collapse:collapse;margin-top:10px" align="right" border="0"  >
<!----<tr>
	<td height="10" style="border:1px solid #111; text-align:left; font-size:14px;font-weight:700;padding:8px;padding-right:3px;">
		Name/Address 
	</td>
	
</tr>---->
<tr>
	<td style="text-align:left;  font-size:12px;padding:8px;margin-top:0px;">
		<span>Receipt # </span>
	</td>
	
</tr>
<tr>
	<td style="text-align:left;  font-size:12px;padding:8px;margin-top:0px;">
		<span>Book # </span>
	</td>
	
</tr>
<tr>
	<td style="text-align:left;  font-size:12px;padding:8px;margin-top:0px;">
		<span> Date: # </span>
	</td>
	
</tr>
</table>
<table  style="width: 100%; border: 0px  dotted #000;">
<tr>
	<td align="left" valign="top" colspan="2">

<table class="datatable" style="margin:3px 0; width:100%;">
<thead>
<tr>
	<th style="text-align:center; font-weight:bold;">Sr.#</th>
	<th style="text-align:center; font-weight:bold;">Item Code</th>
	<th style="text-align:center; font-weight:bold;">Item Name</th>
	<th style="text-align:center; font-weight:bold;">QTY</th>
	<th style="text-align:center; font-weight:bold;">Unit Price</th>
	<th style="text-align:center; font-weight:bold;">Total</th>
</tr>
</thead>';
	$srno  = 0;
	$totalqty  = 0;
	foreach($order['orderdetail'] as $rowbills) {
//------------------------------------------------
		$srno++;
		$unitPrice = $rowbills['unit_price'];	
//------------------------------------------------
echo '
<tr>
	<td style="text-align:center; width:40px;">'.$srno.'</td>
	<td style="text-align:center; width:65px;">'.$rowbills['item_code'].'</td>
	<td style="text-align:left;">'.$rowbills['item_name'].'</td>
	<td style="text-align:center; width:40px;">
		'.number_format($rowbills['total_qty']).'
	</td>
	<td style="text-align:right; width:65px;">
		'.number_format($unitPrice).'
	</td>
	<td style="text-align:right; width:65px;">
		'.number_format($rowbills['total_price']).'
	</td>
</tr>';

$totalqty = ($totalqty + $rowbills['total_qty']);
//------------------------------------------------
} // end while loop
//--------------------------------------------------
if(count($order['orderdetail']) <14) { 
//------------------------------------------------
for($ij = count($order['orderdetail']); $ij <=14; $ij++) { 
//------------------------------------------------
echo '
<tr>
	<td style="text-align:center;">&nbsp;</td>
	<td style="text-align:right;">&nbsp;</td>
	<td style="text-align:right;">&nbsp;</td>
	<td style="text-align:right;">&nbsp;</td>
	<td style="text-align:right;">&nbsp;</td>
	<td style="text-align:right;">&nbsp;</td>
</tr>';
//------------------------------------------------
}
}
echo '

<tr>
	<th colspan="4" style="text-align:center;">Total</th>
	<th colspan="2" style="text-align:right;">'.number_format($order['customer']['total']).'</th>
</tr>
</table>
<table class="datatable" style="margin:10px 0 3px 0; width:350px; float:right;">
<tr>
	<th style="font-weight:700; text-align:right; border-bottom:0 !important;">Deducation %: </th>
	<th style="font-weight:700;text-align:right; width:50%;">'.number_format($order['customer']['deducation_per']).' </th>
</tr>
<tr>
	<td style="font-weight:700; text-align:right; border-bottom:0 !important;">Deducation Amount: </td>
	<td style="font-weight:700;text-align:right; width:50%;">'.number_format(($order['customer']['deducation_amount'])).' </td>
</tr>
<tr>
<tr>
	<th style="font-weight:700; text-align:right; border-bottom:0 !important;">Grand Total: </th>
	<th style="font-weight:700;text-align:right; width:50%;">'.number_format(($order['customer']['grand_total'])).' </th>
</tr>
</table>
	</td>
</tr>
<tr>
  <td valign="bottom" align="left">
<table  border="0" style="margin:100px 0 0 0; width:250px;">
<tr>
	<td align="center" style="font-weight:500;">'.$_SESSION['userlogininfo']['LOGINNAME'].'</td>
</tr>
<tr>
	<td align="center" style="font-weight:500; border-top:1px solid #111;">Prepared By</td>
</tr>
</table>
	</td>
	<td valign="bottom" align="right">
<table  border="0" style="margin:20px 0 0 0; width:130px;">
<tr>
	<td align="center" style="font-weight:500; border-top:1px solid #111;">Director</td>
</tr>
</table>

	</td>
</tr>
</table>
<table class="table-blue-fotter">
  <tr>
    <td>
<div style="border-top:2px solid #000000; width:100%; font-size:13px; text-align:center;">
		<h3 style="margin-bottom:3px; margin-top:5px;">Thank you for your business!</h3>
		<span style="">Aghosh Complex, Near Civic Center, Shah e Jilani Road, Township Lahore.</span>
		<div style="margin-bottom:15px; font-size:14px; text-align:left; ">
			<a href="https://gptech.pk" style="text-decoration:none;" target="_blank">Powered by: Green Professional Technologies</a>
			<span style="font-size:12px; float:right; margin-top:3px;margin-left:30px;">Printed date: '.date("m/d/Y").'</span> 
		</div>
	</div>
</div>
</td>
</tr>
</table>';
}
