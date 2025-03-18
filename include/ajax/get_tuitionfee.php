<?php
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
//--------------------------------------------
$sqllmsfeesetup	= $dblms->querylms("SELECT f.id, d.amount
                                        FROM ".FEESETUP." f
                                        INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = f.id	
                                        WHERE f.status = '1' AND f.is_deleted != '1' 
                                        AND f.id_campus = '".$_POST['camp']."' AND f.id_class = '".$_POST['cls']."'
                                        AND d.id_cat = '1' LIMIT 1");
$valTuitionFee = mysqli_fetch_array($sqllmsfeesetup);

$amount = ($valTuitionFee['amount'] * $_POST['percentage']) / 100;
// echo $amount;
echo'<input type="number" class="form-control amount" required name="amount[]" id="amount'.$_POST['srno'].'" placeholder="testr" value="'.$amount.'" readonly/>';
?>
