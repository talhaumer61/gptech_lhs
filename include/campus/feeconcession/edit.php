<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'edit' => '1'))){   
    
    // QUERY
    $sqllms	= $dblms->querylms("SELECT id, status, id_cat, id_class, id_std
                                        FROM ".SCHOLARSHIP."
                                        WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                        AND id_type = '2' AND id = '".cleanvars($_GET['id'])."'
                                        LIMIT 1");
    $rowsvalues = mysqli_fetch_array($sqllms);
    
    echo '
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-edit"></i> Update Fee Concession</h4>
        </header>
        <form action="feeconcession.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
            <div class="panel-body">                
				<div class="form-group">
                    <input type="hidden" name="id" value="'.$_GET['id'].'">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <label class="control-label">Category <span class="required">*</span></label>
                            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_cat">
                                <option value="">Select</option>';
                                    $sqllms	= $dblms->querylms("SELECT cat_id, cat_type, cat_status, cat_name 
                                                        FROM ".SCHOLARSHIP_CAT."
                                                        WHERE cat_id != '' AND cat_status = '1' AND cat_type = '2'
                                                        ORDER BY cat_name ASC");
                                    while($rowvalues = mysqli_fetch_array($sqllms)) {
                                        echo '<option value="'.$rowvalues['cat_id'].'"'; if($rowvalues['cat_id'] == $rowsvalues['id_cat']){echo'selected';} echo'>'.$rowvalues['cat_name'].'</option>';
                                    }
                                echo '
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-2">
                            <label class="control-label">Class <span class="required">*</span></label>
                            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" name="id_class" onchange="get_classstudent_fee(this.value)" disabled>
                                <option value="">Select</option>';
                                $sqllmsclass	= $dblms->querylms("SELECT class_id, class_name 
                                                                        FROM ".CLASSES." 
                                                                        WHERE class_status = '1' 
                                                                        AND class_id = '".$rowsvalues['id_class']."' LIMIT 1");
                                while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
                                    echo '<option value="'.$value_class['class_id'].'" selected>'.$value_class['class_name'].'</option>';
                                }
                                echo '
                            </select>
                        </div>
                        
                        <div id="getclassstudent">
                            <div class="col-md-4">
                                <label class="control-label">Student <span class="required">*</span></label>
                                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_std" name="id_std" disabled>
                                    <option value="">Select </option>';
                                    $sqllmsstudent	= $dblms->querylms("SELECT std_id, std_name 
                                                                            FROM ".STUDENTS."
                                                                            WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                            AND std_id = '".$rowsvalues['id_std']."' LIMIT 1");
                                    while($value_stu = mysqli_fetch_array($sqllmsstudent)) {
                                        echo'<option value="'.$value_stu['std_id'].'|'.$class.'" selected>'.$value_stu['std_name'].'</option>';
                                    }
                                    echo '
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class=" col-md-offset-2 col-md-3">
                        <label class="control-label mr-md">Status <span class="required">*</span></label>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="status" name="status" value="1"'; if($rowsvalues['status'] == 1){echo'checked';} echo'>
                            <label for="radioExample1">Active</label>
                        </div>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="status" name="status" value="2"'; if($rowsvalues['status'] == 2){echo'checked';} echo'>
                            <label for="radioExample2">Inactive</label>
                        </div>
                    </div>
                </div>

                <br>
                        
                <div id="getfeeamount">
                    <table class="table table-hover table-striped table-condensed mb-none">
                        <thead>
                            <tr>
                                <th class="center">Sr NO</th>
                                <th>Fee Category</th>
                                <th>Category Total</th>
                                <th>Category Type</th>
                                <th width="30%">Concession</th>
                                <th width="20%">Duration</th>
                            </tr>
                        </thead>
                        <tbody>';
                                
                                // Transport Fee
                                $sqllmsTrans  = $dblms->querylms("SELECT transport_fee
                                                                    FROM ".STUDENTS."
                                                                     WHERE std_id = '".$rowsvalues['id_std']."'
                                                                     AND f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
                                $valTransport = mysqli_fetch_array($sqllmsTrans);

                                // Fee STructure
                                $sqllmsSetup  = $dblms->querylms("SELECT id
                                                                    FROM ".FEESETUP."
                                                                     WHERE id_class = '".$rowsvalues['id_class']."'
                                                                     AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                                                     AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                                                     ORDER BY id DESC LIMIT 1");
                                $valSetup = mysqli_fetch_array($sqllmsSetup);

                                // Concession Detail according to cats                         
                                $sqllmsCats  = $dblms->querylms("SELECT c.cat_id, c.cat_name, d.amount
                                                                    FROM ".FEE_CATEGORY." c
                                                                    INNER JOIN ".FEESETUPDETAIL." d ON d.id_cat = c.cat_id	 	
                                                                     WHERE c.cat_status = '1' AND d.id_setup = 	'".$valSetup['id']."'
                                                                     ORDER BY c.cat_name ASC");
                                $srno = 0;
                                while($valCats = mysqli_fetch_array($sqllmsCats)) {
                                    
                                    $sqllmsDet  = $dblms->querylms("SELECT from_month, to_month, amount, percent  
                                                                        FROM ".CONCESSION_DETAIL."
                                                                        WHERE id_fee_cat = '".$valCats['cat_id']."' 
                                                                        AND id_setup = '".$rowsvalues['id']."'
                                                                        LIMIT 1");
                                    $valDet = mysqli_fetch_array($sqllmsDet);

                                    $srno++;
                                    if(empty($valDet['from_month']) && empty($valDet['to_month'])) { 
                                        $forSession = 'selected';
                                        $forCustom = '';
                                    } else {
                                        $forSession = '';
                                        $forCustom = 'selected';
                                    }
                                    

                                    if($valCats['cat_id'] == 5) {
                                        $cat_amount = $valTransport['transport_fee'];
                                    } else {
                                        $cat_amount = $valCats['amount'];
                                    }
                                    
                                    echo '
                                    <tr>
                                        <td class="center">'.$srno.'</td>
                                        <td>
                                            '.$valCats['cat_name'].'
                                            <input type="hidden" name="id_fee_cat['.$srno.']" id="id_fee_cat['.$srno.']" value="'.$valCats['cat_id'].'">
                                        </td>
                                        <td>
                                            <div class="form-group mt-sm">
                                                <div class="col-md-12 mt-md">
                                                    <input type="number" class="form-control" name="cat_amount['.$srno.']" id="cat_amount'.$srno.'" value="'.$cat_amount.'" readonly>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group mt-sm">
                                                <div class="col-md-12 mt-md">
                                                    <select class="form-control" name="duration[]" id="duration[]" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="get_concession_fields'.$srno.'(this.value)">
                                                        <option vlaue="">Select</option>
                                                        <option value="1|'.$srno.'" selected>Percentage</option>
                                                        <option value="2|'.$srno.'">Amount</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group" id="getconcessiontype'.$srno.'">
                                                <label class="col-md-3 control-label">Percentage </label>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" name="con_percent['.$srno.']" id="con_percent'.$srno.'" min="0" max="100" value="'.$valDet['percent'].'" title="Amount should be greater than 0 & less than 100"/>
                                                </div>
                                                <label class="col-md-3 control-label">Amount </label>
                                                <div class="col-md-9 mt-xs">
                                                    <input type="number" class="form-control" name="con_amount['.$srno.']" id="con_amount'.$srno.'" value="'.$valDet['amount'].'" readonly/>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="col-md-12 mb-xs">
                                                    <select class="form-control" name="duration['.$srno.']" id="duration['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="get_duration_fields'.$srno.'(this.value)">
                                                        <option vlaue="">Select</option>
                                                        <option value="1|'.$srno.'" '.$forSession.'>Whole Session</option>
                                                        <option value="2|'.$srno.'" '.$forCustom.'>Custom</option>
                                                    </select>
                                                </div>
                                                <div id="getDuration'.$srno.'">'; 
                                                    if($forCustom == 'selected') {
                                                        echo' 
                                                        <div class="form-group mt-sm">
                                                            <div class="col-md-12">
                                                                <div class="col-md-6">
                                                                    <select class="form-control" name="from['.$srno.']" id="from'.$srno.'" required title="Must Be Required">
                                                                        <option value="0">From</option>';
                                                                        foreach($monthtypes as $months) {
                                                                            echo'<option value="'.$months['id'].'"'; if($valDet['from_month'] == $months['id']){ echo'selected';} echo'>'.$months['name'].'</option>';
                                                                        }
                                                                        echo'
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <select class="form-control" name="to['.$srno.']" id="to'.$srno.'" required title="Must Be Required">
                                                                        <option value="0">To</option>';
                                                                        foreach($monthtypes as $months) {
                                                                            echo'<option value="'.$months['id'].'"'; if($valDet['to_month'] == $months['id']){ echo'selected'; } echo'>'.$months['name'].'</option>';
                                                                        }
                                                                        echo'
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>';
                                                    }
                                                    echo'
                                                </div>
                                            </div>
                                        </td>
                                    </tr> ';?> 
                                    
                                    <script type="text/javascript">
                                        
                                        var grandTotal = 0;
                                    
                                        function get_concession_fields<?php echo $srno; ?>(id_type) {  
                                            $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
                                            $.ajax({
                                                type: "POST",  
                                                url: "include/ajax/get_classstudent_fee.php",  
                                                data: "id_type="+id_type,
                                                success: function(msg){
                                                    $("#getconcessiontype<?php echo $srno; ?>").html(msg);
                                                    $("#loading").html(""); 
                                                }
                                            });                   
                                        }
                                        
                                        // DURATION
                                        function get_duration_fields<?php echo $srno; ?>(duration) {  
                                            $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
                                            $.ajax({
                                                type: "POST",  
                                                url: "include/ajax/get_classstudent_fee.php",  
                                                data: "duration="+duration,
                                                success: function(msg){
                                                    $("#getDuration<?php echo $srno; ?>").html(msg);
                                                    $("#loading").html(""); 
                                                }
                                            });  
                                        }
                                        <?php
                                        echo'
                                        //Calculate Concession Amount
                                        $(document).on("input", "#con_percent'.$srno.'", function() {
                                            var percentage = document.getElementById("con_percent'.$srno.'").value;
                                            var catAmmount = document.getElementById("cat_amount'.$srno.'").value;
                                            concession = (percentage *  catAmmount) / 100;
                                            $("#con_amount'.$srno.'").val(concession);

                                        });
                                    </script> ';
                                }
                                echo '
                            </div>
                        </tbody>
                    </table>
                </div>
                    
                </div>
        
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary" id="update_feeconcession" name="update_feeconcession">Update</button>
                            <a href="feeconcession.php" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </footer>
            </div>
        </form>
    </section>';
} else {
	header("Location: feesetup.php");
}
?>
