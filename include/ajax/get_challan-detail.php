<?php
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
//--------------------------------------------

// Student Against Class
if(isset($_POST['id_class'])) {
	$class = $_POST['id_class']; 
    echo '
    <select class="form-control populate" data-plugin-selectTwo data-width="100%" id="id_std" name="id_std" required title="Must Be Required" onchange="get_mothdetail(this.value)">
        <option value="">Select</option>';
            $sqllmsstudent	= $dblms->querylms("SELECT std_id, std_name , id_section
                                FROM ".STUDENTS."
                                WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                AND std_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
                                ORDER BY std_name ASC");
            while($value_stu = mysqli_fetch_array($sqllmsstudent)) {
                echo '<option value="'.$value_stu['std_id'].'">'.$value_stu['std_name'].'</option>';
            }
    echo '
    </select>';
}
// month Fileds
else if(isset($_POST['id_std']))  {

   $std = $_POST['id_std'];

    echo'
    <select data-plugin-selectTwo data-width="100%" name="id_month" id="id_month" required title="Must Be Required" class="form-control populate" onchange="get_challandetail(this.value)">						
        <option value="">Select</option>';
            foreach($monthtypes as $month){
                echo'<option value="'.$month['id'].'|'.$std.'">'.$month['name'].'</option>';
            }
            echo '
    </select>';
}

// Challan Details
else if(isset($_POST['id_month']))  {
    
    $value = explode("|", $_POST['id_month']);
    $id_month = $value[0];
    $id_std = $value[1];

    // STUDENT DETAILS
    $sqllmstu	= $dblms->querylms("SELECT std_id, id_class, id_section, std_phone, transport_fee
                                        FROM ".STUDENTS."
                                        WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                        AND std_status = '1'  AND is_deleted != '1' AND std_id = '".$id_std."' LIMIT 1");
    if(mysqli_num_rows($sqllmstu) == 1) {

        $value_stu = mysqli_fetch_array($sqllmstu);

        $sqllmsfeesetup	= $dblms->querylms("SELECT f.id, f.dated, f.id_class, f.id_section, f.id_session, f.id_campus					     
                                            FROM ".FEESETUP." f
                                            WHERE f.is_deleted != '1'
                                            AND f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                            AND f.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
                                            AND f.status = '1' AND f.id_class = '".$value_stu['id_class']."' AND f.id_section = '".$value_stu['id_section']."' 
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
                                                AND c.cat_id NOT IN(3,4)
                                                AND c.is_deleted != '1'
                                                ORDER BY c.cat_name ASC");
            $srno = 0;
            $amount = 0;
            $total_amount = 0;
            $tuitionFee = 0;
            $fineAmount = 0;
            $rem_amount = 0;

            while($rowsvalues = mysqli_fetch_array($sqllms)) {
                $srno++;

                // GET EACH CAT AMOUNT
                if($rowsvalues['cat_id'] == 5){ 
                    $cat_amount = $value_stu['transport_fee'];
                } else {
                    $cat_amount = $rowsvalues['amount'];
                }

                // GET CONCESSION AGAINST SPECIFIC CAT
                $sqllmsCon	= $dblms->querylms("SELECT d.amount, d.percent, d.from_month, d.to_month
                                                FROM ".SCHOLARSHIP." c
                                                INNER JOIN ".CONCESSION_DETAIL." d ON d.id_setup = c.id
                                                WHERE c.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                                AND c.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
                                                AND c.id_class = '".$value_stu['id_class']."' AND c.id_std = '".$id_std."'
                                                AND c.status = '1' AND c.id_type = '2' AND c.is_deleted != '1'
                                                AND d.id_fee_cat = '".$rowsvalues['cat_id']."'
                                                LIMIT 1");
                if(mysqli_num_rows($sqllmsCon) > 0) {

                    // AND d.from_month <= $id_month AND d.to_month >= $id_month
                    $valCon = mysqli_fetch_array($sqllmsCon);
                    // Conditions On which Concession Apply
                    if(
                        ($id_month >= $valCon['from_month']  && $id_month <= $valCon['to_month'] )  
                        ||
                        (empty($valCon['from_month']) && empty($valCon['to_month']))
                    ) {
                        $conc_amount = ($valCon['percent'] * $cat_amount) / 100;
                        $cat_conc_amount = ($cat_amount - $conc_amount);
                    } else {
                        $conc_amount = 0;
                        $cat_conc_amount = $cat_amount;
                    }
                    
                } else {
                    $conc_amount = 0;
                    $cat_conc_amount = $cat_amount;

                }

                echo '
                <div>
                    <div class="col-sm-4">
                        <div class="form-group mr-xs">';
                            // if($rowsvalues['cat_id'] != 1){
                                echo'
                                <div class="checkbox-custom checkbox-inline" style="margin-top: -2px;">
                                    <input type="checkbox" name="is_selected['.$srno.']" id="is_selected" value="1" checked>
                                    <label for="checkboxExample1"></label>
                                </div>';
                            // }

                            echo'
                            <label class="control-label">'.$rowsvalues['cat_name'].' <span class="required">*</span></label>
                            <input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
                            <input type="hidden" name="concession['.$srno.']" id="concession['.$srno.']" value="'.$conc_amount.'">
                            <input type="text" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']" value="'.$cat_conc_amount.'" required title="Must Be Required"'; if($rowsvalues['cat_id'] != 7){echo'readonly';} echo'/>';
                            
                            $amount = $rowsvalues['amount'];

                            echo'
                        </div>
                    </div>
                </div>';
                $total_amount = $total_amount + $amount;

                // GET TUITION FEE
                if($rowsvalues['cat_id'] == 1){
                    $tuitionFee = $rowsvalues['amount'];
                }
            }
        
            // Remaining Amount
            $sqllms_rem = $dblms->querylms("SELECT remaining_amount FROM ".FEES." 
                                                WHERE id_std = '".cleanvars($id_std)."'
                                                AND is_deleted != '1'
                                                ORDER BY id DESC LIMIT 1");
            if(mysqli_num_rows($sqllms_rem) > 0){
                $row_rem = mysqli_fetch_array($sqllms_rem);
                $rem_amount = $row_rem['remaining_amount'];
                $allowEdit = "readonly"; 
            }
            else{
                $allowEdit = "";
                $rem_amount = 0;
            }
            
			// Fine
			$month = $id_month - 1;
			$sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
												FROM ".SCHOLARSHIP." 
												WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
												AND id_type = '3' AND status = '1' AND is_deleted != '1'
												AND id_std = '".$id_std."' AND challan_no = ''
												AND  MONTH(date) IN ('".$month."', '".$id_month."') ");
			$values_fine = 	mysqli_fetch_array($sql_fine);
            echo'
            <div>
                <div class="col-sm-4">
                    <div class="form-group mr-xs">
                        <label class="control-label">Fine <span class="required">*</span></label>
                        <input type="text" class="form-control text-danger"  name="fine" id="fine" value="'.$values_fine['fine'].'" readonly/>
                    </div>
                </div>
            </div>
            <div>
                <div class="col-sm-4">
                    <div class="form-group mr-xs">
                        <label class="control-label">Previous Balance <span class="required">*</span></label>
                        <input type="text" class="form-control text-danger"  name="prev_remaining_amount" id="prev_remaining_amount" value="'.$rem_amount.'" '.$allowEdit.' />
                    </div>
                </div>
            </div>
            <input type="hidden" name="total_amount" value="'.$total_amount.'">
            <input type="hidden" name="id_month" value="'.$id_month.'" />
            <input type="hidden" name="std_phone" value="'.$value_stu['std_phone'].'" />
            <input type="hidden" name="id_class" value="'.$value_stu['id_class'].'" />
            <input type="hidden" name="id_section" value="'.$value_stu['id_section'].'" />';

        } else {
            echo'<p class="text text-danger center">No Fee Structure Added! <br> Firstly Kindly Add Fee Details</p>';
            exit();
        }
    }

    echo"";

}

else {

}

?>