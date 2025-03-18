<?php

require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

$count = 0;
//Variefy Total adn Particulars Amount
$sqllmsChallan = $dblms->querylms("SELECT id, status, challan_no, total_amount, scholarship, concession, fine, prev_remaining_amount, remaining_amount
                                        FROM ".FEES."			   
                                        WHERE is_deleted != '1' AND id_type IN (1,2) 
                                        ORDER BY id ASC");
                                        $count = 0;
while($rowMain = mysqli_fetch_array($sqllmsChallan)) {

    //All Part
    $sqllmsDetail	= $dblms->querylms("SELECT SUM(amount) as total
													FROM ".FEE_PARTICULARS."
													WHERE id_fee = '".cleanvars($rowMain['id'])."' ");
    $rowPart = mysqli_fetch_array($sqllmsDetail);

    //Concession
    // $sqllmsDetailConcess	= $dblms->querylms("SELECT amount
	// 												FROM ".FEE_PARTICULARS."
	// 												WHERE id_fee = '".cleanvars($rowMain['id'])."'
    //                                                 AND id_cat = '17' ");
    // $rowConcession = mysqli_fetch_array($sqllmsDetailConcess);

    // $partAmount = $rowPart['total'] - (2 * $rowConcession['amount']);
    // $partAmount = $rowPart['total'] - ($rowConcession['amount']);

    $particularsAmount = ($rowPart['total'] + $rowMain['prev_remaining_amount'] + $rowMain['fine']) - ($rowMain['scholarship'] + $rowMain['concession']);

    if($rowMain['total_amount'] != $particularsAmount) {
        $count ++;
        echo $count."::: stat: ".$rowMain['status']."TotalMain: ".$rowMain['total_amount']." Total Part: ".$rowPart['total']."  == ChallanNo: ".$rowMain['challan_no']."<br><br>";
    } else {
        continue;
    }

}
?>