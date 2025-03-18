<?php
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
//--------------------------------------------
    // Students Against Class
    if(isset($_POST['id_class'])) {
        $class = $_POST['id_class']; 

        echo '
        <div class="col-md-4">
            <label class="control-label">Student <span class="required">*</span></label>
            <select class="form-control populate" data-plugin-selectTwo data-width="100%" id="id_std" name="id_std" required title="Must Be Required" onchange="get_feecat_amount(this.value)">
                <option value="">Select</option>';
                $sqllmsstudent	= $dblms->querylms("SELECT std_id, std_name, transport_fee 
                                                        FROM ".STUDENTS."
                                                        WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                        AND std_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
                                                        ORDER BY std_name ASC");
                while($value_stu = mysqli_fetch_array($sqllmsstudent)) {
                    echo'<option value="'.$value_stu['std_id'].'|'.$value_stu['transport_fee'].'|'.$class.'">'.$value_stu['std_name'].'</option>';
                }
                echo '
            </select>
            </div>
        </div>';
    }

    if(isset($_POST['id_std'])) {

        $value = explode("|", $_POST['id_std']);
        $std     = $value[0];
        $tarns_fee = $value[1];
        $class   = $value[2];

        // Query To Amount Against Every Cat
        $sqllmsFee  = $dblms->querylms("SELECT f.id
                                            FROM ".FEESETUP." f 	
                                            INNER JOIN ".STUDENTS." s ON s.id_section = f.id_section
                                            WHERE f.status = '1' AND s.std_id = '".$std."' AND f.id_class = '".$class."'
                                            AND f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                            ORDER BY f.id DESC LIMIT 1");
        if(mysqli_num_rows($sqllmsFee) > 0) {      
            $valFee = mysqli_fetch_array($sqllmsFee);
        
            echo'
            <table class="table table-hover table-striped table-condensed mb-none">
                <thead>
                    <tr>
                        <th>Fee Category</th>
                        <th>Category Total</th>
                        <th>Category Type</th>
                        <th width="30%">Concession</th>
                        <th width="20%">Duration</th>
                    </tr>
                </thead>
                <tbody>';
                    $srno = 0;
                    
                    $sqllmsCats  = $dblms->querylms("SELECT c.cat_id, c.cat_name, d.amount
                                                            FROM ".FEE_CATEGORY." c
                                                            INNER JOIN ".FEESETUPDETAIL." d ON d.id_cat = c.cat_id	
                                                            WHERE c.cat_status = '1' AND d.id_setup = '".$valFee['id']."'
                                                            ORDER BY c.cat_name ");

                    while($valCats = mysqli_fetch_array($sqllmsCats)) {
                        $srno++;

                        if($valCats['cat_id'] == 5) {
                            $cat_amount = $tarns_fee;
                        } else {
                            $cat_amount = $valCats['amount'];
                        }

                        echo '
                        <tr class="align-items-cente">
                            <td >
                                '.$valCats['cat_name'].'
                                <input type="hidden" name="id_fee_cat['.$srno.']" id="id_fee_cat['.$srno.']" value="'.$valCats['cat_id'].'">
                            </td>
                            <td>
                                <div class="form-group mt-sm">
                                    <div class="col-md-12 mt-md">
                                        <input type="number" class="form-control" name="cat_amount['.$srno.']" id="cat_amount'.$srno.'" value="'.$cat_amount.'" readonly/>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group mt-sm">
                                    <div class="col-md-12 mt-md">
                                        <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_type[]" id="id_type" onchange="get_concession_fields'.$srno.'(this.value)">
                                            <option value="1|'.$srno.'">Percentage</option>
                                            <option value="2|'.$srno.'">Amount</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" id="getconcessiontype'.$srno.'">
                                    <label class="col-md-3 control-label">Percentage </label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="con_percent['.$srno.']" id="con_percent'.$srno.'" min="0" max="100"/>
                                    </div>
                                    <label class="col-md-3 control-label">Amount </label>
                                    <div class="col-md-9 mt-xs">
                                        <input type="number" class="form-control" name="con_amount['.$srno.']" id="con_amount'.$srno.'" readonly/>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <div class="col-md-12 mb-xs">
                                        <select class="form-control" name="duration['.$srno.']" id="duration['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="get_duration_fields'.$srno.'(this.value)">
                                            <option vlaue="">Select</option>
                                            <option value="1|'.$srno.'" selected>Whole Session</option>
                                            <option value="2|'.$srno.'">Custom</option>
                                        </select>
                                    </div>
                                    <div id="getDuration'.$srno.'"></div>
                                </div>
                            </td>
                        </tr>';?>
                                            
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
                    echo'
                </tbody>
            </table>';
        } else {

            echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Fee Structure Found!</h2></div>';

        }
    }

    // Input Fields For Concession
    if(isset($_POST['id_type'])) {
        
        $value = explode("|", $_POST['id_type']);
        $type = $value[0];
        $sr = $value[1];

        if($type == 2) {

            echo'
            <label class="col-md-3 control-label">Amount</label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="con_amount['.$sr.']" id="con_amount'.$sr.'" min="0"/>
            </div>
            <label class="col-md-3 control-label">Percentage</label>
            <div class="col-md-9 mt-xs">
                <input type="number" class="form-control" name="con_percent['.$sr.']" id="con_percent'.$sr.'" min="0" max="100" title="Amount should be greater than 0 & less than 100" readonly/>
            </div>
            
            <script type="text/javascript">
            
                var grandTotal = 0;

                //Calculate Concession Percentage
                $(document).on("input", "#con_amount'.$sr.'", function() {
                    var amount = document.getElementById("con_amount'.$sr.'").value;
                    var catAmmount = document.getElementById("cat_amount'.$sr.'").value;
                    concession = (amount /  catAmmount) * 100;
                    $("#con_percent'.$sr.'").val(concession);
                });
                
            </script>';


        } else {

            echo'
            <label class="col-md-3 control-label">Percentage </label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="con_percent['.$sr.']" id="con_percent'.$sr.'" min="0" max="100" title="Amount should be greater than 0 & less than 100"/>
            </div>
            <label class="col-md-3 control-label">Amount </label>
            <div class="col-md-9 mt-xs">
                <input type="number" class="form-control" name="con_amount['.$sr.']" id="con_amount'.$sr.'" readonly/>
            </div>
            
            <script type="text/javascript">

                //Calculate Concession Amount
                $(document).on("input", "#con_percent'.$sr.'", function() {
                    var percentage = document.getElementById("con_percent'.$sr.'").value;
                    var catAmmount = document.getElementById("cat_amount'.$sr.'").value;
                    concession = (percentage *  catAmmount) / 100;
                    $("#con_amount'.$sr.'").val(concession);
                });
            
            </script>';

        }
    }

    // Input Fields Duration
    if(isset($_POST['duration'])) {

        $value = explode("|", $_POST['duration']);
        $type = $value[0];
        $sr = $value[1];

        if($type == 2) {

            echo'
            <div class="form-group mt-sm">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <select class="form-control" name="from['.$sr.']" id="from'.$sr.'" required title="Must Be Required">
                            <option>From</option>';
                            foreach($monthtypes as $months) {
                                echo'<option value="'.$months['id'].'">'.$months['name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" name="to['.$sr.']" id="to'.$sr.'" required title="Must Be Required">
                            <option>To</option>';
                            foreach($monthtypes as $months) {
                                echo'<option value="'.$months['id'].'">'.$months['name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                </div>
            </div>';

        } else {


        }
    }

?>