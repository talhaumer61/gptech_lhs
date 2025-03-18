<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_class']) && isset($_POST['id_section'])) {
    
    $sqllmsfeesetup	= $dblms->querylms("SELECT f.id, f.dated, f.id_class, f.id_section, f.id_session, f.id_campus					     
                                        FROM ".FEESETUP." f
                                        WHERE f.id_campus   = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                        AND f.id_session    = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
                                        AND f.id_class      = '".$_POST['id_class']."'
                                        AND f.id_section    = '".$_POST['id_section']."' 
                                        AND f.status        = '1'
                                        AND f.is_deleted    = '0'
                                        ORDER BY f.id DESC LIMIT 1");
    if(mysqli_num_rows($sqllmsfeesetup) > 0){

        echo'<div class="alert alert-danger"> Please select only those heads which you want to add in challan.</div>';

        $value_feesetup = mysqli_fetch_array($sqllmsfeesetup);

        // AMOUNT FROM FEE STRUCTURE 
        $sqllms	= $dblms->querylms("SELECT 	d.id, d.id_setup, d.id_cat, d.amount,
                                            c.cat_id, c.cat_name
                                            FROM ".FEESETUPDETAIL." d
                                            LEFT JOIN ".FEE_CATEGORY." c ON c.cat_id = d.id_cat												 
                                            WHERE d.id_setup = '".$value_feesetup['id']."' AND c.cat_status = '1'
                                            AND c.is_deleted != '1'
                                            ORDER BY c.cat_name ASC");
        $srno = 0;
        $amount = 0;
        $total_amount = 0;
        $tuitionFee = 0;
        $fineAmount = 0;
        $rem_amount = 0;

        if(mysqli_num_rows($sqllms)){
            echo'
            <div class="row">
                <div class="form-group">';
                    while($rowsvalues = mysqli_fetch_array($sqllms)) {
                        $srno++;
                        $amount = $rowsvalues['amount'];
                        echo'
                        <div class="col-sm-4 mt-sm">
                            <div class="checkbox-custom checkbox-inline" style="margin-top: -2px;">
                                <input type="checkbox" name="is_selected['.$srno.']" id="is_selected" value="1" checked>
                                <label for="checkboxExample1"></label>
                            </div>
                            <label class="control-label">'.$rowsvalues['cat_name'].' <span class="required">*</span></label>
                            <input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
                            <input type="number" class="form-control amount" name="amount['.$srno.']" id="amount['.$srno.']" value="'.$amount.'" required title="Must Be Required"/>
                        </div>';
                        $total_amount = $total_amount + $amount;

                        // GET TUITION FEE
                        if($rowsvalues['cat_id'] == 1){
                            $tuitionFee = $rowsvalues['amount'];
                        }
                    }
                    echo'
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label">Grand Total <span class="required">*</span></label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" value="'.$total_amount.'" readonly>
                    </div>
                </div>
            </div>';
        }
    }else{
        echo'<p class="text text-danger center">No Fee Structure Added! <br> Firstly Kindly Add Fee Details</p>';
        exit();
    }
}
?>