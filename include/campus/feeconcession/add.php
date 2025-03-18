<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'add' => '1'))){   
    echo '
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Class Fee Concession</h4>
        </header>
        <form action="feeconcession.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
            <div class="panel-body">                
				<div class="form-group">
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
                                        echo '<option value="'.$rowvalues['cat_id'].'">'.$rowvalues['cat_name'].'</option>';
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
                            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" name="id_class" onchange="get_classstudent_fee(this.value)">
                                <option value="">Select</option>';
                                $sqllmsclass	= $dblms->querylms("SELECT class_id, class_name 
                                                                        FROM ".CLASSES." 
                                                                        WHERE class_status = '1' ORDER BY class_id ASC");
                                while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
                                    echo '<option value="'.$value_class['class_id'].'">'.$value_class['class_name'].'</option>';
                                }
                                echo '
                            </select>
                        </div>
                        
                        <div id="getclassstudent">
                            <div class="col-md-4">
                                <label class="control-label">Student <span class="required">*</span></label>
                                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_std" name="id_std">
                                    <option value="">Select </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class=" col-md-offset-2 col-md-3">
                        <label class="control-label mr-md">Status <span class="required">*</span></label>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="status" name="status" value="1" checked>
                            <label for="radioExample1">Active</label>
                        </div>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="status" name="status" value="2">
                            <label for="radioExample2">Inactive</label>
                        </div>
                    </div>
                </div>

                <br>
                        
                <div id="getfeeamount">
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
                                $sqllmsCats  = $dblms->querylms("SELECT cat_id, cat_name  
                                                                    FROM ".FEE_CATEGORY."
                                                                    WHERE cat_status = '1' 
                                                                    ORDER BY cat_name ASC");
                                $srno = 0;
                                while($valCats = mysqli_fetch_array($sqllmsCats)) {
                                    $srno++;
                                    echo '
                                    <input type="hidden" name="id_fee_cat['.$srno.']" id="id_fee_cat['.$srno.']" value="'.$valCats['cat_id'].'">
                                    <tr>
                                        <td >'.$valCats['cat_name'].'</td>
                                        <td>
                                            <div class="form-group mt-sm">
                                                <div class="col-md-12">
                                                    <input type="number" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']"/ disabled>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group mt-sm">
                                                <div class="col-md-12">
                                                    <select class="form-control" name="id_type['.$srno.']" id="id_type['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="get_concession_fields'.$srno.'(this.value)">
                                                        <option vlaue="">Select</option>
                                                        <option value="1">Percentage</option>
                                                        <option value="2">Amount</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group" id="getconcessiontype'.$srno.'">
                                                <label class="col-md-3 control-label">Percentage </label>
                                                <div class="col-md-9">
                                                    <input type="number" class="form-control" name="con_percent['.$srno.']" id="con_percent'.$srno.'" min="0" max="100" required title="Amount should be greater than 0 & less than 100" disabled/>
                                                </div>
                                                <label class="col-md-3 control-label">Amount </label>
                                                <div class="col-md-9 mt-xs">
                                                    <input type="number" class="form-control" name="con_amount['.$srno.']" id="con_amount'.$srno.'" disabled/>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="col-md-12 mb-xs">
                                                    <select class="form-control" name="duration['.$srno.']" id="duration['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="get_duration_fields'.$srno.'(this.value)">
                                                        <option vlaue="">Select</option>
                                                        <option value="1|'.$srno.'">Whole Session</option>
                                                        <option value="2|'.$srno.'">Custom</option>
                                                    </select>
                                                </div>
                                                <div id="getDuration'.$srno.'"></div>
                                            </div>
                                        </td>
                                    </tr>';?>
                                    
                                    <script type="text/javascript">
                                        // GET CONCESSION FIELDS
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

                                    </script>

                                   <?php
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
                            <button type="submit" class="btn btn-primary" id="add_concession" name="add_concession">Save</button>
                            <button class="btn btn-default modal-dismiss">Cancel</button>
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
